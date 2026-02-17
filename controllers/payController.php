<?php
require_once __DIR__ . '/../config/bootstrap.php';

require_once __DIR__ . '/../models/Order.php';

/* Auth */
if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "views/login_register.php");
    exit;
}

/* Validation*/
if (!isset($_GET['order_id'])) {
    die("Order ID not specified.");
}

$orderId = (int) $_GET['order_id'];
$order = Order::getById($conn, $orderId);

// Validate order
if (
    !$order ||
    $order->getUserId() !== $_SESSION['user']['id']
) {
    die("Invalid or unauthorized order.");
}
