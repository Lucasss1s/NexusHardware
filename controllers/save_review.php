<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/Database.php';
require_once '../models/Review.php';

$conn = Database::getInstance();

$productId = $_POST['product_id'] ?? null;
$userId = $_POST['user_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$comment = $_POST['comment'] ?? null;

if (!$productId || !$userId || !$rating || !$comment) {
    header("Location: ../views/purchase_history.php?error=Missing fields");
    exit;
}

// Verificar si ya existe una reseña
$stmt = $conn->prepare("SELECT id FROM review WHERE product_id = :product AND user_id = :user LIMIT 1");
$stmt->execute([
    ':product' => $productId,
    ':user' => $userId
]);

if ($existing = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Update
    $update = $conn->prepare("UPDATE review SET rating = :rating, comment = :comment, created_at = NOW() WHERE id = :id");
    $update->execute([
        ':rating' => $rating,
        ':comment' => $comment,
        ':id' => $existing['id']
    ]);
} else {
    // Insert
    $review = new Review(0, (int)$productId, (int)$userId, (int)$rating, $comment, date('Y-m-d H:i:s'));
    $review->save($conn);
}

header("Location: ../views/purchase_history.php?success=1");
exit;
