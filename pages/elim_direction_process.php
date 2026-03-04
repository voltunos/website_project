<?php

require_once 'database.php';
require_once 'auth.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
    header("Location: directions.php");
    exit();
}

$id_direccion = $_POST['id_direccion'] ?? null;
$id_usuario = $_SESSION['id_usuario'];

$stmt = $pdo->prepare("SELECT id_usuario FROM direcciones WHERE id_direccion = :id_direccion LIMIT 1");
$stmt -> execute([
    ":id_direccion" => $id_direccion
]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$result || $result['id_usuario'] != $id_usuario) {
    $_SESSION['error'] = "Dirección inválida.";
    header("Location: directions.php");
    exit();
}

$stmt2 = $pdo->prepare("UPDATE direcciones SET activo = 0 WHERE id_direccion = :id_direccion LIMIT 1");
$stmt2->execute([
    ":id_direccion" => $id_direccion
]);

$_SESSION['success'] = "Se eliminó la dirección correctamente.";
header("Location: directions.php");
exit();

?>