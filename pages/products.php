<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
requireRole(["Administrador", "Dueño"], $pdo);

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

$stmt = $pdo->prepare("SELECT * FROM productos WHERE activo = 1");
$stmt->execute();

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<head>
    <title>Productos</title>

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
    <span class="edit-title">Productos</span>
     <?php
    if ($error) {
        echo '<span class="edit-error"><b>Error: </b>'.$error.'</span>';
    }
    if ($success) {
        echo '<span class="edit-success">'.$success.'</span>';
    }
    ?>
    <div class="reduced-space">
        <div class="product-edit">
            <span class="product-edit-maininfo">Imágen</span>
            <span class="product-edit-maininfo">Nombre</span>
            <span class="product-edit-maininfo">Precio</span>
            <span class="product-edit-maininfo">Descripción</span>
            <span class="product-edit-maininfo">Categoria</span>
            <span class="product-edit-maininfo">Opciones</span>
        </div>
        <hr class="account2-hr">
        <?php
        foreach($productos as $producto) {
            $imagen = "../uploads/products/".$producto['imagen'];
            echo '<div class="product-edit">';
            echo '<figure class="product-edit-figure">';
            echo '<img src="'.$imagen.'" class="product-edit-image">';
            echo '</figure>';
            echo '<span class="product-edit-info">'.$producto['nombre'].'</span>';
            echo '<span class="product-edit-info">$'.$producto['precio'].'</span>';
            echo '<span class="product-edit-description">'.$producto['descripcion'].'</span>';
            echo '<span class="product-edit-info">'.$producto['categoria'].'</span>';
            echo '<div class="product-edit-buttons">';
            echo '<form action="edit_product.php" method="POST">';
            echo '<input type="hidden" name="id_producto" value="'.$producto['id_producto'].'">';
            echo '<button class="product-edit-editbutton" type="submit">Editar producto</button>';
            echo '</form>';
            echo '<form action="deactivate_product.php" method="POST">';
            echo '<input type="hidden" name="id_producto" value="'.$producto['id_producto'].'">';
            echo '<button class="product-edit-deletebutton" type="submit">Desactivar producto</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '<hr class="account2-hr">';
        }
        ?>
        <div class="product-edit-more">
            <a href="add_product.php"><img src="../images/add.png" class="icon"><span class="product-edit-additional">Agregar producto</span></a>
            <a href="deactivated_products.php"><span class="product-edit-additional">Productos desactivados</span><img src="../images/deactivated.png" class="icon"></a>
        </div>
    </div>
</body>