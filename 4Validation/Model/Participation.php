<?php
require_once __DIR__ . '/../config.php'; // Assure-toi que $pdo est bien défini ici

class Participation {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter une participation
    public function addParticipation($id_user) {
        $date_Part = date("Y-m-d H:i:s");
        $stmt = $this->pdo->prepare("INSERT INTO participations (id_user, date_Part) VALUES (?, ?)");
        return $stmt->execute([$id_user, $date_Part]);
    }

    // Récupérer toutes les participations
    public function getAllParticipations() {
        $stmt = $this->pdo->query("SELECT 
                                       p.id_Part, 
                                       p.date_Part, 
                                       p.id_form, 
                                       u.id AS id_user, 
                                       u.nom, 
                                       u.prénom
                                   FROM participations p
                                   JOIN utilisateur u ON p.id_user = u.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une participation par son ID
    public function getParticipationById($id_Part) {
        $stmt = $this->pdo->prepare("SELECT * FROM participations WHERE id_Part = ?");
        $stmt->execute([$id_Part]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour une participation
    public function updateParticipation($id_Part, $date_Part) {
        $stmt = $this->pdo->prepare("UPDATE participations SET date_Part = ? WHERE id_Part = ?");
        return $stmt->execute([$date_Part, $id_Part]);
    }

    // Supprimer une participation
    public function deleteParticipation($id_Part) {
        $stmt = $this->pdo->prepare("DELETE FROM participations WHERE id_Part = ?");
        return $stmt->execute([$id_Part]);
    }
}
?>
