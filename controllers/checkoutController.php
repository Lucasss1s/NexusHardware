<?php
require_once __DIR__ . '/../config/bootstrap.php';

require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/CartItem.php';
require_once __DIR__ . '/../models/Address.php';

/* Auth */
if (!isset($_SESSION['user']) || !isset($_SESSION['cart_id'])) {
    header("Location: " . BASE_URL . "views/login_register.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$cartId = $_SESSION['cart_id'];

/* Cart */
$cart = Cart::getById($cartId, $conn);
$items = $cart->getItems($conn);

/* Adress */
$addresses = Address::getByUserId($conn, $userId);

$addressData = null;
if (!empty($addresses)) {
    $a = $addresses[0];
    $addressData = [
        'street'      => $a->getStreet(),
        'number'      => $a->getNumber(),
        'city'        => $a->getCity(),
        'state'       => $a->getState(),
        'postalCode'  => $a->getPostalCode(),
        'country'     => $a->getCountry(),
        'description' => $a->getDescription(),
    ];
}

/* Total */
$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}
