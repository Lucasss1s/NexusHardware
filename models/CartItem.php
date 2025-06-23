<?php
class CartItem {
    private int $id;
    private int $cartId;
    private int $productId;
    private int $quantity;

    public function __construct(int $id, int $cartId, int $productId, int $quantity) {
        $this->id = $id;
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function getId(): int { return $this->id; }
    public function getCartId(): int { return $this->cartId; }
    public function getProductId(): int { return $this->productId; }
    public function getQuantity(): int { return $this->quantity; }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    // Agregar o actualizar producto en carrito
    public static function addToCart(PDO $conn, int $cartId, int $productId, int $quantity = 1): bool {
        // Verificar si ya existe el producto en el carrito
        $stmt = $conn->prepare("SELECT id, quantity FROM cart_item WHERE cart_id = :cart_id AND product_id = :product_id");
        $stmt->execute([
            ':cart_id' => $cartId,
            ':product_id' => $productId
        ]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Actualizar cantidad sumando
            $newQuantity = $existing['quantity'] + $quantity;
            $updateStmt = $conn->prepare("UPDATE cart_item SET quantity = :quantity WHERE id = :id");
            return $updateStmt->execute([
                ':quantity' => $newQuantity,
                ':id' => $existing['id']
            ]);
        } else {
            // Insertar nuevo item
            $insertStmt = $conn->prepare("INSERT INTO cart_item (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");
            return $insertStmt->execute([
                ':cart_id' => $cartId,
                ':product_id' => $productId,
                ':quantity' => $quantity
            ]);
        }
    }

    // Obtener todos los ítems de un carrito por ID
public static function getByCartId(PDO $conn, int $cartId): array {
    $stmt = $conn->prepare("
        SELECT ci.id as cart_item_id, ci.quantity, p.id as product_id, p.name, p.price
        FROM cart_item ci
        JOIN product p ON ci.product_id = p.id
        WHERE ci.cart_id = :cart_id
    ");
    $stmt->execute([':cart_id' => $cartId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Eliminar todos los ítems de un carrito por ID
    public static function deleteByCartId(PDO $conn, int $cartId): void {
        $stmt = $conn->prepare("DELETE FROM cart_item WHERE cart_id = :cart_id");
        $stmt->execute([':cart_id' => $cartId]);
    }
}
?>
