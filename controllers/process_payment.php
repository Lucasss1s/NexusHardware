<?php
require_once '../config/bootstrap.php';

require_once '../models/Payment.php';
require_once '../models/Order.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "views/login_register.php");
    exit;
}

$orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
$method  = isset($_POST['method']) ? trim($_POST['method']) : 'Credit Card';

if ($orderId <= 0) {
    header("Location: " . BASE_URL . "views/checkout.php?error=invalid_order");
    exit;
}

try {
    $conn->beginTransaction();

    $order = Order::getById($conn, $orderId);

    if (!$order || $order->getUserId() !== $_SESSION['user_id']) {
        throw new Exception('Invalid order');
    }

    if ($order->getStatus() !== 'Pending') {
        throw new Exception('Order already processed');
    }

    $payment = Payment::create($conn, $method, 'Paid');
    if (!$payment) {
        throw new Exception('Payment creation failed');
    }

    $stmt = $conn->prepare("
        UPDATE `order`
        SET id_payment = :id_payment,
            status = 'Completed'
        WHERE id_order = :id_order
    ");

    $stmt->execute([
        ':id_payment' => $payment->getId(),
        ':id_order'   => $orderId
    ]);

    $conn->commit();

    header("Location: " . BASE_URL . "views/successful_purchase.php?success=1");
    exit;

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    error_log('[PROCESS_PAYMENT] ' . $e->getMessage());

    header("Location: " . BASE_URL . "views/checkout.php?error=payment_failed");
    exit;
}
