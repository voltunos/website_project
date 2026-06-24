<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
requireRole(["Administrador"], $pdo);

?>

<!DOCTYPE html>

<head>
    <title>Agregar estado</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
        <a href="state.php"><span class="uitext">Volver</span></a>
    </header>
    <form class="direction-form" action="add_state_process.php" enctype="multipart/form-data" method="POST">
        <div class="account-form-start">
            <span class="account-form-title">Agregar estado</span>
        </div>
        <hr class="account-hr">
        <div class="account-form-info">
            <span class="account-form-text">Nombre</span>
            <input class="account-form-input" type="text" name="nombre" placeholder="Nombre" required>
            <span class="account-form-additional">Nombre del estado (solo visible para administradores)</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Texto</span>
            <input class="account-form-input" type="text" name="texto" placeholder="Texto" required>
            <span class="account-form-additional">Texto que se mostrará en la página junto con la imágen cuando el estado esté activo</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Imágen</span>
            <input class="account-form-input" type="file" name="imagen" accept="image/*">
            <span class="account-form-additional">Imágen del estado (.jpg, .png o .webp)</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Permitir compras</span>
            <select name="compras" required>
                <option value="1">Si</option>
                <option value="0">No</option>
            </select>
            <span class="account-form-additional">Indicar si se permiten realizar compras mientras este estado esté activo</span>
        </div>
        <button type="submit" class="account-button">Continuar</button>
    </form>
</body>