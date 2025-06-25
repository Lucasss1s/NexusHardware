<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/connection.php';
require_once '../models/Payment.php';
require_once '../models/Order.php';

if (!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login_register.php");
    exit;
}

$orderId = (int)$_POST['order_id'];
$method = $_POST['method'] ?? 'Credit Card';

try {
    $conn->beginTransaction();

    // Verificar que la orden exista y pertenezca al usuario
    $order = Order::getById($conn, $orderId);
    if (!$order || $order->getUserId() !== $_SESSION['user']['id']) {
        throw new Exception("Orden no válida.");
    }

    // Crear el pago
    $payment = Payment::create($conn, $method, 'Paid');
    if (!$payment) {
        throw new Exception("Error al registrar el pago.");
    }

    // Actualizar la orden: asignar id_payment y estado
    $stmt = $conn->prepare("UPDATE `order` SET id_payment = :id_payment, status = 'Completed' WHERE id_order = :id_order");
    $stmt->execute([
        ':id_payment' => $payment->getId(),
        ':id_order' => $orderId
    ]);

    $conn->commit();

    // Redirigir al historial
    header("Location: ../views/successful_purchase.php");
    exit;

} catch (Exception $e) {
    $conn->rollBack();
    die("Error en el pago: " . $e->getMessage());
}
