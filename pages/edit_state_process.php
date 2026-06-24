<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
require_once '../services/image_service.php';

requireRole(["Administrador"], $pdo);

$id_estado = $_POST['id_estado'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$texto = $_POST['texto'] ?? '';
$compras = $_POST['compras'] ?? '';

if (empty($id_estado)) {
    $_SESSION['error'] = "No se consuguió el estado.";
    header("Location: state.php");
    exit();
}

if (empty($nombre) || empty($texto) || empty($compras)) {
    $_SESSION['error'] = "Los campos obligatorios están vacios.";
    header("Location: state.php");
    exit();
}

$uploadDir = __DIR__."/../uploads/states/";

$stmtImage = $pdo->prepare("SELECT imagen FROM estado WHERE id_estado = :id_estado");
$stmtImage->execute([
    ":id_estado" => $id_estado
]);
$prevImage = $stmtImage->fetchColumn();

if ($prevImage === false) {
    $_SESSION['error'] = "El estado no existe.";
    header("Location: state.php");
    exit();
}

try {
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $newImageName = ImageService::uploadImage($_FILES['imagen'], $uploadDir, $prevImage);

        $stmt = $pdo->prepare("UPDATE estado SET 
        nombre = :nombre, texto = :texto, compras = :compras, imagen = :imagen
        WHERE id_estado = :id_estado");

        $stmt->execute([
            ":id_estado" => $id_estado,
            ":nombre" => $nombre,
            ":texto" => $texto,
            ":compras" => $compras,
            ":imagen" => $newImageName
        ]);

    } else {
        $stmt = $pdo->prepare("UPDATE estado SET 
        nombre = :nombre, texto = :texto, compras = :compras
        WHERE id_estado = :id_estado");

        $stmt->execute([
            ":id_estado" => $id_estado,
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

$_SESSION['success'] = "Se actualizaron los datos del estado correctamente.";
header("Location: state.php");
exit();

?>