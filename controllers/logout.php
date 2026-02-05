<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

unset($_SESSION['user']);
unset($_SESSION['cart_id']);

session_unset();
session_destroy();

header("Location:" . BASE_URL . "index.php");
exit;
?>
