<?php
require_once '../config/connection.php';
require_once '../models/Product.php';
require_once '../models/Category.php';

try {
    $stmt = $conn->query("SELECT * FROM product ORDER BY id DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $products = [];
    foreach ($rows as $row) {
        $products[] = new Product(
            (int) $row['id'],
            $row['name'],
            $row['brand'],
            (float) $row['price'],
            $row['old_price'] !== null ? (float) $row['old_price'] : null,
            $row['discount'] ?? null,
            (int) $row['category_id'],
            $row['img'],
            $row['img_hover']
        );
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Product List</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Product Management</h1>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="success">Product deleted successfully.</div>
    <?php endif; ?>

    <a href="add_product.php" class="add-btn">+ Add Product</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Old Price</th>
                <th>Discount</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <?php
                    $category = Category::getById($product->getCategoryId(), $conn);
                    $categoryName = $category ? $category->getName() : '-';
                ?>
                <tr>
                    <td><?= $product->getId() ?></td>
                    <td><img src="<?= $product->getImage() ?>" width="60"></td>
                    <td><?= $product->getName() ?></td>
                    <td><?= $product->getBrand() ?></td>
                    <td>$<?= $product->getPrice() ?></td>
                    <td><?= $product->getOldPrice() ? "$" . $product->getOldPrice() : '-' ?></td>
                    <td><?= $product->getDiscount() ?: '-' ?></td>
                    <td><?= $categoryName?></td>
                    <td class="actions">
                        <a href="edit_product.php?id=<?= $product->getId() ?>" class="edit-btn">Edit</a>
                        <a href="delete_product.php?id=<?= $product->getId() ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>
