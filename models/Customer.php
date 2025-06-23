<?php
require_once 'User.php';

class Customer extends User {
    private string $registrationDate;

    public function __construct(int $id, string $fullName, string $email, string $password, ?string $phone, string $registrationDate) {
        parent::__construct($id, $fullName, $email, $password, $phone);
        $this->registrationDate = $registrationDate;
    }

    public function getRegistrationDate(): string {
        return $this->registrationDate;
    }

    // Obtener un cliente por ID
    public static function getById(int $id, PDO $conn): ?Customer {
        $stmt = $conn->prepare("
            SELECT u.*, c.registration_date 
            FROM user u 
            INNER JOIN customer c ON u.id = c.user_id 
            WHERE u.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Customer(
                $data['id'],
                $data['full_name'],
                $data['email'],
                $data['password'],
                $data['phone'],
                $data['registration_date']
            );
        }

        return null;
    }

    // Método exclusivo: ver historial de compras
    public function viewPurchaseHistory(PDO $conn): array {
        // Suponiendo que exista una tabla `order`
        $stmt = $conn->prepare("SELECT * FROM order WHERE customer_id = :id ORDER BY created_at DESC");
        $stmt->execute([':id' => $this->id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
