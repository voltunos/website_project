<!DOCTYPE html>

<?php

require_once 'database.php';

?>

<head>
    <title>Iniciar sesión</title>

    <link href="style.css" rel="stylesheet"> 

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <div class="logo-and-more">
            <a href=""><img class="icon" src="../images/menu.png"></a>
            <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
        </div>
        <a href="login.php"><img class="icon" src="../images/user.png"><span class="uitext">Iniciar sesión</span></a>
    </header>
    <div class="account-form">
        <span class="account-form-title">Iniciar sesión</span>
        <hr class="account-hr">
        <div class="account-form-info">
            <span class="account-form-text">Correo electrónico o DNI</span>
        </div>
    </div>
</body>