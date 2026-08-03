<?php

session_start();
require_once 'database.php';
require_once 'auth.php';

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

$stmt = $pdo->prepare("SELECT * FROM direcciones WHERE id_usuario = :id_usuario AND activo = 1");
$stmt->execute([
    ":id_usuario" => $_SESSION['id_usuario']
]);

$direcciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>

<head>
    <title>Direcciones</title>

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
    <body>
        <span class="edit-title">Mis direcciones</span>
        <?php
        if ($error) {
            echo '<span class="edit-error"><b>Error: </b>'.$error.'</span>';
        }
        if ($success) {
            echo '<span class="edit-success">'.$success.'</span>';
        }
        ?>
        <div class="directions">
            <div class="direction">
                <span class="directions-main-text">Calle</span>
                <span class="directions-main-text">Número</span>
                <span class="directions-main-text">Información adicional</span>
                <span class="directions-main-text">Opciones</span>
            </div>
            <hr class="direction-hr">
            <?php
            foreach ($direcciones as $direccion) {
                echo '<div class="direction">';
                echo '<span class="directions-text">'.htmlspecialchars($direccion['calle'], ENT_QUOTES, 'UTF-8').'</span>';
                echo '<span class="directions-text">'.htmlspecialchars($direccion['numero'], ENT_QUOTES, 'UTF-8').'</span>';
                echo '<span class="directions-description">'.htmlspecialchars($direccion['adicional'], ENT_QUOTES, 'UTF-8').'</span>';
                echo '<div class="direction-buttons">';
                echo '<form action="edit_direction.php" method="POST">';
                echo '<input type="hidden" name="id_direccion" value="'.htmlspecialchars($direccion['id_direccion'], ENT_QUOTES, 'UTF-8').'">';
                echo '<button type="submit" class="direction-edit">Editar dirección</button>';
                echo '</form>';
                echo '<form action="elim_direction_process.php" method="POST">';
                echo '<input type="hidden" name="id_direccion" value="'.htmlspecialchars($direccion['id_direccion'], ENT_QUOTES, 'UTF-8').'">';
                echo '<button type="submit" class="direction-elim">Eliminar dirección</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '<hr class="direction-hr">';
            }
            ?>
            <div class="direction">
                <a href="add_direction.php"><img src="../images/add.png" class="icon"><span class="directions-main-text">Agregar dirección</span></a>
            </div>
        </div>
    </body>
</body>