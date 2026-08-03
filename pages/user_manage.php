<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
requireRole(["Administrador", "Dueño"], $pdo);

$users_limit = 10;

$page = $_GET['page'] ?? 1;
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $users_limit;

$stmt = $pdo->prepare("SELECT * FROM users ORDER BY id_usuario ASC LIMIT :limit OFFSET :offset");

$stmt->bindValue(':limit', $users_limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_pages = ceil($total_users / $users_limit);

$range = 4;

$start = $page - $range;
$end = $page + $range;

if ($start < 1) {
    $start = 1;
}

if ($end > $total_pages) {
    $end = $total_pages;
}

?>

<!DOCTYPE html>

<head>
    <title>Gestionar usuarios</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
        <a href="account.php"><span class="uitext">Volver</span></a>
    </header>
    <div class="user-manage">
        <div class="user-manage-line">
            <span class="user-manage-text">ID de usuario</span>
            <span class="user-manage-text">Nombre</span>
            <span class="user-manage-text">Apellido</span>
            <span class="user-manage-text">Correo</span>
            <span class="user-manage-text">Teléfono</span>
            <span class="user-manage-text">Rol</span>
            <span class="user-manage-text">Baneado</span>
            <span class="user-manage-text">Puntos Blend</span>
            <span class="user-manage-text">Creado en</span>
        </div>
        <?php
        foreach ($users as $user) {
            if ($user['baneado'] == 1) {
                $baneado = "Si";
            } else {
                $baneado = "No";
            }
            echo '<hr class="user-manage-hr">';
            echo '<div class="user-manage-line">';
            echo '<a href="user.php?id='.htmlspecialchars($user['id_usuario'], ENT_QUOTES, 'UTF-8').'" class="user-manage-text"><b>'.htmlspecialchars($user['id_usuario'], ENT_QUOTES, 'UTF-8').'</b></a>';
            echo '<span class="user-manage-text">'.htmlspecialchars($user['nombre'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="user-manage-text">'.htmlspecialchars($user['apellido'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="user-manage-text">'.htmlspecialchars($user['correo'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="user-manage-text">'.htmlspecialchars($user['telefono'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="user-manage-text">'.htmlspecialchars($user['rol'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="user-manage-text">'.$baneado.'</span>';
            echo '<span class="user-manage-text">'.htmlspecialchars($user['blendpoints'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="user-manage-text">'.htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '</div>';
        }
        ?>
        <div class="user-manage-pages">
            <?php
            for ($i = $start; $i <= $end; $i++) {
                if ($i == $page) {
                    echo '<span class="user-manage-text"><b>'.$i.'</b></span>';
                } else {
                    echo '<a href="user_manage.php?page='.$i.'">'.$i.'</a>';
                }
            }
            ?>
        </div>
    </div>
</body>