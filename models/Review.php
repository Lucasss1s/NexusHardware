<?php
class Review {
    private int $id;
    private int $productId;
    private ?int $userId;
    private int $rating;
    private string $comment;
    private string $createdAt;

    public function __construct(
        int $id,
        int $productId,
        ?int $userId,
        int $rating,
        string $comment,
        string $createdAt
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->userId = $userId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->createdAt = $createdAt;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getProductId(): int { return $this->productId; }
    public function getUserId(): ?int { return $this->userId; }
    public function getRating(): int { return $this->rating; }
    public function getComment(): string { return $this->comment; }
    public function getCreatedAt(): string { return $this->createdAt; }

    // Setters
    public function setRating(int $rating): void { $this->rating = $rating; }
    public function setComment(string $comment): void { $this->comment = $comment; }

    // Guardar la reseña en la base de datos
    public function save(PDO $conn): bool {
        $stmt = $conn->prepare("
            INSERT INTO review (product_id, user_id, rating, comment)
            VALUES (:product_id, :user_id, :rating, :comment)
        ");
        return $stmt->execute([
            ':product_id' => $this->productId,
            ':user_id' => $this->userId,
            ':rating' => $this->rating,
            ':comment' => $this->comment
        ]);
    }

    // Obtener todas las reseñas de un producto
    public static function getByProductId(int $productId, PDO $conn): array {
        $stmt = $conn->prepare("SELECT * FROM review WHERE product_id = :product_id ORDER BY created_at DESC");
        $stmt->execute([':product_id' => $productId]);

        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = new Review(
                $row['id'],
                $row['product_id'],
                $row['user_id'] ?? null,
                $row['rating'],
                $row['comment'],
                $row['created_at']
            );
        }
        return $reviews;
    }

}
