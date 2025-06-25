<?php
require_once '../controllers/require_admin.php';
require_once '../config/Database.php';
$conn = Database::getInstance();

require_once '../models/User.php';

try {
    $users = User::getAll($conn);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - User List</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>User Management</h1>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="success">User deleted successfully.</div>
    <?php endif; ?>

    <a href="add_user.php" class="add-btn">+ Add User</a>
    <a href="../index.php" class="add-btn">View page</a>
    <a href="index.php" class="back-btn">← Back to Panel</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr><td colspan="5">No users found.</td></tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user->getId() ?></td>
                        <td><?=$user->getFullName()?></td>
                        <td><?=$user->getEmail() ?></td>
                        <td><?=$user->getPhone() ?? '-' ?></td>

                        <td class="actions">
                            <a href="edit_user.php?id=<?= $user->getId() ?>" class="edit-btn">Edit</a>
                            <a href="delete_user.php?id=<?= $user->getId() ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>

</body>
</html>
