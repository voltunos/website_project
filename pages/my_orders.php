<?php

session_start();
require_once 'database.php';
require_once 'auth.php';
require_once '../services/image_service.php';

$estado = $_GET['estado'] ?? '';
$orden = $_GET['orden'] ?? 'asc';

$query = "SELECT * FROM pedido WHERE id_usuario = :id_usuario";
$params = [];

$params[':id_usuario'] = $_SESSION['id_usuario'];
if (!empty($estado)) {
    $query .= " AND estado = :estado";
    $params[':estado'] = $estado;
}

$orderDirection = ($orden === 'asc') ? 'ASC' : "DESC";
$query .= " ORDER BY fecha $orderDirection";

$stmt = $pdo->prepare($query);
$stmt->execute($params);

$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<head>
    <title>Mis pedidos</title>

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
    <span class="edit-title">Mis pedidos</span>
    <div class="order-body">
        <div class="orders">
            <?php
            $states = [
                "En proceso" => "processing",
                "Confirmado" => "confirmed",
                "Enviando" => "delivering",
                "Completado" => "completed",
                "Cancelado" => "cancelled",
                "Esperando reembolso" => "refund"
            ];
            $stateBgColors = [
                "processing" => "goldenrod",
                "confirmed" => "darkblue",
                "delivering" => "dodgerblue",
                "completed" => "darkgreen",
                "cancelled" => "darkred",
                "refund" => "dimgray"
            ];

            foreach($pedidos as $pedido) {
                $pedidoState = $pedido['estado'];
                $state = $states[$pedidoState] ?? 'unknown';
                $id = $pedido['id_pedido'];

                // Get address
                $stmt4 = $pdo->prepare("SELECT calle, numero FROM direcciones WHERE id_direccion = :id_direccion AND id_usuario = :id_usuario LIMIT 1");
                $stmt4->execute([
                    ":id_direccion" => $pedido['direccion'],
                    ":id_usuario" => $pedido['id_usuario']
                ]);
                $direccion = $stmt4->fetch(PDO::FETCH_ASSOC);

                // Get items
                $stmt2 = $pdo->prepare("SELECT * FROM pedidos_items WHERE id_pedido = :id_pedido");
                $stmt2->execute([
                    ":id_pedido" => $id
                ]);
                $items = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                echo '<div class="order">';
                echo '<div class="order-main-bg" style="background-color: '.$stateBgColors[$state].'">';
                echo '<div class="order-main">';
                echo '<span class="order-text"><b>ID pedido:</b> '.$id.'</span>';
                echo '<span class="order-text"><b>Fecha:</b> '.$pedido['fecha'].'</span>';
                echo '<span class="order-text"><b>Total:</b> $'.$pedido['total'].'</span>';
                echo '<span class="order-text"><b>Método de pago:</b> '.$pedido['metodo_pago'].'</span>';
                echo '<span class="order-text"><b>Estado:</b> '.$pedido['estado'].'</span>';
                echo '<span class="order-text"><b>Enviar a:</b> '.htmlspecialchars($direccion['calle'], ENT_QUOTES, 'UTF-8').' '.htmlspecialchars($direccion['numero'], ENT_QUOTES, 'UTF-8').'</span>';
                echo '<span class="order-text"><b>Adicional:</b> '.$pedido['adicional'].'</span>';
                echo '</div>';
                echo '<hr class="order-main-hr">';
                echo '</div>';
                echo '<div class="order-items">';
                echo '<div class="order-item">';
                echo '<span class="order-item-add">Imágen</span>';
                echo '<span class="order-item-add">Nombre del producto</span>';
                echo '<span class="order-item-add">Categoría</span>';
                echo '<span class="order-item-add">Cantidad</span>';
                echo '<span class="order-item-add">Precio unitario</span>';
                echo '<span class="order-item-add">Subtotal</span>';
                echo '</div>';
                foreach($items as $item) {
                    // Get items additional info (name, category)
                    $stmt3 = $pdo->prepare("SELECT * FROM productos WHERE id_producto = :id_producto");
                    $stmt3->execute([
                        ":id_producto" => $item['id_producto']
                    ]);
                    $itemInfo = $stmt3->fetch(PDO::FETCH_ASSOC);
                    $itemImage = ImageService::getImage($itemInfo['imagen'], "../uploads/products/");

                    echo '<div class="order-item">';
                    echo '<figure class="order-figure">';
                    echo '<img src="'.$itemImage.'" class="order-img">';
                    echo '</figure>';
                    echo '<span class="order-item-info">'.$itemInfo['nombre'].'</span>';
                    echo '<span class="order-item-info">'.$itemInfo['categoria'].'</span>';
                    echo '<span class="order-item-info">'.$item['cantidad'].'</span>';
                    echo '<span class="order-item-info">$'.$item['precio'].'</span>';
                    echo '<span class="order-item-info">$'.$item['precio'] * $item['cantidad'].'</span>';
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
        <div class="order-filters">
            <div class="order-filter-box">
                <span class="filter-title">Filtros</span>
                <hr class="filter-hr">
                <span class="filter-text"><b>Estado:</b></span>
                <form class="filter-form" id="filter-form" method="GET" action="">
                    <div class="filter-option">
                        <input type="checkbox" id="todos" name="estado" value=""
                        <?php if ($estado === '') echo 'checked'; ?>>
                        <label for="todos" class="filter-text">Todos</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="enproceso" name="estado" value="En proceso"
                        <?php if ($estado === 'En proceso') echo 'checked'; ?>>
                        <label for="enproceso" class="filter-text">En proceso</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="confirmado" name="estado" value="Confirmado"
                        <?php if ($estado === 'Confirmado') echo 'checked'; ?>>
                        <label for="confirmado" class="filter-text">Confirmado</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="enviando" name="estado" value="Enviando"
                        <?php if ($estado === 'Enviando') echo 'checked'; ?>>
                        <label for="enviando" class="filter-text">Enviando</label>
                    </div>
                </form>
                <script>
                    document.querySelectorAll('input[type="checkbox"][name="estado"]').forEach(chk => {
                        chk.addEventListener('change', function () {
                            document.querySelectorAll('input[type="checkbox"][name="estado"]').forEach(c => {
                                if (c !== this) c.checked = false;
                            });
                            document.getElementById('filter-form').submit();
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</body>