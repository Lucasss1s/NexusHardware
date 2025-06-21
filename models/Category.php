<?php
class Category {
    private int $id;
    private string $name;
    private ?string $description;

    public function __construct(int $id, string $name, ?string $description = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public static function getById(int $id, PDO $conn): ?Category {
        $stmt = $conn->prepare("SELECT * FROM category WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Category($data['id'], $data['name'], $data['description']);
        }

        return null;
    }

    public static function getByName(string $name, PDO $conn): ?Category {
        $stmt = $conn->prepare("SELECT * FROM category WHERE name = :name");
        $stmt->execute([':name' => $name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Category($data['id'], $data['name'], $data['description']);
        }

        return null;
    }


    public static function getAll(PDO $conn): array {
        try {
            $stmt = $conn->query("SELECT * FROM category ORDER BY name ASC");
            $categories = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = new Category($row['id'], $row['name'], $row['description']);
            }

            return $categories;
        } catch (PDOException $e) {
            return [];
        }
    }
}
