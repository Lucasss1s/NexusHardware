<?php
require_once '../config/bootstrap.php';

require_once '../models/Cart.php';
require_once '../models/CartItem.php';
require_once '../models/Address.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['cart_id'])) {
    header("Location: " . BASE_URL . "views/login_register.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$cartId = $_SESSION['cart_id'];

$cart = Cart::getById($cartId, $conn);
$items = $cart->getItems($conn);

$addresses = Address::getByUserId($conn, $userId);

$addressData = null;
if (!empty($addresses)) {
    $a = $addresses[0];
    $addressData = [
        'street' => $a->getStreet(),
        'number' => $a->getNumber(),
        'city' => $a->getCity(),
        'state' => $a->getState(),
        'postalCode' => $a->getPostalCode(),
        'country' => $a->getCountry(),
        'description' => $a->getDescription(),
    ];
}

$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}

$pageScript = 'checkout.js';
include '../components/header.php';
?>

<div class="container mt-5 mb-5">
    <h2>Complete Purchase</h2>

    <div class="row">
        <!-- Address form -->
        <div class="col-md-6">
            <h4>Mailing address</h4>
            <button id="autoCompleteBtn" class="btn btn-secondary mb-3">Autocomplete address</button>

            <form id="addressForm" action="<?= BASE_URL ?>controllers/confirm_order.php" method="post" novalidate>

                <div class="form-group">
                    <label for="street">Street</label>
                    <input type="text" id="street" name="street" class="form-control" required minlength="3" pattern="^[A-Za-z0-9\s\.,'-]+$">
                    <div class="invalid-feedback">
                        Please enter a valid street address (minimum 3 characters).
                    </div>
                </div>

                <div class="form-group">
                    <label for="number">Number</label>
                    <input type="text" id="number" name="number" class="form-control" required pattern="^[A-Za-z0-9\s\-]+$" minlength="1" maxlength="10">
                    <div class="invalid-feedback">
                        Please enter a valid street number (may include letters, numbers, and hyphens).
                    </div>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control" required minlength="2" maxlength="50" pattern="^[A-Za-z\s]+$">
                    <div class="invalid-feedback">
                        Please enter a valid city name.
                    </div>
                </div>

                <div class="form-group">
                    <label for="state">Province/State</label>
                    <input type="text" id="state" name="state" class="form-control" required minlength="2" maxlength="50" pattern="^[A-Za-z\s]+$">
                    <div class="invalid-feedback">
                        Please enter a valid province or state name.
                    </div>
                </div>

                <div class="form-group">
                    <label for="postal_code">Zip code</label>
                    <input type="text" id="postal_code" name="postal_code" class="form-control" required pattern="^[A-Za-z0-9\s\-]+$" minlength="3" maxlength="15">
                    <div class="invalid-feedback">
                        Please enter a valid postal code.
                    </div>
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" class="form-control" required minlength="2" maxlength="50" pattern="^[A-Za-z\s]+$">
                    <div class="invalid-feedback">
                        Please enter a valid country name.
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description (optional)</label>
                    <input type="text" id="description" name="description" class="form-control" maxlength="100">
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

<?php if ($addressData !== null): ?>
<script>
    window.addressData = <?= json_encode($addressData) ?>;
</script>
<?php endif; ?>