<?php

session_start();
require_once 'database.php';
require_once 'auth.php';

$id_producto = $_POST['id_producto'] ?? '';
$action = $_POST['action'] ?? '';
$amount = $_POST['amount'] ?? 1;

$amount = (int)$amount;

if (empty($id_producto) || empty($action)) {
    header("Location: main.php");
    exit();
}

$stmt = $pdo->prepare("SELECT activo FROM productos WHERE id_producto = :id_producto");
$stmt->execute([
    ":id_producto" => $id_producto
]);

$active = $stmt->fetchColumn();

if ($active == 0) {
    header("Location: main.php");
    exit();
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$found = false;

switch ($action) {
    case "add":
        if (!isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto] = 0;
        }

        $_SESSION['carrito'][$id_producto] += $amount;
    break;
    
    case "subtract":
        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto] -= $amount;

            if ($_SESSION['carrito'][$id_producto] <= 0) {
                unset($_SESSION['carrito'][$id_producto]);
            }
        }
    break;

    case "remove":
        unset($_SESSION['carrito'][$id_producto]);
    break;

    case "empty":
        $_SESSION['carrito'] = [];
    break;
}

header("Location: main.php");
exit();
?>