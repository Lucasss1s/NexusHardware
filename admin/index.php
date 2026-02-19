<?php 
require_once '../middlewares/requireAuth.php';
require_once '../middlewares/requireAdmin.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Admin Dashboard</h1>

    <div class="panel-options">
        <a href="products.php" class="option-btn">🛍 Manage Products</a>
        <a href="users.php" class="option-btn">👤 Manage Users</a>

    </div>
</body>
</html>
