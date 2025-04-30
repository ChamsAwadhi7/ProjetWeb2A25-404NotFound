<?php
class Admin {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Insérer un nouvel administrateur
    public function createAdmin($userId, $tel) {
        $stmt = $this->pdo->prepare("INSERT INTO admin (id, tel) VALUES (?, ?)");
        $stmt->execute([$userId, $tel]);
    }
}
?>