<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
require_once '../services/image_service.php';

requireRole(["Administrador"], $pdo);

$nombre = $_POST['nombre'] ?? '';
$precio = $_POST['precio'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$categoria = $_POST['categoria'] ?? '';

if (empty($nombre) || empty($precio) || empty($categoria)) {
    $_SESSION['error'] = "Los campos obligatorios están vacios.";
    header("Location: products.php");
    exit();
}

$precio = filter_var($precio, FILTER_VALIDATE_FLOAT);

if ($precio === false || $precio <= 0) {
    $_SESSION['error'] = "Precio inválido.";
    header("Location: products.php");
    exit();
}

$stmtCat = $pdo->prepare("SELECT COUNT(*) FROM categorias WHERE nombre = :categoria AND activo = 1");
$stmtCat->execute([
    ":categoria" => $categoria
]);

if ($stmtCat->fetchColumn() == 0) {
    $_SESSION['error'] = "La categoría seleccionada es inválida.";
    header("Location: products.php");
    exit();
}

$uploadDir = __DIR__."/../uploads/products/";

try {
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $newImageName = ImageService::uploadImage($_FILES['imagen'], $uploadDir);

        $stmt = $pdo->prepare("INSERT INTO 
        productos (nombre, precio, descripcion, categoria, imagen) 
        VALUES (:nombre, :precio, :descripcion, :categoria, :imagen)");

        $stmt->execute([
            ":nombre" => $nombre,
            ":precio" => $precio,
            ":descripcion" => $descripcion,
            ":categoria" => $categoria,
            ":imagen" => $newImageName
        ]);

    } else {
        $stmt = $pdo->prepare("INSERT INTO 
        productos (nombre, precio, descripcion, categoria) 
        VALUES (:nombre, :precio, :descripcion, :categoria)");

        $stmt->execute([
            ":nombre" => $nombre,
            ":precio" => $precio,
            ":descripcion" => $descripcion,
            ":categoria" => $categoria
        ]);
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: products.php");
    exit();
}

$_SESSION['success'] = "Se agregó el producto correctamente, para verlo vaya a la lista de productos desactivados.";
header("Location: products.php");
exit();

?>