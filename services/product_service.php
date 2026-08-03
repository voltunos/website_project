<?php 

class ProductService {
    public static function getProductsFromCart(array $cart, PDO $pdo) {
        $ids = array_keys($cart);

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $pdo->prepare("SELECT id_producto, nombre, precio, categoria FROM productos WHERE id_producto IN ($placeholders) AND activo = 1");
        $stmt->execute($ids);

        $products = [];

        while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $product["cantidad"] = $cart[$product["id_producto"]];

            $products[$product["id_producto"]] = $product;
        }

        return $products;
    }
    public static function organizeProducts(array $items, PDO $pdo) {
        $ids = array_keys($items);

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $pdo->prepare("SELECT id_producto, nombre, precio, categoria, imagen FROM productos WHERE id_producto IN ($placeholders)");
        $stmt->execute($ids);

        $products = [];

        while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[$product["id_producto"]] = $product;
        }

        return $products;
    }
}

?>