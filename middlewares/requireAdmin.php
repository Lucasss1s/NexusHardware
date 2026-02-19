<?php
require_once __DIR__ . '/../config/bootstrap.php';

if (
    !isset($_SESSION['user']) ||
    ($_SESSION['user']['role'] ?? null) !== 'admin'
) {
    header('Location: ' . BASE_URL . 'index.php?error=unauthorized');
    exit;
}
