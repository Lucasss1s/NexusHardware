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

// Obtener direcciones existentes del usuario
$addresses = Address::getByUserId($conn, $userId);

// Calcular total
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
            <form action="/nexushardware/controllers/confirm_order.php" method="post">
                <div class="form-group">
                    <label>Calle</label>
                    <input type="text" name="street" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Número</label>
                    <input type="number" name="number" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Ciudad</label>
                    <input type="text" name="city" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Provincia/Estado</label>
                    <input type="text" name="state" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Código Postal</label>
                    <input type="text" name="postal_code" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>País</label>
                    <input type="text" name="country" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Descripción (opcional)</label>
                    <input type="text" name="description" class="form-control">
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

<?php include 'components/footer.php'; ?>


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

