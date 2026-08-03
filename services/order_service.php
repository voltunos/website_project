<?php

require_once 'cart_service.php';

class OrderService {
    public static function createOrder(PDO $pdo, int $userid, int $shipping, int $direction, string $method, string $additional, array $items) {
        $pdo->beginTransaction();
        try {
            $total = CartService::calculateOrderTotal($items);
            $stmt = $pdo->prepare("INSERT INTO pedido (id_usuario, envio, total, direccion, metodo_pago, adicional, estado) VALUES (:id_usuario, :envio, :total, :direccion, :metodo_pago, :adicional, :estado)");
            $stmt->execute([
                ":id_usuario" => $userid,
                ":envio" => $shipping,
                ":total" => $total,
                ":direccion" => $direction,
                ":metodo_pago" => $method,
                ":adicional" => $additional,
                ":estado" => "En proceso"
            ]);
            $orderId = (int)$pdo->lastInsertId();

            $stmt2 = $pdo->prepare("INSERT INTO pedidos_items (id_pedido, id_producto, precio, cantidad) VALUES (:id_pedido, :id_producto, :precio, :cantidad)");
            foreach ($items as $product) {
                $stmt2->execute([
                    ":id_pedido" => $orderId,
                    ":id_producto" => $product['id_producto'],
                    ":precio" => $product['precio'],
                    ":cantidad" => $product['cantidad']
                ]);
            }
            $pdo->commit();

            return $orderId;
        } catch(Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}

?>