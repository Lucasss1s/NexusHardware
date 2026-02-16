<?php
require_once '../config/bootstrap.php';

require_once '../models/Product.php';
require_once '../models/Cart.php';
require_once '../models/CartItem.php';

// handle item deletion 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item_id'])) {
    $removeId = (int)$_POST['remove_item_id'];

    $stmtDel = $conn->prepare("DELETE FROM cart_item WHERE id = :id");
    $stmtDel->execute([':id' => $removeId]);

    header("Location: " . BASE_URL . "views/cart_product.php");
    exit;
}

include '../components/header.php';

if (!isset($_SESSION['cart_id'])) {
    echo "<p>Your cart is empty.</p>";
    include '../components/footer.php';
    exit;
}

$cartId = $_SESSION['cart_id'];

// Get all items in the cart with product in
$stmt = $conn->prepare("
    SELECT 
        ci.id AS cart_item_id,
        ci.quantity,
        p.id AS product_id,
        p.name,
        p.price,
        p.img
    FROM cart_item ci
    JOIN product p ON ci.product_id = p.id
    WHERE ci.cart_id = :cart_id
");
$stmt->execute([':cart_id' => $cartId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5 mb-5">
    <h2>Your Cart</h2>

    <?php if (empty($items)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($items as $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>
                        <?php if (!empty($item['img'])): ?>
                            <img src="<?=  BASE_URL . htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 60px;">
                        <?php endif; ?>
                    </td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="remove_item_id" value="<?= $item['cart_item_id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>

                <tr>
                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                    <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>

        <form action="<?= BASE_URL ?>views/checkout.php" method="post" class="mt-3 text-end">
            <button type="submit" class="btn btn-success">Checkout</button>
        </form>
    <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>

<script src="<?= BASE_URL ?>js/jquery/jquery-2.2.4.min.js"></script>
<script src="<?= BASE_URL ?>js/popper.min.js"></script>
<script src="<?= BASE_URL ?>js/bootstrap.min.js"></script>
<script src="<?= BASE_URL ?>js/plugins.js"></script>
<script src="<?= BASE_URL ?>js/classy-nav.min.js"></script>
<script src="<?= BASE_URL ?>js/active.js"></script>
