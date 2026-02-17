<?php
require_once '../controllers/checkoutController.php';

$pageScript = 'checkout.js';
include '../components/header.php';
?>

<div class="container mt-5 mb-5">
    <h2>Complete Purchase</h2>

    <div class="row">
        <!-- Address form -->
        <div class="col-md-6">
            <h4>Mailing address</h4>

            <button
                id="autoCompleteBtn"
                class="btn btn-secondary mb-3"
                <?= $addressData ? '' : 'disabled' ?>
            >
                Autocomplete address
            </button>

            <form
                id="addressForm"
                action="<?= BASE_URL ?>controllers/confirm_order.php"
                method="post"
                novalidate
            >
                <div class="form-group">
                    <label for="street">Street</label>
                    <input type="text" id="street" name="street" class="form-control" required minlength="3">
                </div>

                <div class="form-group">
                    <label for="number">Number</label>
                    <input type="text" id="number" name="number" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="state">Province / State</label>
                    <input type="text" id="state" name="state" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="postal_code">Zip code</label>
                    <input type="text" id="postal_code" name="postal_code" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Description (optional)</label>
                    <input type="text" id="description" name="description" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">
                    Confirm purchase
                </button>
            </form>
        </div>

        <!-- Cart summary -->
        <div class="col-md-6">
            <h4>Summary of your purchase</h4>
            <ul class="list-group">
                <?php foreach ($items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>)
                        <span>
                            $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                        </span>
                    </li>
                <?php endforeach; ?>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Total</strong>
                    <strong>$<?= number_format($total, 2) ?></strong>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>

<?php if ($addressData): ?>
<script>
    window.addressData = <?= json_encode($addressData) ?>;
</script>
<?php endif; ?>