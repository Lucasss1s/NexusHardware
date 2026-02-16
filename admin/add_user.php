<?php
require_once '../controllers/require_admin.php';
require_once '../config/bootstrap.php';

require_once '../models/User.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']) ?: null;
    $role = $_POST['role'] ?? null;

    if (!$fullName || !$email || !$password || !$role) {
        $error = "All fields except phone are required.";
    } else {
        try {
            // Validar duplicado
            $check = $conn->prepare("SELECT id FROM user WHERE email = :email");
            $check->execute([':email' => $email]);
            if ($check->fetch()) {
                $error = "Email already registered.";
            } else {
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

                $userId = $conn->lastInsertId();

                // Insertar en admin o customer
                if ($role === 'admin') {
                    $conn->prepare("INSERT INTO admin (user_id, role) VALUES (:user_id, 'admin')")
                            ->execute([':user_id' => $userId]);
                } else {
                    $conn->prepare("INSERT INTO customer (user_id, registration_date) VALUES (:user_id, NOW())")
                            ->execute([':user_id' => $userId]);
                }

                $success = "User created successfully as $role.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Add New User</h1>
    <a href="users.php" class="back-btn">← Back to List</a>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="form">
        <label>Full Name:</label>
        <input type="text" name="full_name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Phone (optional):</label>
        <input type="text" name="phone">

        <label>Role:</label>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="admin">Admin</option>
            <option value="customer">Customer</option>
        </select>

        <button type="submit">Save</button>
    </form>
</body>
</html>
