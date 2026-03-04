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

$calle = $_POST['calle'] ?? "";
$numero = $_POST['numero'] ?? "";
$adicional = $_POST['adicional'] ?? "";

if (!$id_direccion) {
    $_SESSION['error'] = "No se consiguió la dirección correctamente.";
    header("Location: directions.php");
    exit();
}

if (empty($calle) || empty($numero)) {
    $_SESSION['error'] = "Los campos obligatorios están vacios.";
    header("Location: directions.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM direcciones WHERE id_direccion = :id_direccion LIMIT 1");
$stmt -> execute([
    ":id_direccion" => $id_direccion
]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$result || $result['id_usuario'] != $id_usuario) {
    $_SESSION['error'] = "Dirección inválida.";
    header("Location: directions.php");
    exit();
}

$stmt2 = $pdo->prepare("UPDATE direcciones SET calle = :calle, numero = :numero, adicional = :adicional WHERE id_direccion = :id_direccion");
$stmt2 -> execute([
    ":calle" => $calle,
    ":numero" => $numero,
    ":adicional" => $adicional,
    ":id_direccion" => $id_direccion
]);

$_SESSION['success'] = "Se editaron los datos de la dirección correctamente.";
header("Location: directions.php");
exit();

?>