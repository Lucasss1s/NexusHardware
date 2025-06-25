<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /nexushardware/views/login_register.php?error=Please login to continue");
    exit;
}

require_once '../config/Database.php';
$conn = Database::getInstance();

require_once '../models/Review.php';
require_once '../models/Product.php';


$productId = $_GET['id'] ?? null;
if (!$productId) {
    die("Product ID is missing.");
}

$product = Product::getById((int)$productId, $conn);
if (!$product) {
    die("Product not found.");
}

$reviews = Review::getByProductId((int)$productId, $conn);

include '../components/header.php';
?>


    <!-- ##### Single Product Details Area Start ##### -->
    <section class="single_product_details_area d-flex align-items-center">

        <!-- Single Product Thumb -->
        <div class="single_product_thumb clearfix">
<!--             <div class="product_thumbnail_slides owl-carousel">
                <img src="../img/product-img/product-big-2.jpg" alt="">
                <img src="../img/product-img/product-big-3.jpg" alt="">
            </div> -->
            <div class="product-image-container">
                <img src="<?= $product->getImage() ?>" alt="<?= htmlspecialchars($product->getName()) ?>">
            </div>
        </div>

        <!-- Single Product Description -->
        <div class="single_product_desc clearfix">
            <span><?=$product->getBrand() ?></span>
            <h2><?=$product->getName() ?></h2>
            <h3>$<?= number_format($product->getPrice(), 2) ?></h3>

            <?php if ($product->getOldPrice()): ?>
                <p><del>$<?= number_format($product->getOldPrice(), 2) ?></del></p>
            <?php endif; ?>

            <?php if ($product->getDiscount()): ?>
                <p class="text-success">Discount: <?= htmlspecialchars($product->getDiscount()) ?></p>
            <?php endif; ?>
            <p class="product-desc">Mauris viverra cursus ante laoreet eleifend. Donec vel fringilla ante. Aenean finibus velit id urna vehicula, 
                nec maximus est sollicitudin.</p>

            <!-- Form -->
            <form class="cart-form clearfix" method="post">
                <!-- Cart & Favourite Box -->
                <div class="cart-fav-box d-flex align-items-center">
                    <!-- Cart -->
                    <button type="submit" name="addtocart" value="5" class="btn essence-btn">Add to cart</button>
                    <!-- Favourite -->
                    <div class="product-favourite ml-4">
                        <a href="#" class="favme fa fa-heart"></a>
                    </div>
                </div>
            </form>
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

    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="../js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="../js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="../js/bootstrap.min.js"></script>
    <!-- Plugins js -->
    <script src="../js/plugins.js"></script>
    <!-- Classy Nav js -->
    <script src="../js/classy-nav.min.js"></script>
    <!-- Active js -->
    <script src="../js/active.js"></script>

</body>

</html>