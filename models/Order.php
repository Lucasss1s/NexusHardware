<?php
class Order {
    private int $id;
    private ?int $userId;
    private ?int $paymentId;
    private int $addressId;
    private string $orderDate;
    private string $status;
    private float $total;

    public function __construct(int $id, ?int $userId, ?int $paymentId, int $addressId, string $orderDate, string $status, float $total) {
        $this->id = $id;
        $this->userId = $userId;
        $this->paymentId = $paymentId;
        $this->addressId = $addressId;
        $this->orderDate = $orderDate;
        $this->status = $status;
        $this->total = $total;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getUserId(): ?int { return $this->userId; }
    public function getPaymentId(): ?int { return $this->paymentId; }
    public function getAddressId(): int { return $this->addressId; }
    public function getOrderDate(): string { return $this->orderDate; }
    public function getStatus(): string { return $this->status; }
    public function getTotal(): float { return $this->total; }

    // Crear una nueva orden
    public static function create(PDO $conn, ?int $userId, ?int $paymentId, int $addressId, string $status, float $total): ?Order {
        $stmt = $conn->prepare("INSERT INTO `order` (user_id, id_payment, id_address, order_date, status, total)
                                VALUES (:user_id, :id_payment, :id_address, NOW(), :status, :total)");
        if ($stmt->execute([
            ':user_id' => $userId,
            ':id_payment' => $paymentId,
            ':id_address' => $addressId,
            ':status' => $status,
            ':total' => $total
        ])) {
            $id = (int)$conn->lastInsertId();
            return new Order($id, $userId, $paymentId, $addressId, date('Y-m-d H:i:s'), $status, $total);
        }
        return null;
    }

    // Obtener orden por ID
    public static function getById(PDO $conn, int $id): ?Order {
        $stmt = $conn->prepare("SELECT * FROM `order` WHERE id_order = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Order(
                $row['id_order'],
                $row['user_id'],
                $row['id_payment'],
                $row['id_address'],
                $row['order_date'],
                $row['status'],
                (float)$row['total']
            );
        }
        return null;
    }

    // Obtener todas las órdenes por ID de usuario
    public static function getByUserId(PDO $conn, int $userId): array {
        $stmt = $conn->prepare("SELECT * FROM `order` WHERE user_id = :user_id ORDER BY order_date DESC");
        $stmt->execute([':user_id' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $orders = [];
        foreach ($rows as $row) {
            $orders[] = new Order(
                $row['id_order'],
                $row['user_id'],
                $row['id_payment'],
                $row['id_address'],
                $row['order_date'],
                $row['status'],
                (float)$row['total']
            );
        }
        return $orders;
    }
}
?>
