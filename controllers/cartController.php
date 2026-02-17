<?php

require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../models/Cart.php';

class CartController
{
    public static function handleRemove(PDO $conn): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item_id'], $_SESSION['cart_id'])) {
            $cart = Cart::getById((int) $_SESSION['cart_id'], $conn);

            if ($cart) {
                $cart->removeItem($conn, (int) $_POST['remove_item_id']);
            }

            header('Location: ' . BASE_URL . 'views/cart_product.php');
            exit;
        }
    }

    public static function getCartData(PDO $conn): array
    {
        if (!isset($_SESSION['cart_id'])) {
            return [
                'cart' => null,
                'items' => [],
                'total' => 0
            ];
        }

        $cart = Cart::getById((int) $_SESSION['cart_id'], $conn);

        if (!$cart) {
            return [
                'cart' => null,
                'items' => [],
                'total' => 0
            ];
        }

        $items = $cart->getItems($conn);

        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return compact('cart', 'items', 'total');
    }
}
