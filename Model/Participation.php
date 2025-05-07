<?php
require_once(__DIR__ . '/../Config/db.php');

class Participation {

    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    // Add participation
    public function addParticipation($id_user) {
        // Automatically use the current date when a user subscribes
        $date_Part = date("Y-m-d H:i:s");
        $stmt = $this->pdo->prepare("INSERT INTO participations (id_user, date_Part) VALUES (?, ?)");
        return $stmt->execute([$id_user, $date_Part]);
    }
 
    public function getAllParticipations() {
    $stmt = $this->pdo->query("SELECT 
                                   p.id_Part, 
                                   p.date_Part, 
                                   p.id_form,              -- ✅ Make sure this is here
                                   u.id_user, 
                                   u.nom_user, 
                                   u.prenom_user
                               FROM participations p
                               JOIN user u ON p.id_user = u.id_user");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    

    // Get participation by ID
    public function getParticipationById($id_Part) {
        $stmt = $this->pdo->prepare("SELECT * FROM participations WHERE id_Part = ?");
        $stmt->execute([$id_Part]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update participation
    public function updateParticipation($id_Part, $date_Part) {
        $stmt = $this->pdo->prepare("UPDATE participations SET date_Part = ? WHERE id_Part = ?");
        return $stmt->execute([$date_Part, $id_Part]);
    }

    // Delete participation
    public function deleteParticipation($id_Part) {
        $stmt = $this->pdo->prepare("DELETE FROM participations WHERE id_Part = ?");
        return $stmt->execute([$id_Part]);
    }
}
?>
