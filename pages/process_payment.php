<?php 

session_start();
require_once 'database.php';
require_once 'auth.php';
require_once 'cart_check.php';
require_once 'state_check.php';
require_once 'direction_check.php';
require_once '../services/config_service.php';
require_once '../services/product_service.php';
require_once '../services/order_service.php';

$carrito = $_SESSION['carrito'] ?? [];
$id = $_SESSION['id_usuario'] ?? "";

$method = $_POST['method'] ?? "";
$direction = $_POST['id_direccion'] ?? "";
$additional = trim($_POST['additional'] ?? "");
$titular = trim($_POST['titular'] ?? "");

$availableMethods = ["efectivo", "transferencia"];

if (empty($method) || empty($direction) || !in_array($method, $availableMethods)) {
    header("Location: cart.php");
    exit();
}

try {
    $shipment = ConfigService::getShippingCost($pdo);
    $items = ProductService::getProductsFromCart($carrito, $pdo);
    if (!empty($titular)) {
        $additional = "Titular: ".$titular." | ".$additional;
    }
    OrderService::createOrder($pdo, $id, $shipment, $direction, $method, $additional, $items);
    unset($_SESSION['carrito']);
    header("Location: my_orders.php");
} catch(Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: main.php");
}

exit();

?>