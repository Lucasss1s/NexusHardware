<?php
class Cart {
    private int $id;
    private string $createdAt;
    private int $totalItems;
    private ?int $clientId;

    public function __construct(int $id, string $createdAt, int $totalItems, ?int $clientId) {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->totalItems = $totalItems;
        $this->clientId = $clientId;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function getTotalItems(): int { return $this->totalItems; }
    public function getClientId(): ?int { return $this->clientId; }

    // Setters
    public function setTotalItems(int $totalItems): void { $this->totalItems = $totalItems; }
    public function setClientId(?int $clientId): void { $this->clientId = $clientId; }

    // Crear nuevo carrito
    public static function create(PDO $conn, ?int $clientId = null): ?Cart {
        $stmt = $conn->prepare("INSERT INTO cart (total_items, client_id) VALUES (0, :client_id)");
        if ($stmt->execute([':client_id' => $clientId])) {
            $id = $conn->lastInsertId();
            return new Cart((int)$id, date('Y-m-d H:i:s'), 0, $clientId);
        }
        return null;
    }

    // Obtener carrito por id
    public static function getById(int $id, PDO $conn): ?Cart {
        $stmt = $conn->prepare("SELECT * FROM cart WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Cart(
                (int)$data['id'],
                $data['created_at'],
                (int)$data['total_items'],
                $data['client_id'] !== null ? (int)$data['client_id'] : null
            );
        }
        return null;
    }

    // Obtener todos los items del carrito
    public function getItems(PDO $conn): array {
        $stmt = $conn->prepare("SELECT ci.id, ci.product_id, ci.quantity, p.name, p.price, p.img FROM cart_item ci JOIN product p ON ci.product_id = p.id WHERE ci.cart_id = :cart_id");
        $stmt->execute([':cart_id' => $this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar total_items basado en la suma de cantidades
    public function updateTotalItems(PDO $conn): bool {
        $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart_item WHERE cart_id = :cart_id");
        $stmt->execute([':cart_id' => $this->id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $total = $result['total'] ?? 0;
        $this->totalItems = (int)$total;

        $updateStmt = $conn->prepare("UPDATE cart SET total_items = :total WHERE id = :id");
        return $updateStmt->execute([':total' => $this->totalItems, ':id' => $this->id]);
    }

    // Eliminar un item del carrito por su id
    public function removeItem(PDO $conn, int $cartItemId): bool {
        $stmt = $conn->prepare("DELETE FROM cart_item WHERE id = :id AND cart_id = :cart_id");
        $result = $stmt->execute([':id' => $cartItemId, ':cart_id' => $this->id]);
        if ($result) {
            $this->updateTotalItems($conn);
        }
        return $result;
    }
}
?>
