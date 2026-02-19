<?php
require_once '../middlewares/requireAuth.php';
require_once '../middlewares/requireAdmin.php';

require_once '../config/bootstrap.php';

require_once '../models/Product.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Missing product ID.");
}

if (Product::deleteById((int)$id, $conn)) {
    header("Location: products.php?deleted=ok");
    exit;
} else {
    echo "Failed to delete product.";
}

?>