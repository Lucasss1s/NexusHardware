<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/Database.php';
$conn = Database::getInstance();

require_once '../models/Order.php';
require_once '../models/OrderDetail.php';
require_once '../models/Product.php';
require_once '../models/Review.php';

if (!isset($_SESSION['user'])) {
    header("Location: login_register.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$orders = Order::getByUserId($conn, $userId);

include '../components/header.php';
?>

<div class="container mt-5 mb-5">
    <h2>Purchase history</h2>

    <?php if (empty($orders)): ?>
        <p>You have no registered purchases.</p>
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
                    <h6>Products:</h6>
                    <ul class="list-group">
                        <?php
                        $details = OrderDetail::getByOrderId($conn, $order->getId());
                        foreach ($details as $detail):
                            $stmt = $conn->prepare("SELECT name, img FROM product WHERE id = :id");
                            $stmt->execute([':id' => $detail->getProductId()]);
                            $product = $stmt->fetch(PDO::FETCH_ASSOC);

                            $existingReview = null;
                            if ($status === 'Completed') {
                                $reviewStmt = $conn->prepare("SELECT * FROM review WHERE user_id = :user AND product_id = :product LIMIT 1");
                                $reviewStmt->execute([
                                    ':user' => $userId,
                                    ':product' => $detail->getProductId()
                                ]);
                                $row = $reviewStmt->fetch(PDO::FETCH_ASSOC);
                                if ($row) {
                                    $existingReview = new Review(
                                        $row['id'],
                                        $row['product_id'],
                                        $row['user_id'],
                                        $row['rating'],
                                        $row['comment'],
                                        $row['created_at']
                                    );
                                }
                            }
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= htmlspecialchars($product['name'] ?? 'Producto eliminado') ?></strong><br>
                                    Amount: <?= $detail->getQuantity() ?> - Unit price: $<?= number_format($detail->getUnitPrice(), 2) ?>
                                    <?php if ($status === 'Completed'): ?>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reviewModal_<?= $detail->getProductId() ?>">
                                                <?= $existingReview ? 'Edit Review' : 'Leave Review' ?>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($product['img'])): ?>
                                    <img src="<?= htmlspecialchars($product['img']) ?>" alt="img" style="width: 60px;">
                                <?php endif; ?>
                            </li>

                            <!-- Modal para reseñar -->
                            <?php if ($status === 'Completed'): ?>
                                <div class="modal fade" id="reviewModal_<?= $detail->getProductId() ?>" tabindex="-1" aria-labelledby="reviewModalLabel_<?= $detail->getProductId() ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="/nexushardware/controllers/save_review.php">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="reviewModalLabel_<?= $detail->getProductId() ?>">
                                                        <?= $existingReview ? 'Edit your review' : 'Leave a review' ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <input type="hidden" name="product_id" value="<?= $detail->getProductId() ?>">
                                                    <input type="hidden" name="user_id" value="<?= $userId ?>">

                                                    <div class="mb-3">
                                                        <label for="rating_<?= $detail->getProductId() ?>" class="form-label">Rating</label>
                                                        <select name="rating" class="form-select" required>
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <option value="<?= $i ?>" <?= ($existingReview && $existingReview->getRating() === $i) ? 'selected' : '' ?>>
                                                                    <?= $i ?> ⭐
                                                                </option>
                                                            <?php endfor; ?>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Comment</label>
                                                        <textarea name="comment" class="form-control" rows="3" required><?= $existingReview ? htmlspecialchars($existingReview->getComment()) : '' ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"><?= $existingReview ? 'Save Changes' : 'Submit Review' ?></button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>

<!-- Scripts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/jquery/jquery-2.2.4.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/plugins.js"></script>
<script src="../js/classy-nav.min.js"></script>
<script src="../js/active.js"></script>
