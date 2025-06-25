<?php
require_once '../config/Database.php';
$conn = Database::getInstance();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']) ?: null;

    // Validar campos básicos
    if (!$fullName || !$email || !$password) {
        header("Location: ../views/login_register.php?error=All fields are required");
        exit;
    }

    try {
        // Verificar si el email ya está registrado
        $stmt = $conn->prepare("SELECT id FROM user WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
            header("Location: ../views/login_register.php?error=Email is already registered");
            exit;
        }

        // Insertar en user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO user (full_name, email, password, phone)
            VALUES (:full_name, :email, :password, :phone)
        ");
        $stmt->execute([
            ':full_name' => $fullName,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':phone' => $phone
        ]);

        // Obtener el ID del nuevo usuario
        $userId = $conn->lastInsertId();

        // Insertar en customer
        $stmt = $conn->prepare("
            INSERT INTO customer (user_id, registration_date)
            VALUES (:user_id, NOW())
        ");
        $stmt->execute([':user_id' => $userId]);

        // Redirigir con éxito
        header("Location: ../views/login_register.php?success=Account created successfully");
        exit;
    } catch (PDOException $e) {
        // Error general
        header("Location: ../views/login_register.php?error=Registration failed: " . urlencode($e->getMessage()));
        exit;
    }
} else {
    // Si alguien accede directo por GET
    header("Location: ../views/login_register.php");
    exit;
}
