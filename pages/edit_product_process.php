<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
require_once '../services/image_service.php';

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

$stmtImage = $pdo->prepare("SELECT imagen FROM productos WHERE id_producto = :id_producto");
$stmtImage->execute([
    ":id_producto" => $id_producto
]);
$prevImage = $stmtImage->fetchColumn();

if ($prevImage === false) {
    $_SESSION['error'] = "El producto no existe.";
    header("Location: products.php");
    exit();
}

try {
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $newImageName = ImageService::uploadImage($_FILES['imagen'], $uploadDir, $prevImage);

        $stmt = $pdo->prepare("UPDATE productos SET 
        nombre = :nombre, precio = :precio, descripcion = :descripcion, categoria = :categoria, imagen = :imagen
        WHERE id_producto = :id_producto");

        $stmt->execute([
            ":id_producto" => $id_producto,
            ":nombre" => $nombre,
            ":precio" => $precio,
            ":descripcion" => $descripcion,
            ":categoria" => $categoria,
            ":imagen" => $newImageName
        ]);

    } else {
        $stmt = $pdo->prepare("UPDATE productos SET 
        nombre = :nombre, precio = :precio, descripcion = :descripcion, categoria = :categoria
        WHERE id_producto = :id_producto");

        $stmt->execute([
            ":id_producto" => $id_producto,
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

$_SESSION['success'] = "Se actualizaron los datos del producto correctamente.";
header("Location: products.php");
exit();

?>