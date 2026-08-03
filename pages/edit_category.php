<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';
requireRole(["Administrador", "Dueño"], $pdo);

$id_categoria = $_POST['id_categoria'];

$stmt = $pdo->prepare("SELECT * FROM categorias WHERE id_categoria = :id_categoria LIMIT 1");
$stmt->execute([
    ":id_categoria" => $id_categoria
]);

$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<head>
    <title>Editar categoria</title>

    <link href="style.css" rel="stylesheet"> 
    <link href="../images/logo_mini.png" rel="icon" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cookie&family=Lora:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <a href="main.php"><img class ="logo" src="../images/logo.png"></a>
        <a href="categories.php"><span class="uitext">Volver</span></a>
    </header>
    <form class="direction-form" action="edit_category_process.php" enctype="multipart/form-data" method="POST">
        <div class="account-form-start">
            <span class="account-form-title">Editar categoria</span>
        </div>
        <hr class="account-hr">
        <div class="account-form-info">
            <span class="account-form-text">Nombre</span>
            <input class="account-form-input" type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>
            <span class="account-form-additional">Nombre de la categoría</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Display</span>
            <input class="account-form-input" type="text" name="display" placeholder="Display" value="<?= htmlspecialchars($categoria['display'], ENT_QUOTES, 'UTF-8') ?>" required>
            <span class="account-form-additional">Display de la categoría (el texto bajo el que se mostrará a los usuarios)</span>
        </div>
        <div class="account-form-info">
            <span class="account-form-text">Imágen</span>
            <input class="account-form-input" type="file" name="imagen" accept="image/*">
            <span class="account-form-additional">Imágen de la categoría (.jpg, .png o .webp)</span>
        </div>
        <?php echo '<input type="hidden" name="id_categoria" value="'.htmlspecialchars($id_categoria, ENT_QUOTES, 'UTF-8').'">'; ?>
        <button type="submit" class="account-button">Continuar</button>
    </form>
</body>