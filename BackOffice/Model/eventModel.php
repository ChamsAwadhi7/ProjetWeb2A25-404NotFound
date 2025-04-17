<?php
// model/EventModel.php
require_once __DIR__ . '/../config.php';

class EventModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajout d'un événement
    public function addEvent($nom, $date, $description, $lieu, $imageBlob) {
        // Préparer la requête d'insertion
        $stmt = $this->pdo->prepare("INSERT INTO events (nom_event, date_event, desc_event, img_event, lieu_event) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nom, $date, $description, $imageBlob, $lieu]);
    }

    // Récupérer tous les événements
    public function getAllEvents() {
        $stmt = $this->pdo->query("SELECT * FROM events ORDER BY date_event DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un événement par son ID
    public function getEventById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id_event = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Supprimer un événement
    public function deleteEvent($id) {
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE id_event = ?");
        return $stmt->execute([$id]);
    }

    // Mettre à jour un événement
    public function updateEvent($id, $nom, $date, $description, $lieu, $imageBlob) {
        $stmt = $this->pdo->prepare("UPDATE events SET nom_event = ?, date_event = ?, desc_event = ?, img_event = ?, lieu_event = ? WHERE id_event = ?");
        return $stmt->execute([$nom, $date, $description, $imageBlob, $lieu, $id]);
    }
}
?>
