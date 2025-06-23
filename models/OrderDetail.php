<?php
class OrderDetail {
    private int $id;
    private int $orderId;
    private int $productId;
    private int $quantity;
    private float $unitPrice;

    public function __construct(int $id, int $orderId, int $productId, int $quantity, float $unitPrice) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
    }

    public function getId(): int { return $this->id; }
    public function getOrderId(): int { return $this->orderId; }
    public function getProductId(): int { return $this->productId; }
    public function getQuantity(): int { return $this->quantity; }
    public function getUnitPrice(): float { return $this->unitPrice; }

    public static function create(PDO $conn, int $orderId, int $productId, int $quantity, float $unitPrice): ?OrderDetail {
        $stmt = $conn->prepare("INSERT INTO order_detail (id_order, product_id, quantity, unit_price) 
                                VALUES (:id_order, :product_id, :quantity, :unit_price)");
        if ($stmt->execute([
            ':id_order' => $orderId,
            ':product_id' => $productId,
            ':quantity' => $quantity,
            ':unit_price' => $unitPrice
        ])) {
            $id = (int)$conn->lastInsertId();
            return new OrderDetail($id, $orderId, $productId, $quantity, $unitPrice);
        }
        return null;
    }

    public static function getByOrderId(PDO $conn, int $orderId): array {
        $stmt = $conn->prepare("SELECT * FROM order_detail WHERE id_order = :order_id");
        $stmt->execute([':order_id' => $orderId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $details = [];
        foreach ($rows as $row) {
            $details[] = new OrderDetail(
                $row['id_order_detail'],
                $row['id_order'],
                $row['product_id'],
                $row['quantity'],
                (float)$row['unit_price']
            );
        }
        return $details;
    }
}
?>
