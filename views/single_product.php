<?php
$pageScript = 'single_product.js';
require_once '../controllers/singleProductController.php';
include '../components/header.php';
?>

<section class="single_product_details_area d-flex align-items-center">

    <!-- Single Product Thumb -->
    <div class="single_product_thumb clearfix">
        <div class="product-image-container">
            <img
                src="<?= BASE_URL . $product->getImage() ?>"
                alt="<?= htmlspecialchars($product->getName()) ?>"
                style="width: 400px; height: 400px; object-fit: contain; display: block; margin: 0 auto;"
            >
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
            <p class="text-success">
                Discount: <?= htmlspecialchars($product->getDiscount()) ?>
            </p>
        <?php endif; ?>

        <p class="product-desc">
            Mauris viverra cursus ante laoreet eleifend. Donec vel fringilla ante.
            Aenean finibus velit id urna vehicula, nec maximus est sollicitudin.
        </p>

        <!-- Form -->
        <form
            class="cart-form clearfix"
            method="post"
            action="<?= BASE_URL ?>views/single_product.php?id=<?= $productId ?>"
        >
            <input type="hidden" name="product_id" value="<?= $productId ?>">

            <div class="cart-fav-box d-flex align-items-center">
                <button type="submit" name="addtocart" class="btn essence-btn">
                    Add to cart
                </button>

                <div class="product-favourite ml-4">
                    <a href="#" class="favme fa fa-heart"></a>
                </div>
            </div>
        </form>

        <?php if (!empty($successMessage)): ?>
            <p class="text-success mt-3">
                <?= htmlspecialchars($successMessage) ?>
            </p>
        <?php endif; ?>

        <!-- Reviews -->
        <div class="product-reviews mt-5" style="max-height: 280px; overflow-y: auto;">
            <h4 class="mb-3">Customer Reviews</h4>

            <?php if (count($reviews) === 0): ?>
                <p class="text-muted">This product has no reviews yet.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div
                        class="single-review p-3 mb-2"
                        style="background:#f9f9f9;border-left:4px solid #0315ff;"
                    >
                        <strong><?= $review->getRating() ?>/5 ⭐</strong>
                        <p class="mb-1">
                            <?= htmlspecialchars($review->getComment()) ?>
                        </p>
                        <small class="text-muted">
                            <?= date('F j, Y', strtotime($review->getCreatedAt())) ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php include '../components/footer.php'; ?>
