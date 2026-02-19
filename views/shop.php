<?php
require_once '../controllers/shopController.php';

include '../components/header.php';
?>


<!-- ##### Breadcumb Area Start ##### -->
<div class="breadcumb_area bg-img" style="background-image: url(<?= BASE_URL ?>img/bg-img/breadcumb.jpg);">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <div class="page-title text-center">
                    <h2>SHOP</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ##### Breadcumb Area End ##### -->

<!-- ##### Shop Grid Area Start ##### -->
<section class="shop_grid_area section-padding-80">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3">
                <div class="shop_sidebar_area">

                    <!-- ##### Single Widget ##### -->
                    <div class="widget catagory mb-50">
                        <!-- Widget Title -->
                        <h6 class="widget-title mb-30">Catagories</h6>

                        <!--  Catagories  -->
                        <div class="catagories-menu">
                            <ul id="menu-content2" class="menu-content collapse show">
                                <!-- Single Item -->
                                <li data-toggle="collapse" data-target="#hardware">
                                    <a href="#">Products <i class="fa fa-angle-down"></i></a>
                                    <ul class="sub-menu collapse show" id="hardware">
                                        <li><a href="<?= BASE_URL ?>views/shop.php">All</a></li>
                                        <?php foreach ($categories as $cat): ?>
                                            <li>
                                                <a href="?category=<?= htmlspecialchars($cat->getName()) ?>">
                                                    <?= htmlspecialchars($cat->getName()) ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8 col-lg-9">
                <div class="shop_grid_product_area">
                    <div class="row">
                        <div class="col-12">
                            <div class="product-topbar d-flex align-items-center justify-content-between">
                                <!-- Total Products -->
                                <div class="total-products">
                                    <p><span></span> products found</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $currentCategoryName = $_GET['category'] ?? null;
                    $filteredProducts = [];

                    try {
                        if ($currentCategoryName) {
                            // Search category by name
                            $category = Category::getByName($currentCategoryName, $conn);

                            if ($category) {
                                $categoryId = $category->getId();

                                // Get products from searched category 
                                $stmt = $conn->prepare("SELECT * FROM product WHERE category_id = :cat_id");
                                $stmt->execute([':cat_id' => $categoryId]);
                            } else {
                                // Category not found
                                $stmt = $conn->query("SELECT * FROM product WHERE 0");
                            }
                        } else {
                            // Get all products
                            $stmt = $conn->query("SELECT * FROM product");
                        }

                        $filteredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        echo "Error al obtener productos: " . $e->getMessage();
                        $filteredProducts = [];
                    }
                    ?>


                    <div class="row">
                        <?php foreach ($filteredProducts as $p): ?>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="single-product-wrapper">
                                    <!-- Product Image -->
                                    <div class="product-img">
                                        <a href="<?= BASE_URL ?>views/single_product.php?id=<?= $p['id'] ?>">
                                            <img src="<?= BASE_URL . $p['img'] ?>" alt=""
                                                style="width: 100%; height: 300px; object-fit: contain; background-color: #fff;">
                                            <img class="hover-img" src="<?= BASE_URL . $p['img_hover'] ?>" alt=""
                                            style="width: 100%; height: 300px; object-fit: contain; background-color: #fff;">
                                        </a>
                                        <?php if ($p['discount']): ?>
                                            <div class="product-badge offer-badge">
                                                <span><?= $p['discount'] ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <div class="product-favourite">
                                            <a href="#" class="favme fa fa-heart"></a>
                                        </div>
                                    </div>

                                    <!-- Product Description -->
                                    <div class="product-description">
                                        <span><?= $p['brand'] ?></span>
                                        <a href="<?= BASE_URL ?>views/single_product.php?id=<?= $p['id'] ?>">
                                            <h6><?= $p['name'] ?></h6>
                                        </a>
                                        <p class="product-price">
                                            <?php if ($p['old_price']): ?>
                                                <span class="old-price">$<?= $p['old_price'] ?></span>
                                            <?php endif; ?>
                                            $<?= $p['price'] ?>
                                        </p>

                                        <!-- Hover Content -->
                                        <div class="hover-content">
                                            <div class="add-to-cart-btn">
                                                <?php if (!isset($_SESSION['user'])): ?>
                                                    <a href="<?= BASE_URL ?>views/login_register.php"
                                                        class="btn essence-btn">Add to Cart</a>
                                                <?php else: ?>
                                                    <form method="post"
                                                        action="<?= BASE_URL ?>views/shop.php<?= $currentCategoryName ? '?category=' . urlencode($currentCategoryName) : '' ?>">
                                                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                                        <button type="submit" name="add_to_cart" class="btn essence-btn">Add to
                                                            Cart</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>


                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<!-- ##### Shop Grid Area End ##### -->

<?php include '../components/footer.php'; ?>
