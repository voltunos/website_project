<?php

session_start();
require_once 'database.php';
require_once 'auth.php';
require_once 'cart_check.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito = $_SESSION['carrito'];
$productos = [];

if (!isset($_SESSION['id_direccion'])) {
    header("Location: cart.php");
}

$direccion = $_SESSION['id_direccion'] ?? '';

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
    <title>Pago en efectivo</title>

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
            <span class="account-form-title">Pago en efectivo</span>
            <img class="logo-big" src="../images/logo_big.png">
        </div>
        <hr class="account-hr">
        <form id="checkout" method="POST" action="process_payment.php">
            <input type="hidden" name="method" value="efectivo">
            <input type="hidden" name="id_direccion" value="<?php echo $direccion; ?>">
        </form>
        <span class="account-form-text">Importante:</span>
        <span class="account-form-text">El pedido se enviará a la dirección <b><?php echo htmlspecialchars($data['calle'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($data['numero'], ENT_QUOTES, 'UTF-8') ?></b> una vez preparado, y tendrá que pagar la suma de <b>$<?php echo $total; ?></b> (envío incluído) en efectivo al recibirlo. Si hay alguna complicación, se utilizará su número de teléfono establecido en su cuenta para contactarlo/a. Una vez recibido y completado el pedido, obtendrá una cantidad estimada de <b><?php echo $blendPoints; ?></b> Puntos Blend en su cuenta.</span>
        <button type="submit" class="account-button" form="checkout">Realizar pedido</button>
    </div>
</body>