<?php

include_once 'database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit();
}

$correo = trim($_POST['correo'] ?? "");
$nombre = $_POST['nombre'] ?? "";
$apellido = $_POST['apellido'] ?? "";
$telefono = trim($_POST['telefono'] ?? "");
$contra = $_POST['contra'] ?? "";

if (empty($correo) || empty($nombre) || empty($apellido) || empty($telefono) || empty($contra)) {
    $_SESSION['error'] = "Todos los campos son obligatorios.";
    header("Location: register.php");
    exit();
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "El correo electrónico no es válido.";
    header("Location: register.php");
    exit();
}

if (strlen($contra) < 8) {
    $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres.";
    header("Location: register.php");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id_usuario FROM users WHERE correo = :correo");
    $stmt->execute([
        ":correo" => $correo,
    ]);

    if ($stmt->fetch()) {
        $_SESSION['error'] = "Este correo ya está registrado.";
        header("Location: register.php");
        exit();
    }

    $passhash = password_hash($contra, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (correo, contra, nombre, apellido, telefono, created_at) 
    VALUES (:correo, :contra, :nombre, :apellido, :telefono, NOW())");

    $stmt->execute([
        ":correo" => $correo,
        ":contra" => $passhash,
        ":nombre" => $nombre,
        ":apellido" => $apellido,
        ":telefono" => $telefono
    ]);

    $_SESSION['success'] = "Cuenta registrada con éxito, ya puede iniciar sesión.";
    header("Location: register.php");
    exit();
} catch(PDOException $e) {
    die("Error en el registro: " . $e->getMessage());
}

?>