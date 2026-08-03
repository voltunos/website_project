<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
requireRole(["Administrador", "Dueño"], $pdo);

require_once '../services/image_service.php';

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

$stmt = $pdo->prepare("SELECT * FROM estado");
$stmt->execute();

$estados = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<head>
    <title>Estado</title>

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
    <span class="edit-title">Estado</span>
     <?php
    if ($error) {
        echo '<span class="edit-error"><b>Error: </b>'.$error.'</span>';
    }
    if ($success) {
        echo '<span class="edit-success">'.$success.'</span>';
    }
    ?>
    <div class="space">
        <div class="category-edit">
            <span class="product-edit-maininfo">Imágen</span>
            <span class="product-edit-maininfo">Nombre</span>
            <span class="product-edit-maininfo">Texto</span>
            <span class="product-edit-maininfo">Permitir compras</span>
            <span class="product-edit-maininfo">Opciones</span>
        </div>
        <hr class="account2-hr">
        <?php
        foreach($estados as $estado) {
            $imagen = ImageService::getImage($estado['imagen'], "../uploads/states/");
            if ($estado['activo'] == 1) {
                echo '<div class="state-edit-active">';
            } else {
                echo '<div class="state-edit">';
            }
            echo '<figure class="state-figure">';
            echo '<img src="'.$imagen.'" class="category-edit-image">';
            echo '</figure>';
            echo '<span class="category-edit-info">'.$estado['nombre'].'</span>';
            echo '<span class="category-edit-info">'.$estado['texto'].'</span>';
            if ($estado['compras'] == 1) {
                echo '<span class="category-edit-info">Si</span>';
            } else {
                echo '<span class="category-edit-info">No</span>';
            }
            echo '<div class="product-edit-buttons">';
            echo '<form action="edit_state.php" method="POST">';
            echo '<input type="hidden" name="id_estado" value="'.$estado['id_estado'].'">';
            echo '<button class="product-edit-editbutton" type="submit">Editar estado</button>';
            echo '</form>';
            if ($estado['activo'] == 0) {
                echo '<form action="activate_state.php" method="POST">';
                echo '<input type="hidden" name="id_estado" value="'.$estado['id_estado'].'">';
                echo '<button class="product-edit-activatebutton" type="submit">Activar estado</button>';
                echo '</form>';
            }
            echo '</div>';
            echo '</div>';
            echo '<hr class="account2-hr">';
        }
        ?>
        <div class="product-edit-more">
            <a href="add_state.php"><img src="../images/add.png" class="icon"><span class="product-edit-additional">Agregar estado</span></a>
        </div>
    </div>
</body>