<?php 

require_once 'database.php';

$id = $_SESSION['id_usuario'] ?? "";
$direction = $_SESSION['id_direccion'] ?? "";

if (empty($direction) || empty($id)) {
    header("Location: main.php");
    exit();
}

$stmt = $pdo->prepare("SELECT id_direccion FROM direcciones WHERE id_usuario = :id_usuario AND id_direccion = :id_direccion AND activo = 1 LIMIT 1");
$stmt->execute([
    ":id_usuario" => $id,
    ":id_direccion" => $direction
]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    header("Location: main.php");
    exit();
}

?>