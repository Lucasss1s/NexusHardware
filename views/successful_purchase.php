<?php
$pageScript = 'single_product.js';
require_once '../config/bootstrap.php';
include '../components/header.php';
?>

<div class="container mt-5 mb-5 text-center">
    <h2 class="mb-4">¡Thank you for your purchase!</h2>
    <p class="lead">Your order has been successfully processed. You will receive an email confirmation.</p>

    <a href="<?= BASE_URL ?>views/shop.php" class="btn essence-btn mt-4">Return to the store</a>
</div>

<?php include '../components/footer.php'; ?>

