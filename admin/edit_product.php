<?php
require_once '../middlewares/requireAuth.php';
require_once '../middlewares/requireAdmin.php';

require_once '../config/bootstrap.php';

require_once '../models/Product.php';
require_once '../models/Category.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Product ID is missing.");
}

$success = '';
$error = '';
$allCategories = Category::getAll($conn);

try {
    $product = Product::getById((int)$id, $conn);
    if (!$product) {
        die("Product not found.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product->setName($_POST['name']);
        $product->setBrand($_POST['brand']);
        $product->setPrice((float)$_POST['price']);
        $product->setOldPrice($_POST['old_price'] !== '' ? (float)$_POST['old_price'] : null);
        $product->setDiscount($_POST['discount'] ?: null);
        $product->setCategoryId((int)$_POST['category_id']);
        $product->setImage($_POST['img'] ?: 'img/product-img/default.jpg');
        $product->setImageHover($_POST['img_hover'] ?: 'img/product-img/default-hover.jpg');

        if ($product->update($conn)) {
            $success = "Product updated successfully.";
        } else {
            $error = "Failed to update product.";
        }
    }
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Edit Product</h1>
    <a href="products.php" class="back-btn">← Back to List</a>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="form">
        <label>Product Name:</label>
        <input type="text" name="name" value="<?=$product->getName() ?>" required>

        <label>Brand:</label>
        <input type="text" name="brand" value="<?= $product->getBrand() ?>" required>

        <label>Price:</label>
        <input type="number" name="price" step="0.01" value="<?= $product->getPrice() ?>" required>

        <label>Old Price (optional):</label>
        <input type="number" name="old_price" step="0.01" value="<?= $product->getOldPrice() ?>">

        <label>Discount (optional):</label>
        <input type="text" name="discount" value="<?= $product->getDiscount() ?>">

        <label>Category:</label>
        <select name="category_id" required>
            <?php foreach ($allCategories as $category): ?>
                <option value="<?= $category->getId() ?>" 
                    <?= $category->getId() === $product->getCategoryId() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category->getName()) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Image URL:</label>
        <input type="text" name="img" value="<?=$product->getImage() ?>">

        <label>Hover Image URL:</label>
        <input type="text" name="img_hover" value="<?=$product->getImageHover() ?>">

        <button type="submit">Update</button>
    </form>

</body>
</html>
