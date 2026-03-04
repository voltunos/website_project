<?php

require_once 'database.php';

function requireRole(array $rolesPermitidos, PDO $pdo) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['id_usuario'])) {
        header("Location: login.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT rol, baneado FROM users WHERE id_usuario = :id LIMIT 1");
    $stmt->execute([
        ":id" => $_SESSION['id_usuario']
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['baneado'] == 1) {
    $_SESSION = [];
    session_destroy();
    header("Location: login.php");
    exit();

    if (!in_array($user['rol'], $rolesPermitidos, true)) {
    header("Location: main.php");
    exit();
}
}
}