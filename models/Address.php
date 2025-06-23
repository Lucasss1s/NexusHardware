<?php
class Address {
    private int $id;
    private ?int $userId;
    private string $street;
    private int $number;
    private string $city;
    private string $state;
    private string $postalCode;
    private string $country;
    private ?string $description;

    public function __construct(int $id, ?int $userId, string $street, int $number, string $city, string $state, string $postalCode, string $country, ?string $description) {
        $this->id = $id;
        $this->userId = $userId;
        $this->street = $street;
        $this->number = $number;
        $this->city = $city;
        $this->state = $state;
        $this->postalCode = $postalCode;
        $this->country = $country;
        $this->description = $description;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getUserId(): ?int { return $this->userId; }
    public function getStreet(): string { return $this->street; }
    public function getNumber(): int { return $this->number; }
    public function getCity(): string { return $this->city; }
    public function getState(): string { return $this->state; }
    public function getPostalCode(): string { return $this->postalCode; }
    public function getCountry(): string { return $this->country; }
    public function getDescription(): ?string { return $this->description; }

    // Crear una nueva dirección
    public static function create(PDO $conn, ?int $userId, string $street, int $number, string $city, string $state, string $postalCode, string $country, ?string $description = null): ?Address {
        $stmt = $conn->prepare("INSERT INTO address (user_id, street, number, city, state, postal_code, country, description) 
                                VALUES (:user_id, :street, :number, :city, :state, :postal_code, :country, :description)");
        $success = $stmt->execute([
            ':user_id' => $userId,
            ':street' => $street,
            ':number' => $number,
            ':city' => $city,
            ':state' => $state,
            ':postal_code' => $postalCode,
            ':country' => $country,
            ':description' => $description
        ]);

        if ($success) {
            $id = (int)$conn->lastInsertId();
            return new Address($id, $userId, $street, $number, $city, $state, $postalCode, $country, $description);
        }

        return null;
    }

    // Obtener dirección por ID
    public static function getById(PDO $conn, int $id): ?Address {
        $stmt = $conn->prepare("SELECT * FROM address WHERE id_address = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Address(
                (int)$row['id_address'],
                $row['user_id'] !== null ? (int)$row['user_id'] : null,
                $row['street'],
                (int)$row['number'],
                $row['city'],
                $row['state'],
                $row['postal_code'],
                $row['country'],
                $row['description']
            );
        }

        return null;
    }

    // Obtener todas las direcciones de un usuario
    public static function getByUserId(PDO $conn, int $userId): array {
        $stmt = $conn->prepare("SELECT * FROM address WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $addresses = [];
        foreach ($rows as $row) {
            $addresses[] = new Address(
                (int)$row['id_address'],
                $row['user_id'] !== null ? (int)$row['user_id'] : null,
                $row['street'],
                (int)$row['number'],
                $row['city'],
                $row['state'],
                $row['postal_code'],
                $row['country'],
                $row['description']
            );
        }

        return $addresses;
    }

    // Eliminar dirección por ID
    public static function deleteById(PDO $conn, int $id): bool {
        $stmt = $conn->prepare("DELETE FROM address WHERE id_address = :id");
        return $stmt->execute([':id' => $id]);
    }
}
