<?php

require_once 'database.php';

$stmt = $pdo->prepare("SELECT compras FROM estado WHERE activo = 1 LIMIT 1");
$stmt->execute();

$habilitar = $stmt->fetchColumn();

if ((int)$habilitar !== 1) {
    header("Location: main.php");
    exit();
}

?>