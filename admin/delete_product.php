<?php
require_once '../config/connection.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Missing product ID.");
}

try {
    $stmt = $conn->prepare("DELETE FROM producto WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: products.php?deleted=1");
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
