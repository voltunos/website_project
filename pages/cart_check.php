<?php

require_once 'database.php';
require_once 'auth.php';

$carrito = $_SESSION['carrito'] ?? [];

if (empty($carrito)) {
    header("Location: main.php");
    exit();
}

foreach ($carrito as $id => $cantidad) {
    if (!is_numeric($id) || !is_numeric($cantidad) || $cantidad <= 0) {
        $_SESSION['carrito'] = [];

        header("Location: main.php");
        exit();
    }
}

$ids = array_keys($carrito);

$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $pdo->prepare("SELECT id_producto FROM productos WHERE id_producto IN ($placeholders) AND activo = 1");
$stmt->execute($ids);

$productosActivos = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (count($productosActivos) !== count($ids)) {
    $_SESSION['carrito'] = [];
    header("Location: main.php");
    exit();
}

?>