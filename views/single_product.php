<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "views/login_register.php?error=Please login to continue");
    exit;
}

require_once '../config/Database.php';
require_once '../config/config.php';
$conn = Database::getInstance();

require_once '../models/Review.php';
require_once '../models/Product.php';
require_once '../models/Cart.php';
require_once '../models/CartItem.php';

// Add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addtocart']) && isset($_POST['product_id'])) {
    if (!isset($_SESSION['cart_id'])) {
        $userId = $_SESSION['user']['id'] ?? null;

        if ($userId) {
            $stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = :user_id LIMIT 1");
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

    $productId = (int) $_POST['product_id'];
    CartItem::addToCart($conn, $cart->getId(), $productId, 1);

    $successMessage = "Product added to cart successfully!";
}

// Get data product
$productId = $_GET['id'] ?? null;
if (!$productId) {
    die("Product ID is missing.");
}

$product = Product::getById((int) $productId, $conn);
if (!$product) {
    die("Product not found.");
}

$reviews = Review::getByProductId((int) $productId, $conn);

include '../components/header.php';
?>

<!-- ##### Single Product Details Area Start ##### -->
<section class="single_product_details_area d-flex align-items-center">

    <!-- Single Product Thumb -->
    <div class="single_product_thumb clearfix">
        <div class="product-image-container">
            <img src="<?= BASE_URL . $product->getImage() ?>" alt="<?= htmlspecialchars($product->getName()) ?>"
                style="width: 400px; height: 400px; object-fit: contain; display: block; margin: 0 auto;">
        </div>
    </div>

    <!-- Single Product Description -->
    <div class="single_product_desc clearfix">
        <span><?= htmlspecialchars($product->getBrand()) ?></span>
        <h2><?= htmlspecialchars($product->getName()) ?></h2>
        <h3>$<?= number_format($product->getPrice(), 2) ?></h3>

        <?php if ($product->getOldPrice()): ?>
            <p><del>$<?= number_format($product->getOldPrice(), 2) ?></del></p>
        <?php endif; ?>

        <?php if ($product->getDiscount()): ?>
            <p class="text-success">Discount: <?= htmlspecialchars($product->getDiscount()) ?></p>
        <?php endif; ?>

        <p class="product-desc">
            Mauris viverra cursus ante laoreet eleifend. Donec vel fringilla ante. Aenean finibus velit id urna
            vehicula, nec maximus est sollicitudin.
        </p>

        <!-- Form -->
        <form class="cart-form clearfix" method="post" action="<?= BASE_URL ?>views/single_product.php?id=<?= $productId ?>">
            <input type="hidden" name="product_id" value="<?= $productId ?>">
            <!-- Cart & Favourite Box -->
            <div class="cart-fav-box d-flex align-items-center">
                <button type="submit" name="addtocart" class="btn essence-btn">
                    Add to cart
                </button>                
                <div class="product-favourite ml-4">
                    <a href="#" class="favme fa fa-heart"></a>
                </div>
            </div>
        </form>

        <?php if (isset($successMessage)): ?>
            <p class="text-success mt-3"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>

        <!-- Reviews -->
        <div class="product-reviews mt-5" style="max-height: 280px; overflow-y: auto; scrollbar-width: thin;">
            <h4 class="mb-3">Customer Reviews</h4>

            <?php if (count($reviews) === 0): ?>
                <p class="text-muted">This product has no reviews yet.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="single-review p-3 mb-2" style="background: #f9f9f9; border-left: 4px solid #0315ff;">
                        <strong><?= $review->getRating() ?>/5 ⭐</strong>
                        <p class="mb-1"><?= htmlspecialchars($review->getComment()) ?></p>
                        <small class="text-muted"><?= date('F j, Y', strtotime($review->getCreatedAt())) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>
<!-- ##### Single Product Details Area End ##### -->

<?php include '../components/footer.php'; ?>

<script src="<?= BASE_URL ?>js/jquery/jquery-2.2.4.min.js"></script>
<script src="<?= BASE_URL ?>js/popper.min.js"></script>
<script src="<?= BASE_URL ?>js/bootstrap.min.js"></script>
<script src="<?= BASE_URL ?>js/plugins.js"></script>
<script src="<?= BASE_URL ?>js/classy-nav.min.js"></script>
<script src="<?= BASE_URL ?>js/active.js"></script>

</body>

</html>