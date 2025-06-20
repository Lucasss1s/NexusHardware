<?php
class Product {
    private int $id;
    private string $name;
    private string $brand;
    private float $price;
    private ?float $oldPrice;
    private ?string $discount;
    private string $category;
    private string $img;
    private string $imgHover;

    public function __construct(int $id, string $name, string $brand, float $price, ?float $oldPrice, ?string $discount, string $category, string $img, string $imgHover) {
        $this->id = $id;
        $this->name = $name;
        $this->brand = $brand;
        $this->price = $price;
        $this->oldPrice = $oldPrice;
        $this->discount = $discount;
        $this->category = $category;
        $this->img = $img;
        $this->imgHover = $imgHover;
    }
    //gets
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getBrand(): string { return $this->brand; }
    public function getPrice(): float { return $this->price; }
    public function getOldPrice(): ?float { return $this->oldPrice; }
    public function getDiscount(): ?string { return $this->discount; }
    public function getCategory(): string { return $this->category; }
    public function getImage(): string { return $this->img; }
    public function getImageHover(): string { return $this->imgHover; }

    //sets
    public function setName(string $name): void { $this->name = $name; }
    public function setBrand(string $brand): void { $this->brand = $brand; }
    public function setPrice(float $price): void { $this->price = $price; }
    public function setOldPrice(?float $oldPrice): void { $this->oldPrice = $oldPrice; }
    public function setDiscount(?string $discount): void { $this->discount = $discount; }
    public function setCategory(string $category): void { $this->category = $category; }
    public function setImage(string $img): void { $this->img = $img; }
    public function setImageHover(string $imgHover): void { $this->imgHover = $imgHover; }


    public static function getById(int $id, PDO $conn): ?Product {
        $stmt = $conn->prepare("SELECT * FROM producto WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Product(
                $data['id'],
                $data['name'],
                $data['brand'],
                $data['price'],
                $data['old_price'],
                $data['discount'],
                $data['category'],
                $data['img'],
                $data['img_hover']
            );
        }
        return null;
    }    

    public function update(PDO $conn): bool {
        $stmt = $conn->prepare("INSERT INTO producto 
        (name, brand, price, old_price, discount, category, img, img_hover)
        VALUES 
        (:name, :brand, :price, :old_price, :discount, :category, :img, :img_hover)");

        return $stmt->execute([
            ':name' => $this->name,
            ':brand' => $this->brand,
            ':price' => $this->price,
            ':old_price' => $this->oldPrice,
            ':discount' => $this->discount,
            ':category' => $this->category,
            ':img' => $this->img,
            ':img_hover' => $this->imgHover,
            ':id' => $this->id
        ]);
    }

    public static function deleteById(int $id, PDO $conn): bool {
    $stmt = $conn->prepare("DELETE FROM producto WHERE id = :id");
    return $stmt->execute([':id' => $id]);
    }


}



?>