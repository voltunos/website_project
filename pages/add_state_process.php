<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
require_once '../services/image_service.php';

requireRole(["Administrador"], $pdo);

$nombre = $_POST['nombre'] ?? '';
$texto = $_POST['texto'] ?? '';
$compras = $_POST['compras'] ?? '';

if (empty($nombre) || empty($texto) || empty($compras)) {
    $_SESSION['error'] = "Los campos obligatorios están vacios.";
    header("Location: state.php");
    exit();
}

$uploadDir = __DIR__."/../uploads/states/";

try {
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $newImageName = ImageService::uploadImage($_FILES['imagen'], $uploadDir);

        $stmt = $pdo->prepare("INSERT INTO 
        estado (nombre, texto, compras, imagen) 
        VALUES (:nombre, :texto, :compras, :imagen)");

        $stmt->execute([
            ":nombre" => $nombre,
            ":texto" => $texto,
            ":compras" => $compras,
            ":imagen" => $newImageName
        ]);

    } else {
        $stmt = $pdo->prepare("INSERT INTO 
        estado (nombre, texto, compras) 
        VALUES (:nombre, :texto, :compras)");

        $stmt->execute([
            ":nombre" => $nombre,
            ":texto" => $texto,
            ":compras" => $compras
        ]);
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: state.php");
    exit();
}

$_SESSION['success'] = "Se agregó el estado, puede activarlo en cualquier momento.";
header("Location: state.php");
exit();

?>