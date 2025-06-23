<?php
class Payment {
    private int $id;
    private string $paymentDate;
    private string $method;
    private string $status;

    public function __construct(int $id, string $paymentDate, string $method, string $status) {
        $this->id = $id;
        $this->paymentDate = $paymentDate;
        $this->method = $method;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getPaymentDate(): string { return $this->paymentDate; }
    public function getMethod(): string { return $this->method; }
    public function getStatus(): string { return $this->status; }

    public static function create(PDO $conn, string $method, string $status): ?Payment {
        $stmt = $conn->prepare("INSERT INTO payment (payment_date, method, status) VALUES (NOW(), :method, :status)");
        if ($stmt->execute([':method' => $method, ':status' => $status])) {
            $id = (int)$conn->lastInsertId();
            return new Payment($id, date('Y-m-d H:i:s'), $method, $status);
        }
        return null;
    }

    public static function getById(PDO $conn, int $id): ?Payment {
        $stmt = $conn->prepare("SELECT * FROM payment WHERE id_payment = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Payment($row['id_payment'], $row['payment_date'], $row['method'], $row['status']);
        }
        return null;
    }
}
?>
