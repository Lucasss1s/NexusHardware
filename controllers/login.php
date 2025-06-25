<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/Database.php';
$conn = Database::getInstance();

require_once '../models/User.php';
require_once '../models/Admin.php';
require_once '../models/Customer.php';
require_once '../models/Cart.php'; // <-- agregamos Cart

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$email || !$password) {
        header("Location: ../views/login_register.php?error=Email and password are required");
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data || !password_verify($password, $data['password'])) {
            header("Location: ../views/login_register.php?error=Invalid credentials");
            exit;
        }

        // Crear instancia base de usuario
        $user = new User(
            $data['id'],
            $data['full_name'],
            $data['email'],
            $data['password'],
            $data['phone']
        );

        // Detectar si es admin o customer
        $admin = Admin::getById($user->getId(), $conn);
        $customer = Customer::getById($user->getId(), $conn);

        if ($admin) {
            $_SESSION['user'] = [
                'id' => $admin->getId(),
                'name' => $admin->getFullName(),
                'role' => 'admin'
            ];
            header("Location: ../admin/index.php");
            exit;

        } elseif ($customer) {
            $_SESSION['user'] = [
                'id' => $customer->getId(),
                'name' => $customer->getFullName(),
                'role' => 'customer'
            ];

            // ✅ GESTIÓN DEL CARRITO PARA CUSTOMER
            $stmtCart = $conn->prepare("SELECT id FROM cart WHERE user_id = :user_id LIMIT 1");
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

            header("Location: ../index.php");
            exit;

        } else {
            header("Location: ../views/login_register.php?error=User type not recognized");
            exit;
        }

    } catch (PDOException $e) {
        header("Location: ../views/login_register.php?error=Login failed: " . urlencode($e->getMessage()));
        exit;
    }

} else {
    header("Location: ../views/login_register.php");
    exit;
}
