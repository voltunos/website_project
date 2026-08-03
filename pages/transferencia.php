<?php

session_start();
require_once 'database.php';
require_once 'auth.php';
require_once 'cart_check.php';
require_once 'state_check.php';
require_once 'direction_check.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito = $_SESSION['carrito'];
$productos = [];

if (!isset($_SESSION['id_direccion'])) {
    header("Location: cart.php");
}

$direccion = $_SESSION['id_direccion'] ?? '';
$additional = $_SESSION['adicional'] ?? '';

unset($_SESSION['adicional']);

if (empty($direccion)) {
    header("Location: cart.php");
}

$stmt3 = $pdo->prepare("SELECT clave, valor FROM configuracion WHERE clave IN ('shipping_cost', 'alias')");
$stmt3->execute();

$config = $stmt3->fetchAll(PDO::FETCH_KEY_PAIR);
$alias = $config['alias'];
$envio = $config['shipping_cost'];

$total = $envio;

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

$blendPoints = floor($total / 10);

$stmt2 = $pdo->prepare("SELECT * FROM direcciones WHERE id_usuario = :id_usuario AND id_direccion = :id_direccion AND activo = 1");
$stmt2->execute([
    ":id_usuario" => $_SESSION['id_usuario'],
    ":id_direccion" => $direccion
]);

$data = $stmt2->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<head>
    <title>Pago con transferencia</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
        <a href="checkout.php"><span class="uitext">Volver</span></a>
    </header>
    <div class="checkout">
        <div class="account-form-start">
            <span class="account-form-title">Pago con transferencia</span>
            <img class="logo-big" src="../images/logo_big.png">
        </div>
        <hr class="account-hr">
        <form id="checkout" method="POST" action="process_payment.php">
            <input type="hidden" name="method" value="transferencia">
            <input type="hidden" name="id_direccion" value="<?php echo $direccion; ?>">
            <input type="hidden" name="additional" value="<?php echo $additional; ?>">
        </form>
        <div class="checkout-form">
            <span class="account-form-text"><b>Dirección de entrega</b></span>
            <span class="account-form-text"><?php echo htmlspecialchars($data['calle'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($data['numero'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <div class="checkout-form">
        <span class="account-form-text"><b>Importe a transferir</b></span>
        <span class="account-form-text">$<?php echo $total; ?></span>
        </div>
        <div class="checkout-form">
        <span class="account-form-text"><b>Alias</b></span>
        <span class="account-form-text"><?php echo $alias; ?></span>
        </div>
        <div class="checkout-form">
        <span class="account-form-text"><b>Titular de la transferencia</b></span>
        <input class="account-form-input" type="text" name="titular" placeholder="Titular de la transferencia" form="checkout" required>
        <span class="account-form-additional">Ingrese el nombre y apellido del titular de la cuenta utilizada para realizar el pago.</span>
        </div>
        <div class="checkout-form">
        <span class="account-form-text"><b>Recompensa</b></span>
        <span class="account-form-text">Recibirá aproximadamente <?php echo $blendPoints; ?> Puntos Blend al completarse el pedido.</span>
        </div>
        <button type="submit" class="account-button" form="checkout">Realizar pedido</button>
    </div>
</body>