<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title  -->
    <title>NexusHardware</title>

    <link rel="icon" href="/nexushardware/img/core-img/logoPrincipal.png">
    <link rel="stylesheet" href="/nexushardware/css/core-style.css">
    <link rel="stylesheet" href="/nexushardware/style.css">

</head>

<body>
    <!-- ##### Header Area Start ##### -->
    <header class="header_area">
        <div class="classy-nav-container breakpoint-off d-flex align-items-center justify-content-between">
            <!-- Classy Menu -->
            <nav class="classy-navbar" id="essenceNav">
                <!-- Logo -->
                <a class="nav-brand" href="../index.php"><img src="/nexushardware/img/core-img/logoPrincipal.png"
                        alt=""></a>
                <!-- Navbar Toggler -->
                <div class="classy-navbar-toggler">
                    <span class="navbarToggler"><span></span><span></span><span></span></span>
                </div>
                <!-- Menu -->
                <div class="classy-menu">
                    <!-- close btn -->
                    <div class="classycloseIcon">
                        <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                    </div>
                    <div class="classynav">
                        <ul>
                            <li><a href="/NexusHardware/index.php">Home</a></li>
                            <li><a href="/NexusHardware/views/shop.php">Shop</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Header Meta Data -->
            <div class="header-meta d-flex clearfix justify-content-end">
                <!-- Search Area -->
                <div class="search-area">
                    <form action="#" method="post">
                        <input type="search" name="search" id="headerSearch" placeholder="Type for search">
                        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>
                </div>
                <!-- Favourite Area -->
                <div class="favourite-area">
                    <a href="/nexushardware/views/purchase_history.php"><img
                            src="/nexushardware/img/core-img/purchase_history.png" alt=""></a>
                </div>
                <!-- User Login Info -->
                <div class="user-login-info">
                    <a href="#" onclick="toggleUserMenu(); return false;">
                        <img src="/nexushardware/img/core-img/user.svg" alt="User">
                    </a>

                    <div id="user-menu">
                        <?php if (isset($_SESSION['user'])): ?>
                            <p><?= htmlspecialchars($_SESSION['user']['name']) ?></p>
                            <?php if ($_SESSION['user']['role'] === 'admin'): // Verificar si el rol es admin ?>
                                <a style="line-height: 30px;" class="user-menu-abm"
                                    href="/nexushardware/admin/index.php">Panel ABM</a>
                            <?php endif; ?>
                            <a style="line-height: 30px;" class="user-menu-abm"
                                href="/nexushardware/controllers/logout.php">Logout</a>
                        <?php else: ?>
                            <a style="line-height: 30px;" class="user-menu-abm"
                                href="/nexushardware/views/login_register.php">Sign In</a>
                        <?php endif; ?>
                    </div>
                </div>


                <div class="cart-area">
                    <a href="/NexusHardware/views/cart_product.php"><img src="/nexushardware/img/core-img/bag.svg"
                            alt=""> <span></span></a>
                </div>
            </div>

        </div>
    </header>
    <!-- ##### Header Area End ##### -->
    <script>
        function toggleUserMenu() {
            const menu = document.getElementById("user-menu");
            menu.style.display = (menu.style.display === "none" || menu.style.display === "") ? "block" : "none";
        }

        document.addEventListener("click", function (e) {
            const menu = document.getElementById("user-menu");
            const trigger = document.querySelector(".user-login-info a");
            if (!menu.contains(e.target) && !trigger.contains(e.target)) {
                menu.style.display = "none";
            }
        });
    </script>