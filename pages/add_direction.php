<?php

session_start();
require_once 'database.php';
require_once 'auth.php';

?>

<!DOCTYPE html>

<head>
    <title>Agregar dirección</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
        <a href="directions.php"><span class="uitext">Volver</span></a>
    </header>
    <form class="direction-form" action="add_direction_process.php" method="POST">
        <div class="account-form-start">
            <span class="account-form-title">Agregar dirección</span>
        </div>
        <hr class="account-hr">
        <div class="account-form-info">
            <span class="account-form-text">Calle</span>
            <input class="account-form-input" type="text" name="calle" placeholder="Calle" required>
            <span class="account-form-additional">Nombre de la calle</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Número</span>
            <input class="account-form-input" type="text" name="numero" placeholder="Número" required>
            <span class="account-form-additional">Número de casa</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Información adicional</span>
            <input class="account-form-input" type="text" name="adicional" placeholder="Información adicional">
            <span class="account-form-additional">Información adicional o indicaciones sobre la dirección</span>
        </div>
        <button type="submit" class="account-button">Continuar</button>
    </form>
</body>