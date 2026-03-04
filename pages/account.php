<?php

session_start();
require_once 'database.php';
require_once 'auth.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id_usuario = :id LIMIT 1");
$stmt->execute([
    ":id" => $_SESSION['id_usuario']
]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<head>
    <title>Cuenta</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
    </header>
    <div class="space" style="gap: 0.5vh">
        <div class="account2-maininfo">
            <span class="account2-title"><?php echo $usuario['nombre'].' '; echo $usuario['apellido']; ?></span>
            <div><span class="account2-title"><?php echo $usuario['blendpoints']; ?> Puntos Blend </span><img src="../images/blendpoints_white.png" class="icon-big"></div>
        </div>
        <hr class="account2-main-hr">
        <span class="account2-subtitle">General</span>
        <div class="account2-info">
            <a href="profile.php"><img src="../images/profile.png" class="icon"><span class="account2-text">Mi perfil</span></a>
        </div>
        <div class="account2-info">
            <a href="directions.php"><img src="../images/direction.png" class="icon"><span class="account2-text">Direcciones</span></a>
        </div>
        <div class="account2-info">
            <a href="myorders.php"><img src="../images/myorders.png" class="icon"><span class="account2-text">Mis pedidos</span></a>
        </div>
        <div class="account2-info">
            <a href="blendpoints.php"><img src="../images/blendpoints_white.png" class="icon"><span class="account2-text">Puntos Blend</span></a>
        </div>
        <span class="account2-subtitle">Soporte</span>
        <div class="account2-info">
            <a href="faq.php"><img src="../images/faq.png" class="icon"><span class="account2-text">Preguntas frecuentes</span></a>
        </div>
        <div class="account2-info">
            <a href="terms.php"><img src="../images/terms.png" class="icon"><span class="account2-text">Términos y condiciones</span></a>
        </div>
        <div class="account2-info">
            <a href="logout.php"><img src="../images/logout.png" class="icon"><span class="account2-text">Cerrar sesión</span></a>
        </div>
        <?php
        if ($usuario['rol'] == "Administrador") {
            echo '<span class="account2-subtitle">Opciones de administrador</span>';
            echo '<div class="account2-info">';
            echo '<a href="order_record.php"><img src="../images/order_record.png" class="icon"><span class="account2-text">Historial de pedidos</span></a>';
            echo '</div>';
            echo '<div class="account2-info">';
            echo '<a href="products.php"><img src="../images/products.png" class="icon"><span class="account2-text">Productos</span></a>';
            echo '</div>';
            echo '<div class="account2-info">';
            echo '<a href="user_manage.php"><img src="../images/user_manage.png" class="icon"><span class="account2-text">Gestionar usuarios</span></a>';
            echo '</div>';
            echo '<div class="account2-info">';
            echo '<a href="refunds.php"><img src="../images/refunds.png" class="icon"><span class="account2-text">Devoluciones y reembolsos</span></a>';
            echo '</div>';
            echo '<div class="account2-info">';
            echo '<a href="state.php"><img src="../images/state.png" class="icon"><span class="account2-text">Establecer estado de la página</span></a>';
            echo '</div>';
        }
        ?>
    </div>
</body>