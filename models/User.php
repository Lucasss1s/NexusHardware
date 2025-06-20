<?php
class User {
    protected int $id;
    protected string $fullName;
    protected string $email;
    protected string $password;
    protected string $phone;

    public function __construct(int $id, string $fullName, string $email, string $password, string $phone) {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
    }

    public function login(): bool {
        // Authentication logic
        return true;
    }

    public function logout(): void {
        // Logout logic
    }
}

?>