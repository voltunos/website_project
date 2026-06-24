<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';

requireRole(["Administrador"], $pdo);

$id_producto = $_POST['id_producto'] ?? '';

if (empty($id_producto)) {
    $_SESSION['error'] = "No se consuguió el producto.";
    header("Location: products.php");
    exit();
}

$stmt = $pdo->prepare("UPDATE productos SET activo = 1 WHERE id_producto = :id_producto");
$stmt->execute([
    ":id_producto" => $id_producto
]);

$_SESSION['success'] = "Se activó el producto correctamente.";
header("Location: deactivated_products.php");
exit();

?>