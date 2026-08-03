<?php

session_start();
require_once 'database.php';
require_once '../services/image_service.php';

$id = $_GET['id'] ?? null;

if (isset($_SESSION['id_usuario'])) {
    $stmt3 = $pdo->prepare("SELECT rol FROM users WHERE id_usuario = :id_usuario");
    $stmt3->execute([
        ":id_usuario" => $_SESSION['id_usuario']
    ]);

    $rol = $stmt3->fetchColumn();
}

if (empty($id)) {
    header("Location: main.php");
}

if (isset($_SESSION['id_usuario']) && ($rol === "Administrador" || $rol === "Dueño")) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_usuario = :id_usuario LIMIT 1");
} else {
    $stmt = $pdo->prepare("SELECT nombre, apellido, rol, imagen FROM users WHERE id_usuario = :id_usuario LIMIT 1");
}
$stmt->execute([
    ":id_usuario" => $id
]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: main.php");
    exit();
}

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

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

?>

<!DOCTYPE html>

<head>
    <title>Perfil de usuario</title>

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
    <div class="profile">
        <figure class="profile-figure">
            <?php
            $usuario_imagen = ImageService::getImage($usuario['imagen'], "../uploads/users/");
            echo '<img src="'.$usuario_imagen.'" class="profile-image">';
            ?>
        </figure>
        <div class="profile-info">
            <?php
            $user_role = $usuario['rol'];
            $name = htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8');
            $secondName = htmlspecialchars($usuario['apellido'], ENT_QUOTES, 'UTF-8');
            echo '<span class="profile-name">'.$name.' '.$secondName.'</span>';
            echo '<span class="user-'.$user_role.'">'.$user_role.'</span>';
            ?>
        </div>
    </div>
    <?php
    if (isset($_SESSION['id_usuario']) && ($rol === "Administrador" || $rol === "Dueño")) {
        echo '<div class="profile-admin">';
        echo '<span class="profile-admin-title">Panel de administrador</span>';
        echo '<hr class="profile-admin-hr">';
        echo '<div class="profile-admin-info">';
        echo '<div class="profile-admin-option"><img src="../images/email.png" class="icon"><span class="profile-admin-text">Correo: '.$usuario['correo'].'</span></div>';
        echo '<div class="profile-admin-option"><img src="../images/smartphone.png" class="icon"><span class="profile-admin-text">Teléfono: '.$usuario['telefono'].'</span></div>';
        echo '<div class="profile-admin-option"><img src="../images/created_at.png" class="icon"><span class="profile-admin-text">Creado en: '.$usuario['created_at'].'</span></div>';
        echo '<div class="profile-admin-option"><img src="../images/blendpoints_white.png" class="icon"><span class="profile-admin-text">Puntos Blend: '.$usuario['blendpoints'].'</span></div>';
        echo '</div>';
        if ($error) {
            echo '<span class="edit-error">'.$error.'</span>';
        }
        if ($success) {
            echo '<span class="edit-success">'.$success.'</span>';
        }
        echo '<div class="profile-admin-buttons">';
        if ($usuario['baneado'] == 0) {
            echo '<form id="ban" method="POST" action="user_modify.php">';
            echo '<input type="hidden" name="option" value="ban">';
            echo '<input type="hidden" name="id" value="'.$usuario['id_usuario'].'">';
            echo '</form>';
            echo '<button onclick="confirmAction(event, \'¿Banear al usuario <b>'.$name.' '.$secondName.'</b>?\')" class="profile-admin-button" style="background-color:darkred" type="button" form="ban">Banear usuario</button>';
        } else {
            echo '<form id="unban" method="POST" action="user_modify.php">';
            echo '<input type="hidden" name="option" value="unban">';
            echo '</form>';
            echo '<button onclick="confirmAction(event, \'¿Desbanear al usuario <b>'.$name.' '.$secondName.'</b>?\')" class="profile-admin-button" style="background-color:forestgreen" type="button" form="unban">Desbanear usuario</button>';
        }
        echo '<a href="directions_view.php?id='.$usuario['id_usuario'].'" class="profile-admin-button" style="background-color:dodgerblue">Ver direcciones</a>';
        $roleText = [
            "client" => "Dar rol de cliente",
            "delivery" => "Dar rol de delivery",
            "admin" => "Dar rol de administrador"
        ];
        $roleBgColors = [
            "client" => "dimgray",
            "delivery" => "goldenrod",
            "admin" => "red"
        ];
        $actionsByRole = [
            "Cliente" => ["delivery", "admin"],
            "Delivery" => ["client", "admin"],
            "Administrador" => ["client", "delivery"]
        ];

        $userRole = $usuario['rol'];
        $availableActions = $actionsByRole[$userRole] ?? [];
        foreach($availableActions as $action) {
            echo '<form id="'.$action.'" action="user_modify.php" method="POST">';
            echo '<input type="hidden" name="option" value="role">';
            echo '<input type="hidden" name="id" value="'.$usuario['id_usuario'].'">';
            echo '<input type="hidden" name="additional" value="'.$action.'">';
            $text = $roleText[$action];
            $bg = $roleBgColors[$action];
            echo '</form>';
                
            echo '<button onclick="confirmAction(event, \'¿Dar el rol de '.$action.' al usuario <b>'.$name.' '.$secondName.'</b>?\')" class="profile-admin-button" style="background-color:'.$bg.'" type="button" form="'.$action.'">'.$text.'</button>';
        }
        echo '</div>';
        echo '<div class="profile-admin-buttons">';
        echo '<form id="blendpoints" action="user_modify.php" method="POST">';
        echo '<input type="hidden" name="option" value="blendpoints">';
        echo '<input type="hidden" name="id" value="'.$usuario['id_usuario'].'">';
        echo '</form>';
        echo '<button onclick="confirmBlendPoints()" class="profile-admin-button" style="background: linear-gradient(90deg, rgba(255, 167, 25, 1) 0%, rgba(255, 225, 0, 1) 100%); font-weight:bold; color:black;" type="button" form="blendpoints">Dar Puntos Blend</button>';
        echo '<a href="orders_view.php?id='.$usuario['id_usuario'].'" class="profile-admin-button" style="background-color: navy;">Ver ordenes</a>';
        echo '</div>';
        echo '</div>';
    }
    ?>
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="modal-title">Confirmar acción</span>
            <hr class="modal-hr">
            <span class="modal-text" id="modalText"></span>
            <div class="modal-buttons">
                <button class="modal-button" style="background-color: forestgreen" id="confirm">Confirmar</button>
                <button class="modal-button" style="background-color: darkred" onClick="closeModal()">Cancelar</button>
            </div>
        </div>
    </div>
    <div id="blendPointsModal" class="modal">
        <div class="modal-content">
            <span class="modal-title">Dar Puntos Blend</span>
            <hr class="modal-hr">
            <span class="modal-text">Indique la cantidad de Puntos Blend a otorgar</span>
            <input type="number" class="modal-input" form="blendpoints" name="additional">
            <div class="modal-buttons">
                <button class="modal-button" style="background-color: forestgreen" form="blendpoints" type="submit">Confirmar</button>
                <button class="modal-button" style="background-color: darkred" onClick="closeModal()">Cancelar</button>
            </div>
        </div>
        </div>
    <script>
        let currentForm = null;

        function confirmAction(event, text) {
            event.preventDefault();

            currentForm = document.getElementById(event.currentTarget.getAttribute('form'));

            document.getElementById('modalText').innerHTML = text;

            document.getElementById('confirmModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
            document.getElementById('blendPointsModal').style.display = 'none';
        }

        document.getElementById('confirm').addEventListener('click', () => {
            if (currentForm) {
                currentForm.submit();
            }
        });

        function confirmBlendPoints() {
            document.getElementById('blendPointsModal').style.display = 'flex';
        }
    </script>
</body>