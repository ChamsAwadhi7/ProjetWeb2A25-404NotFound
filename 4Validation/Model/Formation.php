<?php
require 'C:/xampp/htdocs/4Validation/config.php';
class Formation {

    private $pdo;

    /*public function __construct($pdo) {
        $this->pdo = $pdo; // Injection de la connexion PDO définie dans config.php
    }*/
    
    public function getAllFormations() {
        global $pdo;
        $stmt =$pdo->query("SELECT * FROM formation");
        return $stmt->fetchAll();
    }

    public function addFormation($class, $date, $desc, $price, $url, $duration, $capacity, $image) {
        $targetDir = "C:\\xampp\\htdocs\\ProjetWeb\\View\\BackOffice\\uploads\\";
        $targetFile = $targetDir . basename($image["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $check = getimagesize($image["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if ($image["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                $stmt = $this->pdo->prepare("INSERT INTO formation (class_form, date_form, desc_form, price_form, url_form, duration_form, capacity_form, image_form) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$class, $date, $desc, $price, $url, $duration, $capacity, $targetFile])) {
                    return true;
                } else {
                    echo "Failed to insert into database.";
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        return false;
    }

    public function updateFormation($id_form, $class_form, $date_form, $desc_form, $price_form, $url_form, $duration_form, $capacity_form, $image = null) {
        $sql = "UPDATE formation SET class_form = ?, date_form = ?, desc_form = ?, price_form = ?, url_form = ?, duration_form = ?, capacity_form = ?";
        $params = [$class_form, $date_form, $desc_form, $price_form, $url_form, $duration_form, $capacity_form];

        if ($image) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($image["name"]);
            move_uploaded_file($image["tmp_name"], $targetFile);
            $sql .= ", image_form = ?";
            $params[] = $targetFile;
        }

        $sql .= " WHERE id_form = ?";
        $params[] = $id_form;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteFormation($id_form) {
        $stmt = $this->pdo->prepare("DELETE FROM formation WHERE id_form = ?");
        return $stmt->execute([$id_form]);
    }

    public function getFormationById($id_form) {
        $stmt = $this->pdo->prepare("SELECT * FROM formation WHERE id_form = ?");
        $stmt->execute([$id_form]);
        return $stmt->fetch();
    }

    public function getWidgets() {
        $formations = $this->getAllFormations();
        $widgets = [];

        foreach ($formations as $formation) {
            $widgets[] = [
                'title' => 'Formation ' . htmlspecialchars($formation['id_form']),
                'content' => 'Class: ' . htmlspecialchars($formation['class_form']) . '<br>Date: ' . htmlspecialchars($formation['date_form']) . '<br>Desc: ' . htmlspecialchars($formation['desc_form']) . '<br>Price: ' . htmlspecialchars($formation['price_form']) . '<br>URL: ' . htmlspecialchars($formation['url_form']) . '<br>Duration: ' . htmlspecialchars($formation['duration_form']) . '<br>Capacity: ' . htmlspecialchars($formation['capacity_form']) . '<br><img src="' . htmlspecialchars($formation['image_form']) . '" alt="Formation Image" style="width:100px;height:auto;">'
            ];
        }

        return $widgets;
    }
}
