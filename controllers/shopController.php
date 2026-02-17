<?php

require_once __DIR__ . '/../config/bootstrap.php';

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/CartItem.php';

/*Add to cart (POST)*/
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['add_to_cart'], $_POST['product_id'])
) {
    $productId = (int) $_POST['product_id'];

    if (!isset($_SESSION['cart_id'])) {
        $userId = $_SESSION['user']['id'] ?? null;

        if ($userId) {
            $stmt = $conn->prepare(
                "SELECT id FROM cart WHERE user_id = :user_id LIMIT 1"
            );
            $stmt->execute([':user_id' => $userId]);
            $cartRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cartRow) {
                $_SESSION['cart_id'] = $cartRow['id'];
                $cart = Cart::getById($cartRow['id'], $conn);
            } else {
                $cart = Cart::create($conn, $userId);
                $_SESSION['cart_id'] = $cart->getId();
            }
        } else {
            $cart = Cart::create($conn, null);
            $_SESSION['cart_id'] = $cart->getId();
        }
    } else {
        $cart = Cart::getById($_SESSION['cart_id'], $conn);
    }

    CartItem::addToCart($conn, $cart->getId(), $productId, 1);

    header("Location: " . BASE_URL . "views/shop.php");
    exit;
}

/* Categories*/
$categories = Category::getAll($conn);

/* Load products */
$currentCategoryName = $_GET['category'] ?? null;
$products = [];

try {
    if ($currentCategoryName) {
        $category = Category::getByName($currentCategoryName, $conn);

        if ($category) {
            $stmt = $conn->prepare(
                "SELECT * FROM product WHERE category_id = :cat_id"
            );
            $stmt->execute([':cat_id' => $category->getId()]);
        } else {
            $stmt = $conn->query("SELECT * FROM product WHERE 0");
        }
    } else {
        $stmt = $conn->query("SELECT * FROM product");
    }

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Throwable $e) {
    error_log('[SHOP] ' . $e->getMessage());
    $products = [];
}



