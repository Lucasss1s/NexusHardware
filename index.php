<?php 
require_once 'config/bootstrap.php';
include 'components/header.php'; 
?>

    <!-- ##### Welcome Area Start ##### -->
    <section class="welcome_area bg-img background-overlay" style="background-image: url(<?= BASE_URL ?>img/bg-img/bg1.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="hero-content">
                        <h2>Featured Products</h2>
                        <a href="<?= BASE_URL ?>views/shop.php" class="btn essence-btn">view Products</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Welcome Area End ##### -->

    <!-- ##### Top Catagory Area Start ##### -->
    <div class="top_catagory_area section-padding-80 clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="single_catagory_area d-flex align-items-center justify-content-center bg-img" style="background-image: url(<?= BASE_URL ?>img/bg-img/bg2.png);">
                        <div class="catagory-content">
                            <a href="<?= BASE_URL ?>views/shop.php">Processors</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="single_catagory_area d-flex align-items-center justify-content-center bg-img" style="background-image: url(<?= BASE_URL ?>img/bg-img/bg3.png);">
                        <div class="catagory-content">
                            <a href="<?= BASE_URL ?>views/shop.php">Graphics</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="single_catagory_area d-flex align-items-center justify-content-center bg-img" style="background-image: url(<?= BASE_URL ?>img/bg-img/bg4.png);">
                        <div class="catagory-content">
                            <a href="<?= BASE_URL ?>views/shop.php">Motherboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Top Catagory Area End ##### -->

    <!-- ##### CTA Area Start ##### -->
    <section class="welcome_area bg-img background-overlay" style="background-image: url(<?= BASE_URL ?>img/bg-img/banner2.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="hero-content">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### CTA Area End ##### -->

    <!-- ##### Info Section Start ##### -->
<section class="info-section py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="info-box">
                    <i class="fa fa-credit-card fa-2x mb-3"></i>
                    <h5>Up to 18 installments</h5>
                    <p>Paying with credit cards</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <i class="fa fa-truck fa-2x mb-3"></i>
                    <h5>Shipping nationwide</h5>
                    <p>Through OCA</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <i class="fa fa-shield fa-2x mb-3"></i>
                    <h5>Official guarantee</h5>
                    <p>Dand up to 36 months on all products</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ##### Info Section End ##### -->


    <!-- ##### New Arrivals Area Start ##### -->
    <section class="new_arrivals_area section-padding-80 clearfix">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center">
                        <h2>Popular Products</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="popular-products-slides owl-carousel">

                        <!-- Single Product -->
                        <div class="single-product-wrapper">
                            <!-- Product Image -->
                            <div class="product-img">
                                <img src="<?= BASE_URL ?>img/product-img/motherboard-asus-b550m-a-wifi-am4.jpg" alt=""
                                style="width: 100%; height: 300px; object-fit: contain; background-color: #fff;">
                                <!-- Hover Thumb -->
                                <img class="hover-img" src="<?= BASE_URL ?>img/product-img/motherboard-asus-b550m-a-wifi-am4.jpg" alt=""
                                style="width: 100%; height: 300px; object-fit: contain; background-color: #fff;">
                                <!-- Favourite -->
                                <div class="product-favourite">
                                    <a href="#" class="favme fa fa-heart"></a>
                                </div>
                            </div>
                            <!-- Product Description -->
                            <div class="product-description">
                                <span>Asus</span>
                                <a href="<?= BASE_URL ?>views/shop.php">
                                    <h6>Mother ASUS PRIME B550M-A AC WIFI AM4</h6>
                                </a>
                                <p class="product-price">$128.35</p>
                            </div>
                        </div>

                        <!-- Single Product -->
                        <div class="single-product-wrapper">
                            <!-- Product Image -->
                            <div class="product-img">
                                <img src="<?= BASE_URL ?>img/product-img/silla-gamer.jpg" alt=""
                                style="width: 100%; height: 300px; object-fit: contain; background-color: #fff;">
                                <!-- Hover Thumb -->
                                <img class="hover-img" src="<?= BASE_URL ?>img/product-img/silla-gamer.jpg" alt=""
                                style="width: 100%; height: 300px; object-fit: contain; background-color: #fff;">
                                <!-- Favourite -->
                                <div class="product-favourite">
                                    <a href="#" class="favme fa fa-heart"></a>
                                </div>
                            </div>
                            <!-- Product Description -->
                            <div class="product-description">
                                <span>Vertagear</span>
                                <a href="<?= BASE_URL ?>views/shop.php">
                                    <h6>Silla Gamer Vertagear SL3800 HygennX Negro y Blanco Ergonomic (Peso MAX. 100kg)</h6>
                                </a>
                                <p class="product-price">$419.99</p>
                            </div>
                        </div>

                        <!-- Single Product -->
                        <div class="single-product-wrapper">
                            <!-- Product Image -->
                            <div class="product-img">
                                <img src="<?= BASE_URL ?>img/product-img/microfono-redragon-fenris-gm301-rgb.jpg" alt=""
                                style="width: 100%; height: 300px; object-fit: contain; background-color: #fff;">
                                <!-- Hover Thumb -->
                                <img class="hover-img" src="<?= BASE_URL ?>img/product-img/microfono-redragon-fenris-gm301-rgb.jpg" alt=""
                                style="width: 100%; height: 300px; object-fit: contain; background-color: #fff;">
                                <!-- Favourite -->
                                <div class="product-favourite">
                                    <a href="#" class="favme fa fa-heart"></a>
                                </div>
                            </div>
                            <!-- Product Description -->
                            <div class="product-description">
                                <span>Redragon</span>
                                <a href="<?= BASE_URL ?>views/shop.php">
                                    <h6>Microfono Redragon Fenris GM301 RGB</h6>
                                </a>
                                <p class="product-price">$87.65</p>
                            </div>
                        </div>

                        <!-- Single Product -->
                        <div class="single-product-wrapper">
                            <!-- Product Image -->
                            <div class="product-img">
                                <img src="<?= BASE_URL ?>img/product-img/mouse-logitech-g305-wireless.jpg" alt="" 
                                style="width: 100%; height: 300px; object-fit: contain; background-color: #fff;">
                                <!-- Hover Thumb -->
                                <img class="hover-img" src="<?= BASE_URL ?>img/product-img/mouse-logitech-g305-wireless.jpg" alt=""
                                style="width: 100%; height: 300px; object-fit: contain; background-color: #fff;">
                                <!-- Favourite -->
                                <div class="product-favourite">
                                    <a href="#" class="favme fa fa-heart"></a>
                                </div>
                            </div>
                            <!-- Product Description -->
                            <div class="product-description">
                                <span>Logitech</span>
                                <a href="<?= BASE_URL ?>views/shop.php">
                                    <h6>Mouse Logitech G305 Wireless 2.4Ghz Lightspeed Hero Black 99g 250Hs</h6>
                                </a>
                                <p class="product-price">$39.65</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### New Arrivals Area End ##### -->

    <!-- ##### Brands Area Start ##### -->
    <div class="brands-area d-flex align-items-center justify-content-between">
        <div class="single-brands-logo">
            <img src="<?= BASE_URL ?>img/core-img/intelLogo.png" alt="">
        </div>
        <div class="single-brands-logo">
            <img src="<?= BASE_URL ?>img/core-img/logoMsi.png" alt="">
        </div>
        <div class="single-brands-logo">
            <img src="<?= BASE_URL ?>img/core-img/asusLogo.png" alt="">
        </div>
        <div class="single-brands-logo">
            <img src="<?= BASE_URL ?>img/core-img/AMDlogo.png" alt="">
        </div>
        <div class="single-brands-logo">
            <img src="<?= BASE_URL ?>img/core-img/nvidiaLogo.png" alt="">
        </div>
        <div class="single-brands-logo">
            <img src="<?= BASE_URL ?>img/core-img/logitechLogo.png" alt="">
        </div>
    </div>
    <!-- ##### Brands Area End ##### -->

<?php include 'components/footer.php'; ?>

</body>

</html>
