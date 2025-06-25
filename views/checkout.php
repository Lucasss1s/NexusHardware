<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/Database.php';
$conn = Database::getInstance();

require_once '../models/Cart.php';
require_once '../models/CartItem.php';
require_once '../models/Address.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['cart_id'])) {
    header("Location: login_register.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$cartId = $_SESSION['cart_id'];
$cart = Cart::getById($cartId, $conn);
$items = $cart->getItems($conn);

$addresses = Address::getByUserId($conn, $userId);

$addressData = null;
if (count($addresses) > 0) {
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
?>

<?php include '../components/header.php'; ?>

<div class="container mt-5 mb-5">
    <h2>Complete Purchase</h2>

    <div class="row">
        <!-- Formulario de dirección -->
        <div class="col-md-6">
            <h4>Mailing address</h4>
            <button id="autoCompleteBtn" class="btn btn-secondary mb-3">Autocomplete address</button>

            <form id="addressForm" action="/nexushardware/controllers/confirm_order.php" method="post" novalidate>
                <div class="form-group">
                    <label for="street">Street</label>
                    <input type="text" id="street" name="street" class="form-control" required minlength="3"
                        maxlength="100" pattern="^[A-Za-z0-9\s\.,'-]+$"
                        title="Please enter a valid street address (minimum 3 characters).">
                    <div class="invalid-feedback">Please enter a valid street address (minimum 3 characters).</div>
                </div>
                <div class="form-group">
                    <label for="number">Number</label>
                    <input type="text" id="number" name="number" class="form-control" required
                        pattern="^[A-Za-z0-9\s\-]+$" minlength="1" maxlength="10"
                        title="Please enter a valid street number (may include letters, numbers, and hyphens).">
                    <div class="invalid-feedback">Please enter a valid street number (may include letters, numbers, and hyphens).</div>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control" required minlength="2" maxlength="50"
                        pattern="^[A-Za-z\s]+$" title="Please enter a valid city name.">
                    <div class="invalid-feedback">Please enter a valid city name.</div>
                </div>
                <div class="form-group">
                    <label for="state">Province/State</label>
                    <input type="text" id="state" name="state" class="form-control" required minlength="2"
                        maxlength="50" pattern="^[A-Za-z\s]+$"
                        title="Please enter a valid province or state name.">
                    <div class="invalid-feedback">Please enter a valid province or state name.</div>
                </div>
                <div class="form-group">
                    <label for="postal_code">Zip code</label>
                    <input type="text" id="postal_code" name="postal_code" class="form-control" required
                        pattern="^[A-Za-z0-9\s\-]+$" minlength="3" maxlength="15"
                        title="Please enter a valid postal code.">
                    <div class="invalid-feedback">Please enter a valid postal code.</div>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" class="form-control" required minlength="2"
                        maxlength="50" pattern="^[A-Za-z\s]+$" title="Please enter a valid country name.">
                    <div class="invalid-feedback">Please enter a valid country name.</div>
                </div>
                <div class="form-group">
                    <label for="description">Description (optional)</label>
                    <input type="text" id="description" name="description" class="form-control" maxlength="100">
                </div>

                <button type="submit" class="btn btn-primary">Confirm purchase</button>
            </form>
        </div>

        <!-- Detalle del carrito -->
        <div class="col-md-6">
            <h4>Summary of your purchase</h4>
            <ul class="list-group">
                <?php foreach ($items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>)
                        <span>$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
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


<!-- jQuery (Necessary for All JavaScript Plugins) -->
<script src="../js/jquery/jquery-2.2.4.min.js"></script>
<!-- Popper js -->
<script src="../js/popper.min.js"></script>
<!-- Bootstrap js -->
<script src="../js/bootstrap.min.js"></script>
<!-- Plugins js -->
<script src="../js/plugins.js"></script>
<!-- Classy Nav js -->
<script src="../js/classy-nav.min.js"></script>
<!-- Active js -->
<script src="../js/active.js"></script>

<script>
    // Validación personalizada de Bootstrap 4
    (function () {
        'use strict';
        var form = document.getElementById('addressForm');
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    })();

    document.getElementById('autoCompleteBtn').addEventListener('click', function (e) {
        e.preventDefault();

        <?php if ($addressData !== null): ?>
            const address = <?= json_encode($addressData) ?>;
            document.getElementById('street').value = address.street;
            document.getElementById('number').value = address.number;
            document.getElementById('city').value = address.city;
            document.getElementById('state').value = address.state;
            document.getElementById('postal_code').value = address.postalCode;
            document.getElementById('country').value = address.country;
            document.getElementById('description').value = address.description ?? '';
        <?php else: ?>
            alert('You have no saved data to autocomplete the address.');
        <?php endif; ?>
    });
</script>