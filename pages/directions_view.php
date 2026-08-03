<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
requireRole(["Administrador", "Dueño"], $pdo);

$id = $_GET['id'] ?? null;

if (empty($id)) {
    header("Location: main.php");
}

$stmt = $pdo->prepare("SELECT * FROM direcciones WHERE id_usuario = :id_usuario");
$stmt->execute([
    ":id_usuario" => $id
]);

$directions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $pdo->prepare("SELECT nombre, apellido FROM users WHERE id_usuario = :id_usuario");
$stmt2->execute([
    ":id_usuario" => $id
]);

$user = $stmt2->fetch();

?>

<head>
    <title>Direcciones de usuario</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
        <a href="user.php?id=<?php echo $id; ?>"><span class="uitext">Volver</span></a>
    </header>
    <span class="edit-title">Direcciones de <?php echo htmlspecialchars($user['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
    <div class="directions-view">
        <div class="direction">
            <span class="directions-main-text">ID</span>
            <span class="directions-main-text">Calle</span>
            <span class="directions-main-text">Número</span>
            <span class="directions-main-text">Adicional</span>
            <span class="directions-main-text">Activo</span>
        </div>
        <?php
        foreach ($directions as $direction) {
            if ($direction['activo'] == 1) {
                $active = "Si";
            } else {
                $active = "No";
            }
            echo '<hr class="direction-hr">';
            echo '<div class="direction">';
            echo '<span class="directions-view-text">'.htmlspecialchars($direction['id_direccion'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="directions-view-text">'.htmlspecialchars($direction['calle'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="directions-view-text">'.htmlspecialchars($direction['numero'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="directions-view-description">'.htmlspecialchars($direction['adicional'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="directions-view-text">'.$active.'</span>';
            echo '</div>';
        }
        ?>
    </div>
</body>