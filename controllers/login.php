<?php
session_start();
require_once '../config/connection.php';
require_once '../models/User.php';
require_once '../models/Admin.php';
require_once '../models/Customer.php';

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
            header("Location: ../admin/index.php"); // ajustá esta ruta a tu panel admin
            exit;
        } elseif ($customer) {
            $_SESSION['user'] = [
                'id' => $customer->getId(),
                'name' => $customer->getFullName(),
                'role' => 'customer'
            ];
            header("Location: ../index.php"); // o algún dashboard de usuario
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
