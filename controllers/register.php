<?php
require_once '../config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/login_register.php");
    exit;
}

$fullName = trim($_POST['full_name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$phone    = trim($_POST['phone'] ?? '') ?: null;

if ($fullName === '' || $email === '' || $password === '') {
    header("Location: " . BASE_URL . "views/login_register.php?error=missing_fields");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: " . BASE_URL . "views/login_register.php?error=invalid_email");
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id FROM user WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);

    if ($stmt->fetch()) {
        header("Location: " . BASE_URL . "views/login_register.php?error=email_exists");
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO user (full_name, email, password, phone)
        VALUES (:full_name, :email, :password, :phone)
    ");
    $stmt->execute([
        ':full_name' => $fullName,
        ':email'     => $email,
        ':password'  => $hashedPassword,
        ':phone'     => $phone
    ]);

    $userId = (int) $conn->lastInsertId();

    $stmt = $conn->prepare("
        INSERT INTO customer (user_id, registration_date)
        VALUES (:user_id, NOW())
    ");
    $stmt->execute([':user_id' => $userId]);

    header("Location: " . BASE_URL . "views/login_register.php?success=account_created");
    exit;

} catch (Throwable $e) {
    error_log('[REGISTER] ' . $e->getMessage());
    header("Location: " . BASE_URL . "views/login_register.php?error=register_failed");
    exit;
}
