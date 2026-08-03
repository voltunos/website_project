<?php

$host = "localhost";
$db = "blendburger";
$user = "blendburger_user";
$pass = "Y6lCRqhN5g1vojBxFTymHzfPh17X3IU5CK5qOJ7UHkZ9L4gcjm";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

?>