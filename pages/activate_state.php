<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';

requireRole(["Administrador", "Dueño"], $pdo);

$id_estado = $_POST['id_estado'] ?? '';

if (empty($id_estado)) {
    $_SESSION['error'] = "Error al obtener el estado.";
    header("Location: state.php");
    exit();
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM estado WHERE id_estado = :id_estado");
$stmt->execute([
    ":id_estado" => $id_estado
]);

if ($stmt->fetchColumn() == 0) {
    $_SESSION['error'] = "El estado no existe.";
    header("Location: state.php");
    exit();
}

$stmt2 = $pdo->prepare("UPDATE estado SET activo = 0 WHERE id_estado != :id_estado");
$stmt2->execute([
    ":id_estado" => $id_estado
]);

$stmt3 = $pdo->prepare("UPDATE estado SET activo = 1 WHERE id_estado = :id_estado");
$stmt3->execute([
    ":id_estado" => $id_estado
]);

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("UPDATE estado SET activo = 0");
    $stmt->execute();

    $stmt = $pdo->prepare("UPDATE estado SET activo = 1 WHERE id_estado = :id_estado");
    $stmt->execute([
        ":id_estado" => $id_estado
    ]);

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();

    $_SESSION['error'] = "Error al obtener el estado.";
    header("Location: state.php");
    exit();
}

$_SESSION['success'] = "Estado activado correctamente.";
header("Location: state.php");
exit()

?>