<?php
class User {
    protected int $id;
    protected string $fullName;
    protected string $email;
    protected string $password;
    protected ?string $phone;

    public function __construct(int $id, string $fullName, string $email, string $password, ?string $phone) {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }
    public function getFullName(): string {
        return $this->fullName;
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function getPhone(): ?string {
        return $this->phone;
    }

    // Método de login (estático, para buscar y autenticar)
    public static function login(string $email, string $password, PDO $conn): ?User {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data && password_verify($password, $data['password'])) {
            return new User(
                $data['id'],
                $data['full_name'],
                $data['email'],
                $data['password'],
                $data['phone']
            );
        }

        return null;
    }

    public static function logout(): void {
        session_start();
        session_unset();
        session_destroy();
    }

    public static function getAll(PDO $conn): array {
    $stmt = $conn->query("SELECT * FROM user ORDER BY id DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $users = [];
    foreach ($rows as $row) {
        $users[] = new User(
            (int)$row['id'],
            $row['full_name'],
            $row['email'],
            $row['password'],
            $row['phone']
        );
    }
    return $users;
}

}


?>