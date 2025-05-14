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

    public function addFormation($class, $date, $desc, $price, $url, $duration, $capacity, $image) {
        $this->formationModel->addFormation($class, $date, $desc, $price, $url, $duration, $capacity, $image);
    }

    public function getFormationById($id) {
        return $this->formationModel->getFormationById($id);
    }

    public function updateFormation($id, $formationId, $class, $date,$desc,$price,$url,$duration,$capacity,$image) {
        $this->formationModel->updateFormation($id, $formationId, $class, $date,$desc,$price,$url,$duration,$capacity,$image);
    }

    public function deleteFormation($id) {
        $this->formationModel->deleteFormation($id);
    }
}
?>
