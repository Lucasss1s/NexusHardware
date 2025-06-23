<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['user']);
unset($_SESSION['cart_id']);

session_unset();
session_destroy();

// Redirigir correctamente al index desde /controllers/
header("Location: /NexusHardware/index.php");
exit;
?>
