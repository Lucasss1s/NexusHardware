<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/connection.php';
require_once '../models/Order.php';
require_once '../models/OrderDetail.php';
require_once '../models/CartItem.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['cart_id'])) {
    header("Location: login_register.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$cartId = $_SESSION['cart_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $street = trim($_POST['street']);
    $number = trim($_POST['number']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $postalCode = trim($_POST['postal_code']);
    $country = trim($_POST['country']);
    $description = trim($_POST['description'] ?? '');

    $orderStatus = 'Processing';

    try {
        $conn->beginTransaction();

        // Insertar dirección
        $stmtAddress = $conn->prepare("
            INSERT INTO address (user_id, street, number, city, state, postal_code, country, description)
            VALUES (:user_id, :street, :number, :city, :state, :postal_code, :country, :description)
        ");
        $stmtAddress->execute([
            ':user_id' => $userId,
            ':street' => $street,
            ':number' => $number,
            ':city' => $city,
            ':state' => $state,
            ':postal_code' => $postalCode,
            ':country' => $country,
            ':description' => $description
        ]);
        $id_address = (int) $conn->lastInsertId();

        // Obtener ítems del carrito
        $cartItems = CartItem::getByCartId($conn, $cartId);
        if (empty($cartItems)) {
            throw new Exception("El carrito está vacío.");
        }

        $total = 0.0;
        foreach ($cartItems as $item) {
            $price = isset($item['price']) ? (float) $item['price'] : 0.0;
            $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 0;
            $total += $price * $quantity;
        }

        // Crear orden SIN pago aún
        $order = Order::create($conn, $userId, null, $id_address, $orderStatus, $total);

        // Detalles de la orden
        foreach ($cartItems as $item) {
            OrderDetail::create(
                $conn,
                $order->getId(),
                $item['product_id'],
                $item['quantity'],
                $item['price']
            );
        }

        // Vaciar carrito
        CartItem::deleteByCartId($conn, $cartId);

        $conn->commit();

        // Redirigir al pago
        header("Location: ../views/pay.php?order_id=" . $order->getId());
        exit;

    } catch (Exception $e) {
        $conn->rollBack();
        die("Error al procesar el pedido: " . $e->getMessage());
    }
} else {
    header("Location: ../views/checkout.php");
    exit;
}

?>