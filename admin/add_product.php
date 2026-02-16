<?php
require_once '../controllers/require_admin.php';

require_once '../config/bootstrap.php';

require_once '../models/Product.php';
require_once '../models/Category.php';

$success = '';
$error = '';
$allCategories = Category::getAll($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Crear instancia del producto con los datos del formulario
        $product = new Product(
            0,
            $_POST['name'],
            $_POST['brand'],
            (float) $_POST['price'],
            $_POST['old_price'] !== '' ? (float) $_POST['old_price'] : null,
            $_POST['discount'] ?: null,
            (int) $_POST['category_id'],
            $_POST['img'] ?: 'img/product-img/default.jpg',
            $_POST['img_hover'] ?: 'img/product-img/default-hover.jpg'
        );


        // Preparar la consulta con los datos del objeto
        $stmt = $conn->prepare("INSERT INTO product 
            (name, brand, price, old_price, discount, category_id, img, img_hover)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");


        $stmt->execute([
            $product->getName(),
            $product->getBrand(),
            $product->getPrice(),
            $product->getOldPrice(),
            $product->getDiscount(),
            $product->getCategoryId(),
            $product->getImage(),
            $product->getImageHover()
        ]);

        $success = "Product added successfully.";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Add Product</h1>
    <a href="products.php" class="back-btn">← Back to List</a>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="form">
        <label>Product Name:</label>
        <input type="text" name="name" required>

        <label>Brand:</label>
        <input type="text" name="brand" required>

        <label>Price:</label>
        <input type="number" name="price" step="0.01" required>

        <label>Old Price (optional):</label>
        <input type="number" name="old_price" step="0.01">

        <label>Discount (optional):</label>
        <input type="text" name="discount">

        <label>Category:</label>
        <select name="category_id" required>
            <?php foreach ($allCategories as $category): ?>
                <option value="<?= $category->getId() ?>">
                    <?= $category->getName() ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Image URL:</label>
        <input type="text" name="img" value="../img/product-img/default.jpg" required>

        <label>Hover Image URL:</label>
        <input type="text" name="img_hover" value="../img/product-img/default-hover.jpg" required>

        <button type="submit">Save</button>
    </form>
</body>
</html>
