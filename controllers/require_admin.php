<?php
session_start();
require_once '../config/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location:" . BASE_URL . "index.php?error=Unauthorized access");
    exit;
}

?>