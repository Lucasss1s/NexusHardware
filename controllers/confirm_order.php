<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/connection.php';
require_once '../models/Payment.php';
require_once '../models/Order.php';
require_once '../models/OrderDetail.php';
require_once '../models/CartItem.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['cart_id'])) {
    header("Location: login_register.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$cartId = $_SESSION['cart_id'];

// Validar campos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $street = trim($_POST['street']);
    $number = trim($_POST['number']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $postalCode = trim($_POST['postal_code']);
    $country = trim($_POST['country']);
    $description = trim($_POST['description'] ?? '');

    $method = 'Credit Card'; // o $_POST['method'] si querés que el usuario elija
    $status = 'Paid';
    $orderStatus = 'Processing';

    try {
        $conn->beginTransaction();

        // 1. Crear el pago
        $payment = Payment::create($conn, $method, $status);

        // 2. Insertar la dirección del usuario
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

        // 3. Obtener ítems del carrito con precio y cantidad (modificado para obtener info necesaria)
        $cartItems = CartItem::getByCartId($conn, $cartId);
        if (empty($cartItems)) {
            throw new Exception("El carrito está vacío.");
        }

        // Calcular total correctamente: sumando price * quantity
        $total = 0.0;
        foreach ($cartItems as $item) {
            // Asegurar que 'price' y 'quantity' existan y sean numéricos
            $price = isset($item['price']) ? (float) $item['price'] : 0.0;
            $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 0;
            $total += $price * $quantity;
        }

        // 4. Crear la orden (recordá que Order::create debe recibir float en total)
        $order = Order::create($conn, $userId, $payment->getId(), $id_address, $orderStatus, $total);

        // 5. Crear detalles de la orden
        foreach ($cartItems as $item) {
            OrderDetail::create(
                $conn,
                $order->getId(),
                $item['product_id'],
                $item['quantity'],
                $item['price']
            );
        }

        // 6. Vaciar el carrito
        CartItem::deleteByCartId($conn, $cartId);

        $conn->commit();

        // Redirigir a la página de éxito
        header("Location: ../views/compra_exitosa.php");
        exit;

    } catch (Exception $e) {
        $conn->rollBack();
        die("Error al procesar el pedido: " . $e->getMessage());
    }
} else {
    header("Location: ../views/checkout.php");
    exit;
}
