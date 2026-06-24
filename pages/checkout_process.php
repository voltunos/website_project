<?php

session_start();
require_once 'auth.php';

$method = $_POST['metodo'] ?? '';
$direccion = $_POST['id_direccion'] ?? '';

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