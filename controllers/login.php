<?php
require_once '../config/bootstrap.php';

require_once '../models/User.php';
require_once '../models/Admin.php';
require_once '../models/Customer.php';
require_once '../models/Cart.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/login_register.php");
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header("Location: " . BASE_URL . "views/login_register.php?error=missing_credentials");
    exit;
}

try {
    $user = User::login($email, $password, $conn);

    if (!$user) {
        header("Location: " . BASE_URL . "views/login_register.php?error=invalid_credentials");
        exit;
    }

    // Rol
    $admin    = Admin::getById($user->getId(), $conn);
    $customer = Customer::getById($user->getId(), $conn);

    if ($admin) {
        $_SESSION['user'] = [
            'id'   => $admin->getId(),
            'name' => $admin->getFullName(),
            'role' => 'admin'
        ];

        header("Location: " . BASE_URL . "admin/index.php");
        exit;
    }

    if ($customer) {
        $_SESSION['user'] = [
            'id'   => $customer->getId(),
            'name' => $customer->getFullName(),
            'role' => 'customer'
        ];

        // Cart
        $stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = :id LIMIT 1");
        $stmt->execute([':id' => $customer->getId()]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            $_SESSION['cart_id'] = $cart['id'];
        } else {
            $newCart = Cart::create($conn, $customer->getId());
            $_SESSION['cart_id'] = $newCart->getId();
        }

        header("Location: " . BASE_URL . "index.php");
        exit;
    }

    header("Location: " . BASE_URL . "views/login_register.php?error=invalid_user_type");
    exit;

} catch (Throwable $e) {
    error_log('[LOGIN] ' . $e->getMessage());
    header("Location: " . BASE_URL . "views/login_register.php?error=login_failed");
    exit;
}
