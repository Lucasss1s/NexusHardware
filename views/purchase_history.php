<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/connection.php';
require_once '../models/Order.php';
require_once '../models/OrderDetail.php';
require_once '../models/Product.php';

if (!isset($_SESSION['user'])) {
    header("Location: login_register.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$orders = Order::getByUserId($conn, $userId);

include '../components/header.php';
?>

<div class="container mt-5 mb-5">
    <h2>Historial de Compras</h2>

    <?php if (empty($orders)): ?>
        <p>No tenés compras registradas.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <?php
                $status = $order->getStatus();
                $badgeClass = ($status === 'Completed') ? 'bg-success' : 'bg-secondary';
            ?>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Orden #<?= $order->getId() ?></strong> - <?= $order->getOrderDate() ?>
                    </div>
                    <div>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                        <?php if ($status !== 'Completed'): ?>
                            <a href="pay.php?order_id=<?= $order->getId() ?>" class="btn btn-warning btn-sm ms-2 btn-no-hover">Pagar</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <p><strong>Total:</strong> $<?= number_format($order->getTotal(), 2) ?></p>
                    <h6>Productos:</h6>
                    <ul class="list-group">
                        <?php
                        $details = OrderDetail::getByOrderId($conn, $order->getId());
                        foreach ($details as $detail):
                            $stmt = $conn->prepare("SELECT name, img FROM product WHERE id = :id");
                            $stmt->execute([':id' => $detail->getProductId()]);
                            $product = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= htmlspecialchars($product['name'] ?? 'Producto eliminado') ?></strong><br>
                                    Cantidad: <?= $detail->getQuantity() ?> - Precio unitario: $<?= number_format($detail->getUnitPrice(), 2) ?>
                                </div>
                                <?php if (!empty($product['img'])): ?>
                                    <img src="<?= htmlspecialchars($product['img']) ?>" alt="img" style="width: 60px;">
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>

<!-- Scripts -->
<script src="../js/jquery/jquery-2.2.4.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/plugins.js"></script>
<script src="../js/classy-nav.min.js"></script>
<script src="../js/active.js"></script>
