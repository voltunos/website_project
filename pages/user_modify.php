<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
requireRole(["Administrador", "Dueño"], $pdo);

$userid = $_SESSION['id_usuario'] ?? "";

$id = $_POST['id'] ?? "";
$option = $_POST['option'] ?? "";
$additional = $_POST['additional'] ?? "";

if (empty($id) || empty($option)) {
    $_SESSION['error'] = "No se recibieron los datos necesarios.";
    header("Location: user.php?id=".$id."");
    exit();
}

if ($id == $userid && $option != "blendpoints") {
    $_SESSION['error'] = "No puedes realizar esta acción sobre tí mismo.";
    header("Location: user.php?id=".$id."");
    exit();
}

$roleCheck = $pdo->prepare("SELECT rol FROM users WHERE id_usuario = :id_usuario LIMIT 1");
$roleCheck->execute([
    ":id_usuario" => $id
]);

$userRole = $roleCheck->fetchColumn();
if ($userRole == "Dueño") {
    $_SESSION['error'] = "No puedes realizar esta acción sobre este usuario.";
    header("Location: user.php?id=".$id."");
    exit();
}

if ($option == "ban") {
    try {
        $stmt = $pdo->prepare("UPDATE users SET baneado = 1 WHERE id_usuario = :id_usuario LIMIT 1");
        $stmt->execute([
            ":id_usuario" => $id
        ]);
        
        $_SESSION['success'] = "Se baneó al usuario.";
        header("Location: user.php?id=".$id."");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
} else if ($option == "unban") {
    try {
        $stmt = $pdo->prepare("UPDATE users SET baneado = 0 WHERE id_usuario = :id_usuario LIMIT 1");
        $stmt->execute([
            ":id_usuario" => $id
        ]);
        
        $_SESSION['success'] = "Se desbaneó al usuario.";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: user.php?id=".$id."");
        exit();
    }
} else if ($option == "role") {
    try {
        $rolesAvailable = ["client", "admin", "delivery"];
        if (empty($additional)) {
            $_SESSION['error'] = "No se recibió el rol a dar correctamente.";
            header("Location: user.php?id=".$id."");
            exit();
        }

        if (!in_array($additional, $rolesAvailable)) {
            $_SESSION['error'] = "El rol es inválido.";
            header("Location: user.php?id=".$id."");
            exit();
        }
        
        switch($additional) {
            case "client":
                $roleToGive = "Cliente";
            break;
            case "admin":
                $roleToGive = "Administrador";
            break;
            case "delivery":
                $roleToGive = "Delivery";
            break;
        }

        $stmt = $pdo->prepare("UPDATE users SET rol = :rol WHERE id_usuario = :id_usuario LIMIT 1");
        $stmt->execute([
            ":rol" => $roleToGive,
            ":id_usuario" => $id
        ]);

        $_SESSION['success'] = "Se concedió el rol correctamente";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: user.php?id=".$id."");
        exit();
    }
} else if ($option == "blendpoints") {
    try {
        $amount = $_POST['additional'] ?? "";
        if (!ctype_digit($amount)) {
            $_SESSION['error'] = "La cantidad no es válida.";
            header("Location: user.php?id=".$id."");
            exit();
        }
        $stmt = $pdo->prepare("UPDATE users SET blendpoints = blendpoints + :points WHERE id_usuario = :id_usuario");
        $stmt->execute([
            ":points" => $amount,
            ":id_usuario" => $id
        ]);

        $_SESSION['success'] = "Se otorgaron los Puntos Blend correctamente.";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: user.php?id=".$id."");
        exit();
    }
}

header("Location: user.php?id=".$id."");
exit();

?>