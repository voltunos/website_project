<?php

session_start();
require_once 'database.php';
require_once '../services/image_service.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id_producto = :id_producto LIMIT 1");
    $stmt ->execute([
    ":id_producto" => $id
    ]);
} else {
    header("Location: main.php");
}

$producto = $stmt->fetch(PDO::FETCH_ASSOC);

$user_id = $_SESSION['id_usuario'] ?? "";
if ($user_id) {
    $stmt2 = $pdo->prepare("SELECT blendpoints FROM users WHERE id_usuario = :id_usuario");
    $stmt2->execute([
        ":id_usuario" => $user_id
    ]);

    $blendpoints = $stmt2->fetchColumn();
    $blendpoints = $blendpoints !== false ? $blendpoints : 0;
}

$stmt4 = $pdo->prepare("SELECT * FROM estado WHERE activo = 1 LIMIT 1");
$stmt4 -> execute();

$estado = $stmt4->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<head>
    <title><?php echo $producto['nombre']; ?></title>

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
            echo '<a href="account.php"><img class="icon-big" src="../images/user.png"><span class="uitext">Cuenta</span></a>';
            echo '</div>';
        } else {
            echo '<div class="header-account-info">';
            echo '<div></div>';
            echo '<a href="login.php"><img class="icon-big" src="../images/login.png"><span class="uitext">Iniciar sesión</span></a>';
            echo '</div>';
        }
        ?>
    </header>
    <div class="product-viewer">
        <figure class="viewer-figure">
            <?php
            $imagen = ImageService::getImage($producto['imagen'], "../uploads/products/");
            echo '<img src="'.$imagen.'" class="viewer-image">';
            ?>
        </figure>
        <div class="viewer-info">
            <?php
            echo '<span class="viewer-title">'.htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="viewer-price">$'.htmlspecialchars($producto['precio'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<span class="viewer-description">'.htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8').'</span>';
            echo '<form id="add" action="cart_process.php" method="POST">';
            echo '<input type="hidden" name="id_producto" value="'.$producto['id_producto'].'">';
            echo '<input type="hidden" name="action" value="add">';
            echo '<input type="hidden" name="redirect" value="main">';
            echo '</form>';

            $message = "Agregar al carrito";
            $extra = "";
            if ($producto['activo'] == 0) {
                $message = "Este producto no está disponible";
                $extra = "disabled";
            } else if ($estado['compras'] == 0) {
                $message = "Las compras están desactivadas";
                $extra = "disabled";
            }
            echo '<button class="viewer-button" type="submit" '.$extra.' form="add">'.$message.'</button>'
            ?>
        </div>
    </div>
</body>