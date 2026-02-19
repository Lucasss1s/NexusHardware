<?php
require_once __DIR__ . '/../config/bootstrap.php';

if (
    !isset($_SESSION['user']) ||
    ($_SESSION['user']['role'] ?? null) !== 'customer'
) {
    header('Location: ' . BASE_URL . 'index.php?error=customer_only');
    exit;
}
