<?php

session_start();
require_once 'database.php';
require_once 'auth.php';
require_once '../services/image_service.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id_usuario = :id_usuario LIMIT 1");
$stmt->execute([
    ":id_usuario" => $_SESSION['id_usuario']
]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$usuario) {
    header("Location: main.php");
    exit();
}
?>

<!DOCTYPE html>

<head>
    <title>Mi perfil</title>

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
    <div class="myprofile">
        <div class="myprofile-tabs">
            <div class="myprofile-tab" onclick="showTab('profile')">
                <img class="icon-small" src="../images/user_white.png">
                <span id="profile-text" class="myprofile-text-bold">Mi perfil</span>
            </div>
            <div class="myprofile-tab" onclick="showTab('security')">
                <img class="icon-small" src="../images/security.png">
                <span id="security-text" class="myprofile-text">Seguridad</span>
            </div>
        </div>
        <hr class="myprofile-hr">
        <div id="profile-tab" class="myprofile-info">
            <form method="POST" action="myprofile_change.php" id="myprofile" enctype="multipart/form-data">
                <input type="hidden" name="action" value="profile_change">
            </form>
            <?php $imagen =  ImageService::getImage($usuario['imagen'], "../uploads/users/") ?>
            <span class="myprofile-text">Foto de perfil</span>
            <figure class="myprofile-figure">
                <img src="<?php echo $imagen; ?>" class="myprofile-image">
            </figure>
            <div class="myprofile-section">
                <input form="myprofile" class="myprofile-image-input" type="file" name="imagen" accept="image/*">
                <span class="myprofile-additional">Solo se permiten imágenes de menos de 2MB de tamaño y de extensión .jpg, .png y .webp</span>
            </div>
            <div class="myprofile-section">
                <span class="myprofile-text">Nombre</span>
                <input form="myprofile" class="myprofile-input" type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nombre" required>
                <span class="myprofile-additional">Nombre del usuario</span>
            </div>
            <div class="myprofile-section">
                <span class="myprofile-text">Apellido</span>
                <input form="myprofile" class="myprofile-input" type="text" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Apellido" required>
                <span class="myprofile-additional">Apellido del usuario</span>
            </div>
            <div class="myprofile-section">
                <span class="myprofile-text">Teléfono</span>
                <input form="myprofile" class="myprofile-input" type="text" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Teléfono" required>
                <span class="myprofile-additional">Teléfono a utilizar en caso de necesitar contactarlo/a</span>
            </div>
            <button form="myprofile" class="myprofile-button" type="submit">Realizar cambios</button>
        </div>
        <div id="security-tab" class="myprofile-info-hidden">
            <form method="POST" action="myprofile_change.php" id="password">
                <input type="hidden" name="action" value="password">
            </form>
            <div class="myprofile-section">
                <span class="myprofile-text">Correo electrónico</span>
                <span class="myprofile-additional"><?php echo htmlspecialchars($usuario['correo'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="myprofile-section">
                <span class="myprofile-text">Cambiar contraseña</span>
                <input form="password" class="myprofile-input" type="text" name="passnow" placeholder="Contraseña actual" required>
                <span class="myprofile-additional">Contraseña actual de la cuenta</span>
                <input form="password" class="myprofile-input" type="text" name="pass1" placeholder="Nueva contraseña" required>
                <span class="myprofile-additional">Nueva contraseña (debe tener 8 dígitos o más)</span>
                <input form="password" class="myprofile-input" type="text" name="pass2" placeholder="Repetir nueva contraseña" required>
                <span class="myprofile-additional">Repetir nueva contraseña</span>
            </div>
            <button form="password" class="myprofile-button" type="submit">Cambiar contraseña</button>
        </div>
    </div>

    <script>
        function showTab(tab) {
            document.getElementById("profile-tab").className = "myprofile-info-hidden";
            document.getElementById("security-tab").className = "myprofile-info-hidden";
            document.getElementById("profile-text").className = "myprofile-text";
            document.getElementById("security-text").className = "myprofile-text";

            document.getElementById(tab + "-tab").className = "myprofile-info";
            document.getElementById(tab + "-text").className = "myprofile-text-bold";
        }
    </script>
</body>