import {MercadoPagoConfig, Preference, Payment} from "mercadopago"
import { MERCADOPAGO_API_KEY } from "../config.js";
import {db} from './db.js'

export const createOrder = async (req, res) => { 
    try {
        const {carrito, id_usuario, id_direccion} = req.body;

        if (!carrito || typeof carrito !== 'object') {
            return res.status(400).json({error: "Invalid cart"});
        }

        if (!id_usuario || isNaN(id_usuario)) {
            return res.status(400).json({error: "Invalid user ID"});
        }

        if (!id_direccion || isNaN(id_direccion)) {
            return res.status(400).json({error: "Invalid direction ID"});
        }

        const [rows] = await db.query("SELECT id_direccion FROM direcciones WHERE id_direccion = ? AND id_usuario = ? AND activo = 1",
            [id_direccion, id_usuario]
        );

        if (rows.length === 0) {
            throw new Error("Invalid direction");
        }

        const items = [];

        const [rows3] = await db.query("SELECT valor FROM configuracion WHERE clave = 'shipping_cost'");
        if (rows3.length === 0) {
            throw new Error("Shipping price not obtained");
        }

        const shippingCost = Number(rows3[0].valor);

        items.push({
            id: "shipping",
            title: "Costo de envío",
            unit_price: shippingCost,
            currency_id: "ARS",
            quantity: 1
        });

        for (const [id, quant] of Object.entries(carrito)) {
            const quantNum = Number(quant);
            const idNum = Number(id);

            if (isNaN(idNum) || isNaN(quantNum) || quantNum <= 0) {
                return res.status(400).json({error: "Invalid cart data"});
            }

            const [rows2] = await db.query("SELECT nombre, precio FROM productos WHERE id_producto = ? AND activo = 1", [idNum]);

            if (rows2.length === 0) {
                return res.status(400).json({error: `Product ID ${idNum} doesn't exist`});
            }

            const product = rows2[0];

            items.push({
                id: String(idNum),
                title: product.nombre,
                unit_price: Number(product.precio),
                currency_id: "ARS",
                quantity: quantNum,
            });
        }

        const external_reference = `${id_usuario}|${id_direccion}|${shippingCost}|${Date.now()}`;

        const client = new MercadoPagoConfig({
            accessToken: MERCADOPAGO_API_KEY
        });

        const preference = new Preference(client);

        const result = await preference.create({
            body: {
                items,
                external_reference,
                back_urls: {
                    success: "https://needingly-semicolloquial-jacoby.ngrok-free.dev/api/payments/success",
                    failure: "https://needingly-semicolloquial-jacoby.ngrok-free.dev/api/payments/failure",
                    pending: "https://needingly-semicolloquial-jacoby.ngrok-free.dev/api/payments/pending",
                },
                notification_url: "https://needingly-semicolloquial-jacoby.ngrok-free.dev/api/payments/webhook",
            },
        });

        return res.json({
            id: result.id,
            init_point: result.init_point
        });
        } catch (error) {
            console.log("Error in createOrder:", error);

            return res.status(500).json({
                error: "Internal server error"
            });
        }
};

export const receiveWebhook = async (req, res) => {
    try {
        console.log("Webhook received: ", req.body);

        const {type, data} = req.body;

        const paymentInfo = req.body;

        if (type !== "payment") {
            return res.sendStatus(204);
        }

        if (!data || !data.id) {
            return res.sendStatus(204);
        }

        const client = new MercadoPagoConfig({
            accessToken: MERCADOPAGO_API_KEY
        });

        const payment = new Payment(client);

        const paymentData = await payment.get({id: data.id});
        if (paymentData.status !== "approved") {
            console.log("Payment isn't approved");
            return res.sendStatus(204);
        }

        console.log("Payment success: ", paymentData);

        const [existing] = await db.query("SELECT id_pedido FROM pedido WHERE external_reference = ?", [paymentData.external_reference]);

        if (existing.length > 0) {
            console.log("Order already processed");
            return res.sendStatus(204);
        }

        const external_reference = paymentData.external_reference;
        if (!external_reference) {
            console.log("External reference doesn't exist");
            return res.sendStatus(204);
        }

        const parts = external_reference.split("|");

        if (parts.length < 4) {
            console.log("External reference incomplete");
            return res.sendStatus(204);
        }

        const id_usuario = Number(parts[0]);
        const id_direccion = Number(parts[1]);
        const shipping = Number(parts[2]);

        if (isNaN(id_usuario) || isNaN(id_direccion) || isNaN(shipping)) {
            console.log("Invalid external reference values");
            return res.sendStatus(204);
        }

        const total = paymentData.transaction_amount - shipping;

        const items = paymentData.additional_info?.items || [];

        if (!items.length) {
            console.log("No items in payment");
            return res.sendStatus(204);
        }

        const connection = await db.getConnection();
        await connection.beginTransaction();

        try {
            const [pedidoResult] = await connection.query("INSERT INTO pedido (id_usuario, envio, total, fecha, direccion, metodo_pago, estado, external_reference) VALUES (?, ?, ?, NOW(), ?, 'mercadopago', 'En proceso', ?)", [id_usuario, shipping, total, id_direccion, external_reference]);

            const id_pedido = pedidoResult.insertId;

            for (const item of items) {
                if (item.id === "shipping") {
                    continue;
                }
                const id_producto = item.id;
                const cantidad = item.quantity;

                const [rows] = await connection.query("SELECT precio FROM productos WHERE id_producto = ?", [id_producto]);

                if (rows.length === 0) {
                    throw new Error(`Product ID ${id_producto} not found`);
                }

                const precio = rows[0].precio;

                await connection.query("INSERT INTO pedidos_items (id_pedido, id_producto, precio, cantidad) VALUES (?, ?, ?, ?)", [id_pedido, id_producto, precio, cantidad]);
            }

            await connection.commit();

            console.log("Order made successfully");
        } catch (error) {
            await connection.rollback();
            console.log("Error in transaction:", error);
            throw error;
        } finally {
            connection.release();
        }

        return res.sendStatus(204);
    } catch (error) {
        console.log("Error in webhook:", error);
        return res.sendStatus(500);
    }
};