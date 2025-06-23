<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/connection.php';
require_once '../models/Order.php';
require_once '../models/Payment.php';

if (!isset($_SESSION['user'])) {
    header("Location: login_register.php");
    exit;
}

// Obtener ID de la orden
if (!isset($_GET['order_id'])) {
    die("ID de orden no especificado.");
}

$orderId = (int)$_GET['order_id'];
$order = Order::getById($conn, $orderId);

// Validar que la orden exista y pertenezca al usuario
if (!$order || $order->getUserId() !== $_SESSION['user']['id']) {
    die("Orden no válida o no autorizada.");
}

include '../components/header.php';
?>

<div class="container mt-5 mb-5">
    <h2>Pago de Orden #<?= $order->getId() ?></h2>

    <form action="../controllers/process_payment.php" method="post">
        <input type="hidden" name="order_id" value="<?= $order->getId() ?>">

        <div class="mb-3">
            <label>Número de Tarjeta</label>
            <input type="text" name="card_number" class="form-control" maxlength="16" required>
        </div>
        <div class="mb-3">
            <label>Nombre en la Tarjeta</label>
            <input type="text" name="card_name" class="form-control" required>
        </div>
        <div class="mb-3 row">
            <div class="col">
                <label>Fecha de Vencimiento</label>
                <input type="month" name="card_expiry" class="form-control" required>
            </div>
            <div class="col">
                <label>CVV</label>
                <input type="text" name="card_cvv" maxlength="4" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label>Método de Pago</label>
            <select name="method" class="form-control" required>
                <option value="Credit Card">Tarjeta de Crédito</option>
                <option value="Debit Card">Tarjeta de Débito</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Pagar $<?= number_format($order->getTotal(), 2) ?></button>
    </form>
</div>

<?php include '../components/footer.php'; ?>
