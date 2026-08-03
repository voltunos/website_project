<?php

session_start();
require_once 'database.php';
require_once 'auth.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito = $_SESSION['carrito'];
$productos = [];

$stmt3 = $pdo->prepare("SELECT valor FROM configuracion WHERE clave = 'shipping_cost'");
$stmt3->execute();

$envio = $stmt3->fetchColumn();
$total = $envio;

if (!empty($carrito)) {
    $ids = array_keys($carrito);

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT id_producto, nombre, precio, imagen FROM productos WHERE id_producto IN ($placeholders)");
    $stmt->execute($ids);

    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$stmt2 = $pdo->prepare("SELECT * FROM direcciones WHERE id_usuario = :id_usuario AND activo = 1");
$stmt2->execute([
    ":id_usuario" => $_SESSION['id_usuario']
]);

$direcciones = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$stmt4 = $pdo->prepare("SELECT * FROM estado WHERE activo = 1 LIMIT 1");
$stmt4 -> execute();

$estado = $stmt4->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<head>
    <title>Carrito</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
    </header>
    <span class="edit-title">Carrito</span>
    <div class="cart">
        <div class="cart-products">
            <div class="cart-product">
                <span class="cart-product-text">Producto</span>
                <div></div>
                <span class="cart-product-text">Precio</span>
                <span class="cart-product-text">Cantidad</span>
                <span class="cart-product-text">Opciones</span>
                <span class="cart-product-text">Subtotal</span>
            </div>
            <?php
            foreach ($productos as $producto) {
                $cantidad = $carrito[$producto['id_producto']];
                $subtotal = $producto['precio'] * $cantidad;
                $total += $subtotal;
                echo '<div class="cart-product">';
                echo '<figure>';
                echo '<img class="cart-product-image" src="../uploads/products/'.$producto['imagen'].'">';
                echo '</figure>';
                echo '<span class="cart-product-text">'.$producto['nombre'].'</span>';
                echo '<span class="cart-product-text">$'.$producto['precio'].'</span>';
                echo '<span class="cart-product-text">x'.$cantidad.'</span>';
                echo '<form class="cart-options" action="cart_process.php" method="POST">';
                echo '<input type="hidden" name="id_producto" value="'.$producto['id_producto'].'">';
                echo '<input type="hidden" name="redirect" value="cart">';
                echo '<button class="cart-subtract" type="submit" name="action" value="subtract">-</button>';
                echo '<input class="cart-number" type="number" name="amount" value="1">';
                echo '<button class="cart-add" type="submit" name="action" value="add">+</button>';
                echo '</form>';
                echo '<span class="cart-product-text">$'.$subtotal.'</span>';
                echo '</div>';
            }
            ?>
        </div>
        <div class="cart-payment">
            <div class="account-form-start">
                <span class="account-form-title">Mi pedido</span>
                <img class="logo-big" src="../images/logo_big.png">
            </div>
            <hr class="account-hr">
            <?php
            if (!empty($carrito)) {
                foreach ($productos as $producto) {
                    $cantidad = $carrito[$producto['id_producto']];
                    $subtotal = $producto['precio'] * $cantidad;
                    echo '<div class="purchase">';
                    echo '<span class="purchase-info">x'.$cantidad.' '.$producto['nombre'].'</span>';
                    echo '<span class="purchase-price">$'.$subtotal.'</span>';
                    echo '</div>';
                }
                echo '<div class="purchase">';
                echo '<span class="purchase-info">Envío</span>';
                echo '<span class="purchase-price">$'.$envio.'</span>';
                echo '</div>';
                echo '<hr class="account-hr">';
                echo '<div class="purchase">';
                echo '<span class="purchase-info">Subtotal:</span>';
                echo '<span class="purchase-price">$'.$total.'</span>';
                echo '</div>';
                echo '<form class="direction-select" id="checkout" action="checkout.php" method="POST">';
                echo '<img src="../images/direction2.png" class="icon">';
                echo '<span class="cart-text">Enviar a </span>';
                echo '<select class="cart-direction" name="direccion" required>';
                foreach ($direcciones as $direccion) {
                    echo '<option value="' . htmlspecialchars($direccion['id_direccion'], ENT_QUOTES, 'UTF-8') . '" ' .
                        '>' . htmlspecialchars($direccion['calle'], ENT_QUOTES, 'UTF-8') . ' '. htmlspecialchars($direccion['numero'], ENT_QUOTES, 'UTF-8') .
                        '</option>';
                }
                echo '</select>';
                echo '</form>';
                $message = "Continuar";
                $extra = "";
                if ($estado['compras'] == 0) {
                    $message = "Las compras están desactivadas";
                    $extra = "disabled";
                }
                echo '<button class="cart-button" type="submit" '.$extra.' form="checkout">'.$message.'</button>';
            } else {
                echo '<span class="purchase-info">Agrega un producto al carrito para empezar a ver tu pedido.</span>';
            }
            echo '<span class="cart-text">Métodos de pago aceptados:</span>';
            echo '<div class="direction-select">';
            echo '<img class="icon" src="../images/mp.png">';
            echo '<img class="icon" src="../images/transferencia.png">';
            echo '<img class="icon" src="../images/cash.png">';
            echo '</div>';
            echo '<span class="purchase-info">El precio del envío y los cupones se mostrarán a continuación.</span>';
            ?>
        </div>
    </div>
</body>