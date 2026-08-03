<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';

requireRole(["Administrador", "Dueño"], $pdo);

$id_categoria = $_POST['id_categoria'] ?? '';

if (empty($id_categoria)) {
    $_SESSION['error'] = "No se consuguió la categoría";
    header("Location: products.php");
    exit();
}

$stmt = $pdo->prepare("UPDATE categorias SET activo = 0 WHERE id_categoria = :id_categoria");
$stmt->execute([
    ":id_categoria" => $id_categoria
]);

$_SESSION['success'] = "Se desactivó la categoría correctamente.";
header("Location: categories.php");
exit();

?>