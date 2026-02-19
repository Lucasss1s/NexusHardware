<?php
require_once __DIR__ . '/../config/config.php';
?>

    <footer class="footer_area clearfix">
        <div class="container">
            <div class="row">
                <!-- Logo + Menu -->
                <div class="col-12 col-md-6">
                    <div class="single_widget_area d-flex mb-30">
                        <!-- Logo -->
                        <div class="footer-logo mr-50">
                            <a href="<?= BASE_URL ?>">
                                <img src="<?= BASE_URL ?>img/core-img/logoPrincipal.png" alt="NexusHardware Logo">
                            </a>
                        </div>

                        <!-- Footer Menu -->
                        <div class="footer_menu">
                            <ul>
                                <li><a href="<?= BASE_URL ?>views/shop.php">Shop</a></li>
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Info Links -->
                <div class="col-12 col-md-6">
                    <div class="single_widget_area mb-30">
                        <ul class="footer_widget_menu">
                            <li><a href="#">Order Status</a></li>
                            <li><a href="#">Payment Options</a></li>
                            <li><a href="#">Shipping and Delivery</a></li>
                            <li><a href="#">Guides</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Use</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row align-items-end">
                <!-- Subscribe -->
                <div class="col-12 col-md-6">
                    <div class="single_widget_area">
                        <div class="footer_heading mb-30">
                            <h6>Subscribe</h6>
                        </div>

                        <div class="subscribtion_form">
                            <form action="#" method="post">
                                <input type="email" name="mail" class="mail" placeholder="Your email here" required>
                                <button type="submit" class="submit">
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Social -->
                <div class="col-12 col-md-6">
                    <div class="single_widget_area">
                        <div class="footer_social_area">
                            <a href="#" data-toggle="tooltip" title="Facebook">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </a>
                            <a href="#" data-toggle="tooltip" title="Instagram">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>
                            <a href="#" data-toggle="tooltip" title="Twitter">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>
                            <a href="#" data-toggle="tooltip" title="Pinterest">
                                <i class="fa fa-pinterest" aria-hidden="true"></i>
                            </a>
                            <a href="#" data-toggle="tooltip" title="Youtube">
                                <i class="fa fa-youtube-play" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="row mt-5">
                <div class="col-md-12 text-center">
                    <p>
                        &copy; <?= date('Y') ?> All rights reserved |
                        Made with <i class="fa fa-heart-o" aria-hidden="true"></i> by
                        <a href="https://colorlib.com" target="_blank" rel="noopener">NexusHardware</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

<!-- Vendor JS -->
<script src="<?= BASE_URL ?>js/vendor/jquery/jquery-2.2.4.min.js"></script>
<script src="<?= BASE_URL ?>js/vendor/popper.min.js"></script>
<script src="<?= BASE_URL ?>js/vendor/bootstrap.min.js"></script>
<script src="<?= BASE_URL ?>js/vendor/plugins.js"></script>
<script src="<?= BASE_URL ?>js/vendor/classy-nav.min.js"></script>
<script src="<?= BASE_URL ?>js/vendor/active.js"></script>

<!-- Core JS -->
<script src="<?= BASE_URL ?>js/core/app.js"></script>

<?php if (!empty($pageScript)): ?>
    <script src="<?= BASE_URL ?>js/views/<?= $pageScript ?>"></script>
<?php endif; ?>

