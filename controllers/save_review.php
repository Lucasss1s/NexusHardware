<?php
require_once '../config/bootstrap.php';

require_once '../models/Review.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/purchase_history.php?error=Please login to continue");
    exit;
}

if (!isset($_SESSION['user']['id'])) {
    header("Location: " . BASE_URL . "views/login_register.php");
    exit;
}

$conn = Database::getInstance();

$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
$rating    = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
$comment   = isset($_POST['comment']) ? trim($_POST['comment']) : null;

$userId = (int) $_SESSION['user']['id'];

if (!$productId || !$rating || !$comment) {
    header("Location: " . BASE_URL . "views/purchase_history.php?error=Missing fields");
    exit;
}

if ($rating < 1 || $rating > 5) {
    header("Location: " . BASE_URL . "views/purchase_history.php?error=Invalid rating");
    exit;
}

$stmt = $conn->prepare("
    SELECT id 
    FROM review 
    WHERE product_id = :product AND user_id = :user 
    LIMIT 1
");
$stmt->execute([
    ':product' => $productId,
    ':user'    => $userId
]);

$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    // Update
    $update = $conn->prepare("
        UPDATE review 
        SET rating = :rating,
            comment = :comment,
            created_at = NOW()
        WHERE id = :id
    ");
    $update->execute([
        ':rating'  => $rating,
        ':comment' => $comment,
        ':id'      => $existing['id']
    ]);
} else {
    // Insert
    $review = new Review(
        0,
        $productId,
        $userId,
        $rating,
        $comment,
        date('Y-m-d H:i:s')
    );
    $review->save($conn);
}

header("Location: " . BASE_URL . "views/purchase_history.php?success=1");
exit;
