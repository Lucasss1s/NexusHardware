<?php
require_once '../controllers/require_admin.php';
require_once '../config/Database.php';
$conn = Database::getInstance();

require_once '../models/User.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Missing user ID.");
}

$success = '';
$error = '';

try {
    // Obtener usuario
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        die("User not found.");
    }

    // Detectar rol actual
    $role = null;
    $check = $conn->prepare("SELECT user_id FROM admin WHERE user_id = :id");
    $check->execute([':id' => $id]);
    $role = $check->fetch() ? 'admin' : 'customer';

    // Si se envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullName = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']) ?: null;
        $newRole = $_POST['role'] ?? $role;
        $newPassword = $_POST['password'] ?? '';

        if (!$fullName || !$email || !$newRole) {
            $error = "Full name, email and role are required.";
        } else {
            // Actualizar datos base
            $params = [
                ':full_name' => $fullName,
                ':email' => $email,
                ':phone' => $phone,
                ':id' => $id
            ];

            $query = "UPDATE user SET full_name = :full_name, email = :email, phone = :phone";

            // Si se ingresó una nueva contraseña
            if (!empty($newPassword)) {
                $query .= ", password = :password";
                $params[':password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }

            $query .= " WHERE id = :id";

            $stmt = $conn->prepare($query);
            $stmt->execute($params);

            // Si cambió de rol
            if ($role !== $newRole) {
                if ($role === 'admin') {
                    $conn->prepare("DELETE FROM admin WHERE user_id = :id")->execute([':id' => $id]);
                } else {
                    $conn->prepare("DELETE FROM customer WHERE user_id = :id")->execute([':id' => $id]);
                }

                if ($newRole === 'admin') {
                    $conn->prepare("INSERT INTO admin (user_id, role) VALUES (:id, 'admin')")->execute([':id' => $id]);
                } else {
                    $conn->prepare("INSERT INTO customer (user_id, registration_date) VALUES (:id, NOW())")->execute([':id' => $id]);
                }
            }

            $success = "User updated successfully.";
            $role = $newRole;
            $data['full_name'] = $fullName;
            $data['email'] = $email;
            $data['phone'] = $phone;
        }
    }
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Edit User</h1>
    <a href="users.php" class="back-btn">← Back to List</a>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="form">
        <label>Full Name:</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($data['full_name']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>

        <label>Phone (optional):</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($data['phone']) ?>">

        <label>New Password (optional):</label>
        <input type="password" name="password">

        <label>Role:</label>
        <select name="role" required>
            <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="customer" <?= $role === 'customer' ? 'selected' : '' ?>>Customer</option>
        </select>

        <button type="submit">Update</button>
    </form>
</body>
</html>
