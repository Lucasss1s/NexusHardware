<?php
require_once '../config/bootstrap.php';

require_once '../models/Order.php';
require_once '../models/Payment.php';

if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "views/login_register.php");
    exit;
}

// Obtener ID de la orden
if (!isset($_GET['order_id'])) {
    die("ID de orden no especificado.");
}

$orderId = (int) $_GET['order_id'];
$order = Order::getById($conn, $orderId);

// Validar que la orden exista y pertenezca al usuario
if (!$order || $order->getUserId() !== $_SESSION['user']['id']) {
    die("Orden no válida o no autorizada.");
}

include '../components/header.php';
?>

<div class="container mt-5 mb-5">
    <h2>Order Payment #<?= $order->getId() ?></h2>

    <form id="payment-form" action="<?= BASE_URL ?>controllers/process_payment.php" method="post" class="needs-validation" novalidate>
        <input type="hidden" name="order_id" value="<?= $order->getId() ?>">

        <!-- Número de tarjeta -->
        <div class="mb-3">
            <label for="card_number">
                Card Number
            </label>
            <input type="text" id="card_number" name="card_number" class="form-control" maxlength="16" pattern="^\d{16}$" required>
            <div class="invalid-feedback">
                Please enter a valid 16-digit card number.
            </div>
        </div>

        <!-- Nombre en la tarjeta -->
        <div class="mb-3">
            <label for="card_name">
                Name on Card
            </label>
            <input type="text" id="card_name" name="card_name" class="form-control" required minlength="3" maxlength="50"pattern="^[A-Za-z\s]+$">
            <div class="invalid-feedback">
                Enter the name as it appears on the card.
            </div>
        </div>

        <!-- Fecha de expiración y CVV -->
        <div class="mb-3 row">
            <div class="col">
                <label for="card_expiry">
                    Expiration Date
                </label>
                <input type="month" id="card_expiry" name="card_expiry" class="form-control" required min="<?= date('Y-m') ?>" >
                <div class="invalid-feedback">Select a valid expiration date.</div>
            </div>
            <div class="col">
                <label for="card_cvv">
                    CVV
                </label>
                <input type="text" id="card_cvv" name="card_cvv" class="form-control" maxlength="4" pattern="^\d{3,4}$" required>
                <div class="invalid-feedback">
                    Please enter a valid 3 or 4 digit CVV.
                </div>
            </div>
        </div>

        <!-- Método de pago -->
        <div class="mb-3">
            <label for="method">
                Payment Method
            </label>
            <select id="method" name="method" class="form-control" required>
                <option value="" disabled selected>
                    Select a method
                </option>
                <option value="Credit Card">
                    Credit Card
                </option>
                <option value="Debit Card">
                    Debit Card
                </option>
            </select>
            <div class="invalid-feedback">
                Select a valid payment method.
            </div>
        </div>

        <button type="submit" class="btn btn-success">
            Pay $<?= number_format($order->getTotal(), 2) ?>
        </button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("payment-form");
        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add("was-validated");
        });
    });
</script>

<?php include '../components/footer.php'; ?>
