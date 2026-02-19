<?php
require_once '../middlewares/requireAuth.php';
require_once '../middlewares/requireAdmin.php';

require_once '../config/bootstrap.php';


$id = $_GET['id'] ?? null;
if (!$id) {
    die("Missing user ID.");
}

try {
    $conn->prepare("DELETE FROM user WHERE id = :id")->execute([':id' => $id]);
    header("Location: users.php?deleted=1");
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>