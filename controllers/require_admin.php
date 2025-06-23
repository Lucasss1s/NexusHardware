<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /nexushardware/index.php?error=Unauthorized access");
    exit;
}

?>