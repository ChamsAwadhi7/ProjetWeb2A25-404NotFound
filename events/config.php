<?php
class config {
    public static function getConnexion() {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=nextstep", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
?>
