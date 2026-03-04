<?php

require_once 'no_auth.php';

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

?>

<!DOCTYPE html>

<head>
    <title>Registrarse</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header class="sidebar">
        <a href="main.php"><img class ="logo-account" src="../images/logo.png"></a>
    </header>
    <form class="account-form" action="register_process.php" method="POST">
        <div class="account-form-start">
            <span class="account-form-title">Registrarse</span>
            <img class="logo-big" src="../images/logo_big.png">
        </div>
        <hr class="account-hr">
        <div class="account-form-info">
            <span class="account-form-text">Correo electrónico</span>
            <input class="account-form-input" type="text" name="correo" placeholder="Correo electrónico" required>
            <span class="account-form-additional">Dirección de correo válida</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Nombre</span>
            <input class="account-form-input" type="text" name="nombre" placeholder="Nombre" required>
            <span class="account-form-additional">Nombre completo</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Apellido</span>
            <input class="account-form-input" type="text" name="apellido" placeholder="Apellido" required>
            <span class="account-form-additional">Apellido completo</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Teléfono</span>
            <input class="account-form-input" type="number" maxlength="10" name="telefono" placeholder="Teléfono" required>
            <span class="account-form-additional">En caso de que necesitemos contactarte</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Contraseña</span>
            <input class="account-form-input" type="password" name="contra" placeholder="Contraseña" required>
            <span class="account-form-additional">Contraseña con 8 dígitos o más</span>
        </div>
        <?php
        if ($error) {
            echo '<span class="text-error"><b>Error: </b>'.$error.'</span>';
        }
        if ($success) {
            echo '<span class="text-success">'.$success.'</span>';
        }
        ?>
        <button type="submit" class="account-button">Continuar</button>
        <div class="account-buttons">
            <a href="login.php" class="account-button">Iniciar sesión</a>
            <a href="help.php" class="account-button">Ayuda</a>
        </div>
    </div>
</body>