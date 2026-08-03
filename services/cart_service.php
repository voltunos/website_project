<?php

class CartService {
    public static function calculateOrderTotal(array $items) {
        $total = 0;
        foreach ($items as $item) {
            $subtotal = $item["precio"] * $item["cantidad"];
            $total += $subtotal;
        }

        return $total;
    }
}

?>