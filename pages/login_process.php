<?php

require_once 'database.php';

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
    header("Location: login.php");
    exit();
}

$correo = trim($_POST['correo'] ?? "");
$contra = $_POST['contra'] ?? "";

if (empty($correo) || empty($contra)) {
    $_SESSION['error'] = "Todos los campos son obligatorios.";
    header("Location: login.php");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id_usuario, correo, contra, rol, baneado FROM users WHERE correo = :correo LIMIT 1");
    $stmt -> execute([
        ':correo' => $correo,
    ]);

    $usuario = $stmt -> fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $_SESSION['error'] = "Correo electrónico no encontrado.";
        header("Location: login.php");
        exit();
    }

    if ($usuario['baneado'] == 1) {
        $_SESSION['error'] = "Tu cuenta fue suspendida.";
        header("Location: login.php");
        exit();
    }

    if (!password_verify($contra, $usuario['contra'])) {
        $_SESSION['error'] = "Contraseña incorrecta.";
        header("Location: login.php");
        exit();
    }

    session_regenerate_id(true);

    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['rol'] = $usuario['rol'];
    header("Location: main.php");
    exit();
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}

?>