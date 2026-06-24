<?php

session_start();
require_once 'database.php';
require_once 'role_verify.php';

requireRole(["Administrador"], $pdo);

$id = $_POST['id_pedido'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($id) || empty($action)) {
    $_SESSION['error'] = "Los campos obligatorios están vacios.";
    header("Location: orders.php");
    exit();
}

$stateConversion = [ 
    "confirm" => "Confirmado", 
    "deliver" => "Enviando", 
    "complete" => "Completado", 
    "cancel" => "Cancelado", 
    "refund" => "Esperando reembolso" 
];
$dbToInternal = [
    "En proceso" => "processing",
    "Confirmado" => "confirmed",
    "Enviando" => "delivering",
    "Completado" => "completed",
    "Cancelado" => "cancelled",
    "Esperando reembolso" => "refund"
];
$availableMethods = [
    "processing" => ["confirm", "deliver", "complete", "cancel"],
    "confirmed" => ["deliver", "complete", "cancel"],
    "delivering" => ["complete", "cancel"],
    "completed" => [],
    "cancelled" => [],
    "refund" => ["cancel"]
];

if (!isset($stateConversion[$action])) {
    $_SESSION['error'] = "Acción inválida.";
    header("Location: orders.php");
    exit();
}

$setTo = $stateConversion[$action];
$verify = $pdo->prepare("SELECT id_pedido, metodo_pago, estado FROM pedido WHERE id_pedido = :id_pedido");
$verify->execute([
    ":id_pedido" => $id
]);
$data = $verify->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    $_SESSION['error'] = "Pedido no encontrado.";
    header("Location: orders.php");
    exit();
}

$currentState = $dbToInternal[$data['estado']] ?? null;
if (!$currentState) {
    $_SESSION['error'] = "Estado inválido.";
    header("Location: orders.php");
    exit();
}

if (!in_array($action, $availableMethods[$currentState] ?? [])) {
    $_SESSION['error'] = "Acción no permitida.";
    header("Location: orders.php");
    exit();
}

if ($action == "cancel") {
    if (in_array($data['metodo_pago'], ["mercadopago", "transferencia"])) {
        $setTo = "Esperando reembolso";
    } else {
        $setTo = "Cancelado";
    }
}

$pdo->beginTransaction();

try {
    $update = $pdo->prepare("UPDATE pedido SET estado = :estado WHERE id_pedido = :id_pedido");
    $update->execute([
        ":estado" => $setTo,
        ":id_pedido" => $id
    ]);

    if ($update->rowCount() === 0) {
        throw new Exception("No se pudo actualizar el pedido");
    }
    
    if ($action == "complete") {
        $stmt = $pdo->prepare("SELECT id_usuario, total FROM pedido WHERE id_pedido = :id_pedido");
        $stmt->execute([
            ":id_pedido" => $id
        ]);
        $completionData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$completionData) {
        throw new Exception("Pedido no encontrado");
        }

        $addPoints = floor($completionData['total'] / 10);

        $stmt2 = $pdo->prepare("UPDATE users SET blendpoints = blendpoints + :points WHERE id_usuario = :id_usuario");
        $stmt2->execute([
            ":points" => $addPoints,
            ":id_usuario" => $completionData['id_usuario']
        ]);
    }

    $pdo->commit();
    $_SESSION['success'] = "Se cambió el estado del pedido correctamente.";
} catch(Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    $pdo->rollBack();
}

header("Location: orders.php");
exit();

?>