<?php
require_once(__DIR__ . '/../Config/db.php');

class Participation {

    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion(); 
    }

    // Fetch all participations
    public function getAllParticipations() {
        $stmt = $this->pdo->query("SELECT * FROM participations");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new participation
    public function addParticipation($date_Part) {
        $stmt = $this->pdo->prepare("INSERT INTO participations (date_Part) VALUES (?)");
        return $stmt->execute([$date_Part]);
    }

    // Update participation record
    public function updateParticipation($id_Part, $date_Part) {
        $stmt = $this->pdo->prepare("UPDATE participations SET date_Part = ? WHERE id_Part = ?");
        return $stmt->execute([$date_Part, $id_Part]);
    }

    // Delete participation record
    public function deleteParticipation($id_Part) {
        $stmt = $this->pdo->prepare("DELETE FROM participations WHERE id_Part = ?");
        return $stmt->execute([$id_Part]);
    }

    // Get a participation by ID
    public function getParticipationById($id_Part) {
        $stmt = $this->pdo->prepare("SELECT * FROM participations WHERE id_Part = ?");
        $stmt->execute([$id_Part]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
