<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../components/header.php';
?>

<div class="container mt-5 mb-5 text-center">
    <h2 class="mb-4">¡Thank you for your purchase!</h2>
    <p class="lead">Your order has been successfully processed. You will receive an email confirmation.</p>

    <a href="shop.php" class="btn essence-btn mt-4">Return to the store</a>
</div>

<?php include '../components/footer.php'; ?>

<!-- jQuery (Necessary for All JavaScript Plugins) -->
<script src="../js/jquery/jquery-2.2.4.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/plugins.js"></script>
<script src="../js/classy-nav.min.js"></script>
<script src="../js/active.js"></script>
