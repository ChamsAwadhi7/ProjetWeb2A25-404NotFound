<?php
require_once(__DIR__ . '/../Config/db.php');
require_once(__DIR__ . '/../Model/Formation.php');

class FormationController {
    private $formationModel;

    public function __construct($pdo) {
        $this->formationModel = new Formation($pdo);
    }

    public function getAllFormations() {
        return $this->formationModel->getAllFormations();
    }

    public function addFormation($formationId, $class, $date,$desc,$price,$url,$duration,$capacity) {
        $this->formationModel->addFormation($formationId, $class, $date,$desc,$price,$url,$duration,$capacity);
    }

    public function getFormationById($id) {
        return $this->formationModel->getFormationById($id);
    }

    public function updateFormation($id, $formationId, $class, $date,$desc,$price,$url,$duration,$capacity) {
        $this->formationModel->updateFormation($id, $formationId, $class, $date,$desc,$price,$url,$duration,$capacity);
    }

    public function deleteFormation($id) {
        $this->formationModel->deleteFormation($id);
    }
}
?>
