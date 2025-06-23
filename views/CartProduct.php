<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/connection.php';
require_once '../models/Product.php';
require_once '../models/Cart.php';
require_once '../models/CartItem.php';

// Manejo de eliminación de item - debe ir antes de enviar cualquier salida
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item_id'])) {
    $removeId = (int)$_POST['remove_item_id'];
    $stmtDel = $conn->prepare("DELETE FROM cart_item WHERE id = :id");
    $stmtDel->execute([':id' => $removeId]);

    // Refrescar la página para evitar repost y actualizar vista
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

include '../components/header.php';

if (!isset($_SESSION['cart_id'])) {
    echo "<p>Your cart is empty.</p>";
    include '../components/footer.php';
    exit;
}

$cartId = $_SESSION['cart_id'];

// Obtener todos los ítems del carrito con info del producto
$stmt = $conn->prepare("
    SELECT ci.id as cart_item_id, ci.quantity, p.id as product_id, p.name, p.price, p.img
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
                    <td><img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 60px;"></td>
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
                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                    <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Botón de Comprar -->
        <form action="checkout.php" method="post" class="mt-3 text-right">
            <button type="submit" class="btn btn-success">Comprar</button>
        </form>
    <?php endif; ?>
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
