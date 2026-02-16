<?php
require_once '../config/bootstrap.php';

unset($_SESSION['user']);
unset($_SESSION['cart_id']);

session_unset();
session_destroy();

header("Location:" . BASE_URL . "index.php");
exit;
?>
