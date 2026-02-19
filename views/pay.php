<?php
require_once '../middlewares/requireAuth.php';
require_once '../middlewares/requireCustomer.php';
require_once '../middlewares/requireCart.php';

$pageScript = 'pay.js';
require_once '../controllers/payController.php';
include '../components/header.php';
?>

<div class="container mt-5 mb-5">
    <h2>Order Payment #<?= $order->getId() ?></h2>

    <form
        id="payment-form"
        action="<?= BASE_URL ?>controllers/process_payment.php"
        method="post"
        class="needs-validation"
        novalidate
    >
        <input type="hidden" name="order_id" value="<?= $order->getId() ?>">

        <div class="mb-3">
            <label for="card_number">Card Number</label>
            <input
                type="text"
                id="card_number"
                name="card_number"
                class="form-control"
                maxlength="16"
                pattern="^\d{16}$"
                required
            >
            <div class="invalid-feedback">
                Please enter a valid 16-digit card number.
            </div>
        </div>

        <div class="mb-3">
            <label for="card_name">Name on Card</label>
            <input
                type="text"
                id="card_name"
                name="card_name"
                class="form-control"
                required
                minlength="3"
                maxlength="50"
                pattern="^[A-Za-z\s]+$"
            >
            <div class="invalid-feedback">
                Enter the name as it appears on the card.
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col">
                <label for="card_expiry">Expiration Date</label>
                <input
                    type="month"
                    id="card_expiry"
                    name="card_expiry"
                    class="form-control"
                    required
                    min="<?= date('Y-m') ?>"
                >
                <div class="invalid-feedback">
                    Select a valid expiration date.
                </div>
            </div>

            <div class="col">
                <label for="card_cvv">CVV</label>
                <input
                    type="text"
                    id="card_cvv"
                    name="card_cvv"
                    class="form-control"
                    maxlength="4"
                    pattern="^\d{3,4}$"
                    required
                >
                <div class="invalid-feedback">
                    Please enter a valid 3 or 4 digit CVV.
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="method">Payment Method</label>
            <select id="method" name="method" class="form-control" required>
                <option value="" disabled selected>Select a method</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Debit Card">Debit Card</option>
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

<?php include '../components/footer.php'; ?>
