<?php
require_once __DIR__ . '/../config/bootstrap.php';

if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "views/login_register.php?error=Please login to continue");
    exit;
}

require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/CartItem.php';

/* Add to cart */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addtocart'], $_POST['product_id'])) {

    if (!isset($_SESSION['cart_id'])) {
        $userId = $_SESSION['user']['id'] ?? null;

        if ($userId) {
            $stmt = $conn->prepare(
                "SELECT id FROM cart WHERE user_id = :user_id LIMIT 1"
            );
            $stmt->execute([':user_id' => $userId]);
            $existingCart = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingCart) {
                $_SESSION['cart_id'] = $existingCart['id'];
                $cart = Cart::getById($existingCart['id'], $conn);
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

    $productIdPost = (int) $_POST['product_id'];
    CartItem::addToCart($conn, $cart->getId(), $productIdPost, 1);

    $successMessage = "Product added to cart successfully!";
}

/* Product data */
$productId = $_GET['id'] ?? null;
if (!$productId) {
    die("Product ID is missing.");
}

$product = Product::getById((int) $productId, $conn);
if (!$product) {
    die("Product not found.");
}

$reviews = Review::getByProductId((int) $productId, $conn);
