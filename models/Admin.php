<?php
require_once 'User.php';

class Admin extends User {
    private string $role;

    public function __construct(int $id, string $fullName, string $email, string $password, string $phone, string $role) {
        parent::__construct($id, $fullName, $email, $password, $phone);
        $this->role = $role;
    }

    public function manageProduct(Product $product): void {
        // Logic for managing a product
    }

    public function manageUser(User $user): void {
        // Logic for managing a user
    }
}

?>