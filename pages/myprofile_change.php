<?php

require_once 'database.php';
require_once 'auth.php';
require_once '../services/image_service.php';

$id_usuario = $_SESSION['id_usuario'] ?? "";
$method = $_POST['action'] ?? "";

if (empty($method) || empty($id_usuario)) {
    header("Location: main.php");
    exit();
}

if ($method === "profile_change") {
    try {
        $nombre = $_POST['nombre'] ?? "";
        $apellido = $_POST['apellido'] ?? "";
        $telefono = $_POST['telefono'] ?? "";

        if (empty($nombre) || empty($apellido) || empty($telefono)) {
            $_SESSION['error'] = "Los campos obligatorios están vacios.";
            header("Location: main.php");
            exit();
        }

        if (!empty($_FILES['imagen']['tmp_name'])) {
            $stmtImage = $pdo->prepare("SELECT imagen FROM users WHERE id_usuario = :id_usuario");
            $stmtImage->execute([
                ":id_usuario" => $id_usuario
            ]);
            $prevImage = $stmtImage->fetchColumn();

            $uploadDir = __DIR__."/../uploads/users/";

            $newImageName = ImageService::uploadImage($_FILES['imagen'], $uploadDir, $prevImage);
            $stmt = $pdo->prepare("UPDATE users SET nombre = :nombre, apellido = :apellido, telefono = :telefono, imagen = :imagen WHERE id_usuario = :id_usuario");
            $stmt->execute([
                ":nombre" => $nombre,
                ":apellido" => $apellido,
                ":telefono" => $telefono,
                ":imagen" => $newImageName,
                ":id_usuario" => $id_usuario
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET nombre = :nombre, apellido = :apellido, telefono = :telefono WHERE id_usuario = :id_usuario");
            $stmt->execute([
                ":nombre" => $nombre,
                ":apellido" => $apellido,
                ":telefono" => $telefono,
                ":id_usuario" => $id_usuario
            ]);
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: myprofile.php");
        exit();
    }
} else if ($method === "password") {
    try {
        $passnow = $_POST['passnow'] ?? "";
        $pass1 = $_POST['pass1'] ?? "";
        $pass2 = $_POST['pass2'] ?? "";

        if (empty($passnow) || empty($pass1) || empty($pass2)) {
            $_SESSION['error'] = "Los campos obligatorios están vacios.";
            header("Location: myprofile.php");
            exit();
        }

        $stmt = $pdo->prepare("SELECT contra FROM users WHERE id_usuario = :id_usuario");
        $stmt->execute([
            ":id_usuario" => $id_usuario
        ]);
        $oldpass = $stmt->fetchColumn();

        if (!password_verify($passnow, $oldpass)) {
            $_SESSION['error'] = "La contraseña actual es incorrecta.";
            header("Location: myprofile.php");
            exit();
        }

        if ($pass1 !== $pass2) {
            $_SESSION['error'] = "Las contraseñas no coinciden.";
            header("Location: myprofile.php");
            exit();
        }

        $passhash = password_hash($pass1, PASSWORD_DEFAULT);

        $stmt2 = $pdo->prepare("UPDATE users SET contra = :contra WHERE id_usuario = :id_usuario LIMIT 1");
        $stmt2->execute([
            ":contra" => $passhash,
            ":id_usuario" => $id_usuario
        ]);
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: myprofile.php");
        exit();
    }
}

header("Location: myprofile.php");
exit();

?>