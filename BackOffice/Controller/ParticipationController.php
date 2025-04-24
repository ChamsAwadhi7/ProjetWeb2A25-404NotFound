<?php
require_once(__DIR__ . '/../Config/db.php');
require_once(__DIR__ . '/../Model/Participation.php');

class ParticipationController {
    private $participationModel;

    public function __construct($pdo) {
        $this->participationModel = new Participation($pdo);
    }

    // Get all participations
    public function getAllParticipations() {
        return $this->participationModel->getAllParticipations();
    }

    // Add new participation
    public function addParticipation($date_Part) {
        $this->participationModel->addParticipation($date_Part);
    }

    // Get participation by ID
    public function getParticipationById($id_Part) {
        return $this->participationModel->getParticipationById($id_Part);
    }

    // Update participation
    public function updateParticipation($id_Part, $date_Part) {
        $this->participationModel->updateParticipation($id_Part, $date_Part);
    }

    // Delete participation
    public function deleteParticipation($id_Part) {
        $this->participationModel->deleteParticipation($id_Part);
    }
}
?>
