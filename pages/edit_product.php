<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
requireRole(["Administrador"], $pdo);

$id_producto = $_POST['id_producto'];

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id_producto = :id_producto LIMIT 1");
$stmt->execute([
    ":id_producto" => $id_producto
]);

$producto = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt2 = $pdo->prepare("SELECT * FROM categorias WHERE activo = 1");
$stmt2->execute();

$categorias = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<head>
    <title>Editar producto</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
        <a href="products.php"><span class="uitext">Volver</span></a>
    </header>
    <form class="direction-form" action="edit_product_process.php" enctype="multipart/form-data" method="POST">
        <div class="account-form-start">
            <span class="account-form-title">Editar producto</span>
        </div>
        <hr class="account-hr">
        <div class="account-form-info">
            <span class="account-form-text">Nombre</span>
            <input class="account-form-input" type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>
            <span class="account-form-additional">Nombre del producto</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Precio</span>
            <input class="account-form-input" type="text" name="precio" placeholder="Precio" value="<?= htmlspecialchars($producto['precio'], ENT_QUOTES, 'UTF-8') ?>" required>
            <span class="account-form-additional">Precio del producto (en números. Ej: 2999.99)</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Descripción</span>
            <input class="account-form-input" type="text" name="descripcion" placeholder="Descripción" value="<?= htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8') ?>">
            <span class="account-form-additional">Descripción del producto</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Categoría</span>
            <select class="account-form-input" name="categoria" required>
                <?php
                    foreach ($categorias as $categoria) {
                        echo '<option value="' . htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8') . '" ' .
                            ($producto['categoria'] === $categoria['nombre'] ? 'selected' : '') .
                            '>' . htmlspecialchars($categoria['display'], ENT_QUOTES, 'UTF-8') .
                            '</option>';
                    }
                ?>
            </select>
            <span class="account-form-additional">Categoría del producto</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Imágen</span>
            <input class="account-form-input" type="file" name="imagen" accept="image/*">
            <span class="account-form-additional">Imágen del producto (.jpg, .png o .webp)</span>
        </div>
        <?php echo '<input type="hidden" name="id_producto" value="'.htmlspecialchars($id_producto, ENT_QUOTES, 'UTF-8').'">'; ?>
        <button type="submit" class="account-button">Continuar</button>
    </form>
</body>