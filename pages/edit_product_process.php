<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
requireRole(["Administrador"], $pdo);

$id_producto = $_POST['id_producto'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$precio = $_POST['precio'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$categoria = $_POST['categoria'] ?? '';

if (empty($id_producto)) {
    $_SESSION['error'] = "No se consuguió el producto.";
    header("Location: products.php");
    exit();
}

if (empty($nombre) || empty($precio) || empty($categoria)) {
    $_SESSION['error'] = "Los campos obligatorios están vacios.";
    header("Location: products.php");
    exit();
}

if (!empty($_FILES['imagen']['tmp_name'])) {
    $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    $stmt = $pdo->prepare("UPDATE productos SET nombre = :nombre, precio = :precio, descripcion = :descripcion, categoria = :categoria, imagen = :imagen WHERE id_producto = :id_producto");
    $stmt -> execute([
        ":id_producto" => $id_producto,
        ":nombre" => $nombre,
        ":precio" => $precio,
        ":descripcion" => $descripcion,
        ":categoria" => $categoria,
        ":imagen" => $imagen
    ]);
} else {
    $stmt = $pdo->prepare("UPDATE productos SET nombre = :nombre, precio = :precio, descripcion = :descripcion, categoria = :categoria WHERE id_producto = :id_producto");
    $stmt -> execute([
        ":id_producto" => $id_producto,
        ":nombre" => $nombre,
        ":precio" => $precio,
        ":descripcion" => $descripcion,
        ":categoria" => $categoria
    ]);
}

$_SESSION['success'] = "Se actualizaron los datos del producto correctamente.";
header("Location: products.php");
exit();

?>