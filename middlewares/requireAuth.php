<?php
require_once __DIR__ . '/../config/bootstrap.php';

if (!isset($_SESSION['user'])) {
    header('Location: ' . BASE_URL . 'views/login_register.php?error=auth_required');
    exit;
}
