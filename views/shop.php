<?php
session_start();

require_once '../config/connection.php';
require_once '../models/Product.php';
require_once '../models/Category.php';
require_once '../models/Cart.php';
require_once '../models/CartItem.php';

// Mover el include HEADER abajo, después de procesar POST

// Manejar el agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    if (!isset($_SESSION['cart_id'])) {
        $cart = Cart::create($conn, null);
        $_SESSION['cart_id'] = $cart->getId();
    } else {
        $cart = Cart::getById($_SESSION['cart_id'], $conn);
    }

    $productId = (int)$_POST['product_id'];
    CartItem::addToCart($conn, $cart->getId(), $productId, 1);

    // Sin header redirect para quedarse en la misma página
    // Solo evitar que el formulario se reenvíe (podés hacer redirect a la misma url)
}

include '../components/header.php';
?>


<!-- ##### Breadcumb Area Start ##### -->
<div class="breadcumb_area bg-img" style="background-image: url(../img/bg-img/breadcumb.jpg);">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <div class="page-title text-center">
                    <h2>dresses</h2>
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
                        <?php
                        $categories = Category::getAll($conn);
                        ?>
                        <div class="catagories-menu">
                            <ul id="menu-content2" class="menu-content collapse show">
                                <!-- Single Item -->
                                <li data-toggle="collapse" data-target="#hardware">
                                    <a href="#">Hardware</a>
                                    <ul class="sub-menu collapse show" id="hardware">
                                        <li><a href="shop.php">All</a></li>
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

                    <!-- ##### Single Widget ##### -->
                    <div class="widget price mb-50">
                        <!-- Widget Title -->
                        <h6 class="widget-title mb-30">Filter by</h6>
                        <!-- Widget Title 2 -->
                        <p class="widget-title2 mb-30">Price</p>

                        <div class="widget-desc">
                            <div class="slider-range">
                                <div data-min="49" data-max="360" data-unit="$" class="slider-range-price ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" data-value-min="49" data-value-max="360" data-label-result="Range:">
                                    <div class="ui-slider-range ui-widget-header ui-corner-all"></div>
                                    <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                                    <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                                </div>
                                <div class="range-price">Range: $49.00 - $360.00</div>
                            </div>
                        </div>
                    </div>

                    <!-- ##### Single Widget ##### -->
                    <div class="widget color mb-50">
                        <!-- Widget Title 2 -->
                        <p class="widget-title2 mb-30">Color</p>
                        <div class="widget-desc">
                            <ul class="d-flex">
                                <li><a href="#" class="color1"></a></li>
                                <li><a href="#" class="color2"></a></li>
                                <li><a href="#" class="color3"></a></li>
                                <li><a href="#" class="color4"></a></li>
                                <li><a href="#" class="color5"></a></li>
                                <li><a href="#" class="color6"></a></li>
                                <li><a href="#" class="color7"></a></li>
                                <li><a href="#" class="color8"></a></li>
                                <li><a href="#" class="color9"></a></li>
                                <li><a href="#" class="color10"></a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- ##### Single Widget ##### -->
                    <div class="widget brands mb-50">
                        <!-- Widget Title 2 -->
                        <p class="widget-title2 mb-30">Brands</p>
                        <div class="widget-desc">
                            <ul>
                                <li><a href="#">Asos</a></li>
                                <li><a href="#">Mango</a></li>
                                <li><a href="#">River Island</a></li>
                                <li><a href="#">Topshop</a></li>
                                <li><a href="#">Zara</a></li>
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
                                    <p><span>186</span> products found</p>
                                </div>
                                <!-- Sorting -->
                                <div class="product-sorting d-flex">
                                    <p>Sort by:</p>
                                    <form action="#" method="get">
                                        <select name="select" id="sortByselect">
                                            <option value="value">Highest Rated</option>
                                            <option value="value">Newest</option>
                                            <option value="value">Price: $$ - $</option>
                                            <option value="value">Price: $ - $$</option>
                                        </select>
                                        <input type="submit" class="d-none" value="">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php           
                    $currentCategoryName = $_GET['category'] ?? null;
                    $filteredProducts = [];

                    try {
                        if ($currentCategoryName) {
                            // Buscar la categoría por nombre
                            $category = Category::getByName($currentCategoryName, $conn);

                            if ($category) {
                                $categoryId = $category->getId();

                                // Obtener productos de esa categoría
                                $stmt = $conn->prepare("SELECT * FROM product WHERE category_id = :cat_id");
                                $stmt->execute([':cat_id' => $categoryId]);
                            } else {
                                // Categoría no encontrada
                                $stmt = $conn->query("SELECT * FROM product WHERE 0"); // devuelve vacío
                            }
                        } else {
                            // Sin filtro, obtener todos los productos
                            $stmt = $conn->query("SELECT * FROM product");
                        }

                        $filteredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        echo "Error al obtener productos: " . $e->getMessage();
                        $filteredProducts = [];
                    }
                    ?>


                    <div class="row">
                        <?php                  
                        foreach ($filteredProducts  as $p): ?>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="single-product-wrapper">
                                    <!-- Product Image -->
                                    <div class="product-img">
                                        <a href="single_product.php?id=<?= $p['id'] ?>">
                                            <img src="<?= $p['img'] ?>" alt="">
                                            <img class="hover-img" src="<?= $p['img_hover'] ?>" alt="">
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
                                        <a href="single_product.php?id=<?= $p['id'] ?>">
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
                                                    <a href="/nexushardware/views/login_register.php" class="btn essence-btn">Add to Cart</a>
                                                <?php else: ?>
                                                    <form method="post" action="shop.php<?= $currentCategoryName ? '?category=' . urlencode($currentCategoryName) : '' ?>">
                                                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                                        <button type="submit" name="add_to_cart" class="btn essence-btn">Add to Cart</button>
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
                <!-- Pagination -->
                <nav aria-label="navigation">
                    <ul class="pagination mt-50 mb-70">
                        <li class="page-item"><a class="page-link" href="#"><i class="fa fa-angle-left"></i></a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">...</a></li>
                        <li class="page-item"><a class="page-link" href="#">21</a></li>
                        <li class="page-item"><a class="page-link" href="#"><i class="fa fa-angle-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- ##### Shop Grid Area End ##### -->

<?php include '../components/footer.php'; ?>

<!-- jQuery (Necessary for All JavaScript Plugins) -->
<script src="../js/jquery/jquery-2.2.4.min.js"></script>
<!-- Popper js -->
<script src="../js/popper.min.js"></script>
<!-- Bootstrap js -->
<script src="../js/bootstrap.min.js"></script>
<!-- Plugins js -->
<script src="../js/plugins.js"></script>
<!-- Classy Nav js -->
<script src="../js/classy-nav.min.js"></script>
<!-- Active js -->
<script src="../js/active.js"></script>

</body>

</html>
