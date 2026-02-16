<?php
require_once '../config/bootstrap.php';

include '../components/header.php';
?>

<div class="container mt-5 mb-5 text-center">
    <h2 class="mb-4">¡Thank you for your purchase!</h2>
    <p class="lead">Your order has been successfully processed. You will receive an email confirmation.</p>

    <a href="<?= BASE_URL ?>views/shop.php" class="btn essence-btn mt-4">Return to the store</a>
</div>

<?php include '../components/footer.php'; ?>

<script src="<?= BASE_URL ?>js/jquery/jquery-2.2.4.min.js"></script>
<script src="<?= BASE_URL ?>js/popper.min.js"></script>
<script src="<?= BASE_URL ?>js/bootstrap.min.js"></script>
<script src="<?= BASE_URL ?>js/plugins.js"></script>
<script src="<?= BASE_URL ?>js/classy-nav.min.js"></script>
<script src="<?= BASE_URL ?>js/active.js"></script>
