<?php

session_start();
require_once 'database.php';
require_once '../services/image_service.php';

$categoriaSeleccionada = $_GET['category'] ?? null;

if ($categoriaSeleccionada) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE activo = :activo AND categoria = :categoria");
    $stmt ->execute([
    ":activo" => 1,
    ":categoria" => $categoriaSeleccionada
    ]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE activo = 1");
    $stmt ->execute();
}

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user_id = $_SESSION['id_usuario'] ?? "";
if ($user_id) {
    $stmt2 = $pdo->prepare("SELECT blendpoints FROM users WHERE id_usuario = :id_usuario");
    $stmt2->execute([
        ":id_usuario" => $user_id
    ]);

    $blendpoints = $stmt2->fetchColumn();
    $blendpoints = $blendpoints !== false ? $blendpoints : 0;
}

$stmt3 = $pdo->prepare("SELECT * FROM categorias WHERE activo = 1");
$stmt3 -> execute();

$categorias = $stmt3->fetchAll(PDO::FETCH_ASSOC);

$stmt4 = $pdo->prepare("SELECT * FROM estado WHERE activo = 1 LIMIT 1");
$stmt4 -> execute();

$estado = $stmt4->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<head>
    <title>Blendburger</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <div class="header-right">
            <div class="logo-and-more">
                <a href=""><img class="bars" src="../images/menu.png"></a>
                <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
            </div>
            <?php
            if ($user_id) {
                echo '<a href="cart.php"><img class="icon-big" src="../images/cart.png"></a>';
            }
            ?>
        </div>
        <div class="state">
            <?php
            $estado_imagen = ImageService::getImage($estado['imagen'], "../uploads/states/");
            echo '<img src="'.$estado_imagen.'" class="icon"><span class="uitext">'.$estado['texto'].'</span>';
            ?>
        </div>
        <?php
        if ($user_id) {
            echo '<div class="header-account-info">';
            echo '<img src="../images/notification.png" class="icon-big">';
            echo '<a href="blendpoints.php"><img class="icon-big" src="../images/blendpoints.png"><span class="uitext">'.$blendpoints.'</span></a>';
            echo '<a href="account.php"><img class="icon-big" src="../images/user.png"><span class="uitext hide-mobile">Cuenta</span></a>';
            echo '</div>';
        } else {
            echo '<div class="header-account-info">';
            echo '<div></div>';
            echo '<a href="login.php"><img class="icon-big" src="../images/login.png"><span class="uitext">Iniciar sesión</span></a>';
            echo '</div>';
        }
        ?>
    </header>
    <div class="product-types">
        <div class="type">
            <a href="main.php" class="type">
                <img src="../images/all.png" class="type-image">
                <span class="type-text">Mostrar todo</span>
            </a>
        </div>
        <?php
        foreach ($categorias as $categoria) {
            $imagen = ImageService::getImage($categoria['imagen'] ,"../uploads/categories/");
            echo '<div class="type">';
            echo '<a href="main.php?category='.$categoria['nombre'].'" class="type">';
            echo '<img src="'.$imagen.'" class="type-image">';
            echo '<span class="type-text">'.$categoria['display'].'</span>';
            echo '</a>';
            echo '</div>';
        }
        ?>
    </div>
    <div class="products">
        <?php foreach ($productos as $producto) {
            $imagen = ImageService::getImage($producto['imagen'], "../uploads/products/");
            echo '<form class="product" action="cart_process.php" method="POST">';
            echo '<a href="product.php?id='.$producto['id_producto'].'">';
            echo '<figure>';
            echo '<img src="'.$imagen.'" class="product-image">';
            echo '</figure>';
            echo '</a>';
            echo '<div class="product-info">';
            echo '<span class="product-title">'.$producto['nombre'].'</span>';
            echo '<span class="product-price">$'.$producto['precio'].'</span>';
            echo '<span class="product-description">'.$producto['descripcion'].'</span>';
            echo '<input type="hidden" name="id_producto" value="'.$producto['id_producto'].'">';
            echo '<input type="hidden" name="action" value="add">';
            echo '<input type="hidden" name="redirect" value="main">';
            echo '</div>';
            echo '<button type="submit" class="product-button">Agregar</button>';
            echo '</form>';
        }
        ?>
    </div>
</body>