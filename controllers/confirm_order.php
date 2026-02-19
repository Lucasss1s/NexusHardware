<?php
require_once '../config/bootstrap.php';

require_once '../models/Order.php';
require_once '../models/OrderDetail.php';
require_once '../models/CartItem.php';


$userId = (int) ($_SESSION['user']['id'] ?? 0);
$cartId = (int) $_SESSION['cart_id'];

$street      = isset($_POST['street']) ? trim($_POST['street']) : '';
$number      = isset($_POST['number']) ? trim($_POST['number']) : '';
$city        = isset($_POST['city']) ? trim($_POST['city']) : '';
$state       = isset($_POST['state']) ? trim($_POST['state']) : '';
$postalCode  = isset($_POST['postal_code']) ? trim($_POST['postal_code']) : '';
$country     = isset($_POST['country']) ? trim($_POST['country']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';

if (
    $street === '' ||
    $number === '' ||
    $city === '' ||
    $state === '' ||
    $postalCode === '' ||
    $country === ''
) {
    header("Location: " . BASE_URL . "views/checkout.php?error=invalid_address");
    exit;
}

try {
    $conn->beginTransaction();

    $stmtAddress = $conn->prepare("
        INSERT INTO address (
            user_id,
            street,
            number,
            city,
            state,
            postal_code,
            country,
            description
        ) VALUES (
            :user_id,
            :street,
            :number,
            :city,
            :state,
            :postal_code,
            :country,
            :description
        )
    ");

    $stmtAddress->execute([
        ':user_id'      => $userId,
        ':street'       => $street,
        ':number'       => $number,
        ':city'         => $city,
        ':state'        => $state,
        ':postal_code'  => $postalCode,
        ':country'      => $country,
        ':description'  => $description
    ]);

    $addressId = (int) $conn->lastInsertId();

    $cartItems = CartItem::getByCartId($conn, $cartId);

    if (empty($cartItems)) {
        throw new Exception('Cart is empty');
    }

    $total = 0.0;

    foreach ($cartItems as $item) {
        $price    = isset($item['price']) ? (float) $item['price'] : 0.0;
        $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 0;

        $total += $price * $quantity;
    }

    $order = Order::create(
        $conn,
        $userId,
        null,
        $addressId,
        'Pending',
        $total
    );

    foreach ($cartItems as $item) {
        OrderDetail::create(
            $conn,
            $order->getId(),
            $item['product_id'],
            $item['quantity'],
            $item['price']
        );
    }

    CartItem::deleteByCartId($conn, $cartId);

    $conn->commit();

    header(
        "Location: " . BASE_URL .
        "views/pay.php?order_id=" . $order->getId()
    );
    exit;

} catch (Throwable $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    error_log('[CONFIRM_ORDER] ' . $e->getMessage());

    header("Location: " . BASE_URL . "views/checkout.php?error=order_failed");
    exit;
}
