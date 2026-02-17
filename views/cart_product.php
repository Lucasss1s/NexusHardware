<?php
require_once '../controllers/cartController.php';

CartController::handleRemove($conn);
$data = CartController::getCartData($conn);

$items = $data['items'];
$total = $data['total'];

include '../components/header.php';
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
                <?php foreach ($items as $item): ?>
                    <?php $subtotal = $item['price'] * $item['quantity']; ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>
                            <?php if (!empty($item['img'])): ?>
                                <img src="<?= BASE_URL . htmlspecialchars($item['img']) ?>" width="60">
                            <?php endif; ?>
                        </td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($subtotal, 2) ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="remove_item_id" value="<?= $item['id'] ?>">
                                <button class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr>
                    <td colspan="4" class="text-end"><strong>Total</strong></td>
                    <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>

        <form action="<?= BASE_URL ?>views/checkout.php" method="post" class="text-end">
            <button class="btn btn-success">Checkout</button>
        </form>
    <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>
