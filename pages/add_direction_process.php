<?php

require_once 'database.php';
require_once 'auth.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
    header("Location: directions.php");
    exit();
}

$calle = $_POST['calle'] ?? "";
$numero = $_POST['numero'] ?? "";
$adicional = $_POST['adicional'] ?? "";

$id_usuario = $_SESSION['id_usuario'];

if (empty($calle) || empty($numero)) {
    $_SESSION['error'] = "Los campos obligatorios están vacios.";
    header("Location: directions.php");
    exit();
}

$stmt2 = $pdo->prepare("SELECT COUNT(*) FROM direcciones WHERE id_usuario = :id_usuario AND activo = 1");
$stmt2 -> execute([
    ":id_usuario" => $id_usuario
]);

$total = $stmt2->fetchColumn();

if ($total >= 5) {
    $_SESSION['error'] = "Has alcanzado el máximo de direcciones por usuario, elimine alguna dirección para poder agregar más.";
    header("Location: directions.php");
    exit();
}

$stmt = $pdo->prepare("INSERT INTO direcciones(calle, numero, adicional, id_usuario) VALUES(:calle, :numero, :adicional, :id_usuario)");
$stmt -> execute([
    ":calle" => $calle,
    ":numero" => $numero,
    ":adicional" => $adicional,
    ":id_usuario" => $id_usuario
]);

$_SESSION['success'] = "Se agregó la dirección correctamente.";
header("Location: directions.php");
exit();

?>