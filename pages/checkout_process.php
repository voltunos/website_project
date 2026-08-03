<?php

session_start();
require_once 'auth.php';

$method = $_POST['metodo'] ?? '';
$direccion = $_POST['id_direccion'] ?? '';
$adicional = $_POST['adicional'] ?? '';

if (empty($method) || empty($direccion)) {
    header("Location: cart.php");
    exit();
}

$validMethods = ["efectivo", "transferencia", "mercadopago"];

if (!in_array($method, $validMethods)) {
    header("Location: cart.php");
    exit();
}

$_SESSION['id_direccion'] = $direccion;
$_SESSION['adicional'] = $adicional;

switch($method) {
    case "efectivo":
        header("Location: efectivo.php");
        exit();
    case "transferencia":
        header("Location: transferencia.php");
        exit();
    case "mercadopago":
        header("Location: mercadopago.php");
        exit();
}

?>