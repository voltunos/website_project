<?php

session_start();
require_once 'database.php';
require_once 'auth.php';
require_once 'cart_check.php';
require_once 'state_check.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito = $_SESSION['carrito'];
$productos = [];

$direccion = $_POST['direccion'] ?? '';

if (empty($direccion)) {
    header("Location: cart.php");
}

$stmt3 = $pdo->prepare("SELECT valor FROM configuracion WHERE clave = 'shipping_cost'");
$stmt3->execute();

$envio = $stmt3->fetchColumn();
$total = 0;

if (!empty($carrito)) {
    $ids = array_keys($carrito);

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT id_producto, precio FROM productos WHERE id_producto IN ($placeholders)");
    $stmt->execute($ids);

    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

foreach ($productos as $producto) {
    $cantidad = $carrito[$producto['id_producto']];
    $subtotal = $producto['precio'] * $cantidad;
    $total += $subtotal;
}

$blendPoints = floor(($total + $envio) / 10);

$stmt2 = $pdo->prepare("SELECT * FROM direcciones WHERE id_usuario = :id_usuario AND id_direccion = :id_direccion AND activo = 1");
$stmt2->execute([
    ":id_usuario" => $_SESSION['id_usuario'],
    ":id_direccion" => $direccion
]);

$data = $stmt2->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<head>
    <title>Finalizar compra</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
        <a href="cart.php"><span class="uitext">Volver</span></a>
    </header>
    <div class="checkout">
        <div class="account-form-start">
            <span class="account-form-title">Finalizar compra</span>
            <img class="logo-big" src="../images/logo_big.png">
        </div>
        <hr class="account-hr">
        <span class="account-form-text">Método de pago a utilizar:</span>
        <form class="checkout-form" id="checkout" method="POST" action="checkout_process.php">
            <input type="hidden" name="id_direccion" value="<?php echo $direccion; ?>">
            <label class="checkout-method">
                <img class="icon" src="../images/cash.png">
                <input type="radio" class="checkout-checkbox" name="metodo" value="efectivo" required><span class="account-form-text">Efectivo</span>
            </label>
            <label class="checkout-method">
                <img class="icon" src="../images/transferencia.png">
                <input type="radio" name="metodo" value="transferencia"><span class="account-form-text">Transferencia</span>
            </label>
            <label class="checkout-method">
                <img class="icon" src="../images/mp.png">
                <input type="radio" name="metodo" value="mercadopago"><span class="account-form-text">Mercado pago</span>
            </label>
        </form>
        <div class="checkout-delivery">
            <img src="../images/direction2.png" class="icon">
            <span class="account-form-text">Enviar a <?php echo htmlspecialchars($data['calle'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($data['numero'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
            <div class="checkout-prices">
                <div class="checkout-price">
                <span class="account-form-text">Subtotal</span>
                <span clasS="account-form-text">$<?php echo $total; ?></span>
            </div>
            <div class="checkout-price">
                <span class="account-form-text">Envío</span>
                <span clasS="account-form-text">$<?php echo $envio; ?></span>
            </div>
            <div class="checkout-price">
                <span class="account-form-text">Total</span>
                <span clasS="account-form-text">$<?php echo $total + $envio; ?></span>
            </div>
        </div>
        <div class="checkout-delivery">
            <span class="account-form-text">Ganás </span>
            <img src="../images/blendpoints.png" class="icon">
            <span class="account-form-text"><b><?php echo $blendPoints; ?></b> Puntos Blend con tu compra</span>
        </div>
        <button type="submit" class="account-button" form="checkout">Continuar</button>
        <span class="purchase-info">El siguiente paso es el último para finalizar el encargo del pedido.</span>
    </div>
</body>