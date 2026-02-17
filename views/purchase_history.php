<?php
require_once '../controllers/purchaseHistoryController.php';
include '../components/header.php';
?>

<div class="container mt-5 mb-5">
    <h2>Purchase history</h2>

    <?php if (empty($ordersData)): ?>
        <p>You have no registered purchases.</p>
    <?php else: ?>
        <?php foreach ($ordersData as $orderData): ?>
            <?php
                $order = $orderData['order'];
                $status = $order->getStatus();
                $badgeClass = ($status === 'Completed') ? 'bg-success' : 'bg-secondary';
            ?>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Order #<?= $order->getId() ?></strong>
                        - <?= $order->getOrderDate() ?>
                    </div>
                    <div>
                        <span class="badge <?= $badgeClass ?>">
                            <?= htmlspecialchars($status) ?>
                        </span>

                        <?php if ($status !== 'Completed'): ?>
                            <a
                                href="<?= BASE_URL ?>views/pay.php?order_id=<?= $order->getId() ?>"
                                class="btn btn-warning btn-sm ms-2 btn-no-hover"
                            >
                                Pay
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">
                    <p>
                        <strong>Total:</strong>
                        $<?= number_format($order->getTotal(), 2) ?>
                    </p>

                    <h6>Products:</h6>

                    <ul class="list-group">
                        <?php foreach ($orderData['details'] as $item): ?>
                            <?php
                                $detail = $item['detail'];
                                $product = $item['product'];
                                $review = $item['review'];
                            ?>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>
                                        <?= htmlspecialchars($product['name'] ?? 'Producto eliminado') ?>
                                    </strong><br>

                                    Amount: <?= $detail->getQuantity() ?>
                                    - Unit price:
                                    $<?= number_format($detail->getUnitPrice(), 2) ?>

                                    <?php if ($status === 'Completed'): ?>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                                data-bs-target="#reviewModal_<?= $detail->getProductId() ?>">
                                                <?= $review ? 'Edit Review' : 'Leave Review' ?>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($product['img'])): ?>
                                    <img src="<?= BASE_URL . htmlspecialchars($product['img']) ?>"  alt="img" style="width: 60px;">
                                <?php endif; ?>
                            </li>

                            <?php if ($status === 'Completed'): ?>
                                <!-- Review Modal -->
                                <div class="modal fade" id="reviewModal_<?= $detail->getProductId() ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="<?= BASE_URL ?>controllers/save_review.php">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <?= $review ? 'Edit your review' : 'Leave a review' ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <input type="hidden" name="product_id" value="<?= $detail->getProductId() ?>">

                                                    <div class="mb-3">
                                                        <label class="form-label">Rating</label>
                                                        <select name="rating" class="form-select" required>
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <option value="<?= $i ?>"
                                                                    <?= ($review && $review->getRating() === $i) ? 'selected' : '' ?>>
                                                                    <?= $i ?> ⭐
                                                                </option>
                                                            <?php endfor; ?>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Comment</label>
                                                        <textarea
                                                            name="comment"
                                                            class="form-control"
                                                            rows="3"
                                                            required
                                                        ><?= $review ? htmlspecialchars($review->getComment()) : '' ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">
                                                        <?= $review ? 'Save Changes' : 'Submit Review' ?>
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

