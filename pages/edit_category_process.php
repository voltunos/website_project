<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
require_once '../services/image_service.php';

requireRole(["Administrador"], $pdo);

$id_categoria = $_POST['id_categoria'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$display = $_POST['display'] ?? '';

if (empty($id_categoria)) {
    $_SESSION['error'] = "No se consuguió la categoria.";
    header("Location: categories.php");
    exit();
}

if (empty($nombre) || empty($display)) {
    $_SESSION['error'] = "Los campos obligatorios están vacios.";
    header("Location: categories.php");
    exit();
}

$uploadDir = __DIR__."/../uploads/categories/";

$stmtImage = $pdo->prepare("SELECT imagen FROM categorias WHERE id_categoria = :id_categoria");
$stmtImage->execute([
    ":id_categoria" => $id_categoria
]);
$prevImage = $stmtImage->fetchColumn();

if ($prevImage === false) {
    $_SESSION['error'] = "La categoría no existe.";
    header("Location: categories.php");
    exit();
}

try {
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $newImageName = ImageService::uploadImage($_FILES['imagen'], $uploadDir, $prevImage);

        $stmt = $pdo->prepare("UPDATE categorias SET 
        nombre = :nombre, display = :display, imagen = :imagen
        WHERE id_categoria = :id_categoria");

        $stmt->execute([
            ":id_categoria" => $id_categoria,
            ":nombre" => $nombre,
            ":display" => $display,
            ":imagen" => $newImageName
        ]);

    } else {
        $stmt = $pdo->prepare("UPDATE categorias SET 
        nombre = :nombre, display = :display, imagen = :imagen
        WHERE id_categoria = :id_categoria");

        $stmt->execute([
            ":id_categoria" => $id_categoria,
            ":nombre" => $nombre,
            ":display" => $display
        ]);
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: categories.php");
    exit();
}

$_SESSION['success'] = "Se actualizaron los datos de la categoría correctamente.";
header("Location: categories.php");
exit();

?>