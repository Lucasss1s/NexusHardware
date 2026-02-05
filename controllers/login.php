<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once '../config/Database.php';

require_once '../models/User.php';
require_once '../models/Admin.php';
require_once '../models/Customer.php';
require_once '../models/Cart.php';

$conn = Database::getInstance();

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
    $stmt = $conn->prepare("
        SELECT id, full_name, email, password, phone
        FROM user
        WHERE email = :email
        LIMIT 1
    ");
    $stmt->execute([':email' => $email]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data || !password_verify($password, $data['password'])) {
        header("Location: " . BASE_URL . "views/login_register.php?error=invalid_credentials");
        exit;
    }

    $user = new User(
        $data['id'],
        $data['full_name'],
        $data['email'],
        $data['password'],
        $data['phone']
    );

    $admin    = Admin::getById($user->getId(), $conn);
    $customer = Customer::getById($user->getId(), $conn);

    if ($admin) {
        $_SESSION['user'] = [
            'id'   => $admin->getId(),
            'name' => $admin->getFullName(),
            'role' => 'admin'
        ];

        $_SESSION['user_id']   = $admin->getId();
        $_SESSION['user_name'] = $admin->getFullName();
        $_SESSION['user_role'] = 'admin';

        header("Location: " . BASE_URL . "admin/index.php");
        exit;
    }

    if ($customer) {
        $_SESSION['user'] = [
            'id'   => $customer->getId(),
            'name' => $customer->getFullName(),
            'role' => 'customer'
        ];

        $_SESSION['user_id']   = $customer->getId();
        $_SESSION['user_name'] = $customer->getFullName();
        $_SESSION['user_role'] = 'customer';

        $stmtCart = $conn->prepare("
            SELECT id
            FROM cart
            WHERE user_id = :user_id
            LIMIT 1
        ");
        $stmtCart->execute([':user_id' => $customer->getId()]);
        $cart = $stmtCart->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            $_SESSION['cart_id'] = $cart['id'];
        } else {
            $newCart = Cart::create($conn, $customer->getId());
            if ($newCart) {
                $_SESSION['cart_id'] = $newCart->getId();
            }
        }

        header("Location: " . BASE_URL . "index.php");
        exit;
    }

    error_log('[LOGIN] User type not recognized: ' . $user->getId());
    header("Location: " . BASE_URL . "views/login_register.php?error=invalid_user_type");
    exit;

} catch (Throwable $e) {
    error_log('[LOGIN] ' . $e->getMessage());
    header("Location: " . BASE_URL . "views/login_register.php?error=login_failed");
    exit;
}
