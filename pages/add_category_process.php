<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
require_once '../services/image_service.php';

requireRole(["Administrador"], $pdo);

$nombre = $_POST['nombre'] ?? '';
$display = $_POST['display'] ?? '';

if (empty($nombre) || empty($display)) {
    $_SESSION['error'] = "Los campos obligatorios están vacios.";
    header("Location: categories.php");
    exit();
}

$uploadDir = __DIR__."/../uploads/categories/";

try {
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $newImageName = ImageService::uploadImage($_FILES['imagen'], $uploadDir);

        $stmt = $pdo->prepare("INSERT INTO 
        categorias (nombre, display, imagen) 
        VALUES (:nombre, :display, :imagen)");

        $stmt->execute([
            ":nombre" => $nombre,
            ":display" => $display,
            ":imagen" => $newImageName
        ]);

    } else {
        $stmt = $pdo->prepare("INSERT INTO 
        categorias (nombre, display) 
        VALUES (:nombre, :display)");

        $stmt->execute([
            ":nombre" => $nombre,
            ":display" => $display
        ]);
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: categories.php");
    exit();
}

$_SESSION['success'] = "Se agregó la categoría, para verla vaya a la lista de categorías desactivadas.";
header("Location: categories.php");
exit();

?>