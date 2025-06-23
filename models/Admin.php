<?php
require_once 'User.php';

class Admin extends User {
    private string $role;

    public function __construct(int $id, string $fullName, string $email, string $password, ?string $phone, string $role) {
        parent::__construct($id, $fullName, $email, $password, $phone);
        $this->role = $role;
    }

    public function getRole(): string {
        return $this->role;
    }

    // Cargar un Admin desde la base de datos
    public static function getById(int $id, PDO $conn): ?Admin {
        $stmt = $conn->prepare("
            SELECT u.*, a.role 
            FROM user u 
            INNER JOIN admin a ON u.id = a.user_id 
            WHERE u.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Admin(
                $data['id'],
                $data['full_name'],
                $data['email'],
                $data['password'],
                $data['phone'],
                $data['role']
            );
        }

        return null;
    }

    // Ejemplo de método exclusivo del Admin
    public function manageUsers(): void {
        // Lógica de administración de usuarios
    }

    public function manageProducts(): void {
        // Lógica de administración de productos
    }
}
