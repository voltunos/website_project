<?php 

class ConfigService {
    public static function getConfig(PDO $pdo, string $key) {
        $stmt = $pdo->prepare("SELECT valor FROM configuracion WHERE clave = :clave LIMIT 1");
        $stmt->execute([
            ":clave" => $key
        ]);

        $value = $stmt->fetchColumn();

        return $value === false ? null : $value;
    }

    public static function getShippingCost(PDO $pdo): int {
        $shipping = self::getConfig($pdo, "shipping_cost");

        if ($shipping === null) {
            throw new Exception("Error while trying to get shipping cost.");
        }

        return (int)$shipping;
    }
}
?>