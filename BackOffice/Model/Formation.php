<?php
require_once(__DIR__ . '/../Config/db.php');

class Formation {

    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion(); 
    }

    public function getAllFormations() {
        $stmt = $this->pdo->query("SELECT * FROM formation");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addFormation($formationId, $class, $date, $desc,$price,$url,$duration,$capacity) {
        $stmt = $this->pdo->prepare("INSERT INTO formation (id_form, class_form, date_form, desc_form, price_form,url_form,duration_form,capacity_form) VALUES (?, ?, ?, ?, ?,?,?,?)");
        return $stmt->execute([$formationId, $class, $date, $desc,$price,$url,$duration,$capacity]); 
    }

    public function updateFormation($id_form, $class_form, $date_form, $desc_form, $price_form,$url_form,$duration_form,$capacity_form) {
        $stmt = $this->pdo->prepare("UPDATE formation SET class_form = ?, date_form = ?, desc_form = ?, price_form = ?,url_form= ?,duration_form = ? ,capacity_form = ? WHERE id_form = ?");
        return $stmt->execute([$class_form, $date_form, $desc_form, $price_form,$url_form,$duration_form,$capacity_form, $id_form]); 
    }
    

    public function deleteFormation($id_form) {
        $stmt = $this->pdo->prepare("DELETE FROM formation WHERE id_form = ?");
        return $stmt->execute([$id_form]);
    }

    public function getFormationById($id_form) {
        $stmt = $this->pdo->prepare("SELECT * FROM formation WHERE id_form = ?");
        $stmt->execute([$id_form]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getWidgets() {
        $formations = $this->getAllFormations();
        $widgets = [];
    
        foreach ($formations as $formation) {
            $widgets[] = [
                'title' => 'Formation ' . htmlspecialchars($formation['id_form']),
                'content' => 'Class: ' . htmlspecialchars($formation['class_form']) . '<br>Date: ' . htmlspecialchars($formation['date_form']) . '<br>Desc: ' . htmlspecialchars($formation['desc_form']). 'Price: ' . htmlspecialchars($formation['price_form']). 'URL: ' . htmlspecialchars($formation['url_form']). 'Duration: ' . htmlspecialchars($formation['duration_form']). 'Capacity: ' . htmlspecialchars($formation['capacity_form'])
            ];
        }
    
        return $widgets;
    }
}
?>