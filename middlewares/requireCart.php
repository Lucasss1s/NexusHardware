<?php
require_once __DIR__ . '/../config/bootstrap.php';

if (!isset($_SESSION['cart_id'])) {
    header('Location: ' . BASE_URL . 'index.php?error=cart_required');
    exit;
}
