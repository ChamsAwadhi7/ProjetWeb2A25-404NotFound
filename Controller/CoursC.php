<?php
require 'C:/xampp/htdocs/4Validation/config.php';
require_once '../../Model/Cours.php';

//require_once '../Commentaire.php';

class CoursController {
    public $message = "";

    // Fonction pour afficher les cours
    public function afficherCours() {
        // Création d'une instance du modèle Cours
        $coursModel = new Cours();
        
        // Appel de la méthode getAllCourses() du modèle pour récupérer les cours
        $cours = $coursModel->getAllCourses();
        
        // Retourner les cours récupérés
        return $cours;
    }

    public function ajouterCours($data, $files) {
        global $pdo;
        $titre = $data['courseName'] ?? '';
        $description = $data['courseDescription'] ?? '';
        $prix = $data['coursePrix'] ?? 0;
        $date = date("Y-m-d");

        $imgCoverPath = $this->uploadFile($files['imgCover'], "uploads/covers/");
        if (!$imgCoverPath) return;

        $exportPath = $this->uploadFile($files['courseExport'], "uploads/");
        if (!$exportPath) {
            $this->message = "❌ Veuillez sélectionner un fichier exporté.";
            return;
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO cours (DateAjout, Titre, Description, Notes, NbrVu, Prix, Exportation, ImgCover)
                                   VALUES (?, ?, ?, 0, 0, ?, ?, ?)");
            $stmt->execute([$date, $titre, $description, $prix, $exportPath, $imgCoverPath]);
            $this->message = "✅ Cours ajouté avec succès.";
        } catch (PDOException $e) {
            $this->message = "❌ Erreur SQL : " . $e->getMessage();
        }
    }


    public function supprimerCours($id) {
        try {
            global $pdo;
    
            // Commencer une transaction
            $pdo->beginTransaction();
    
            // Supprimer les commentaires associés au cours
            $stmt = $pdo->prepare("DELETE FROM commentaires WHERE cours_id = ?");
            $stmt->execute([$id]);
    
            // Supprimer le cours
            $stmt = $pdo->prepare("DELETE FROM cours WHERE id = ?");
            $stmt->execute([$id]);
    
            // Valider la transaction
            $pdo->commit();
    
            $this->message = "✅ Cours et commentaires supprimés avec succès.";
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();
            $this->message = "❌ Erreur suppression : " . $e->getMessage();
        }
    }
    


    public function chercherCours(string $tri, string $search): array {
        global $pdo;
    
        // Sécurité : limiter les valeurs possibles de tri à celles autorisées
        $allowedSorts = [
            'date' => 'DateAjout DESC',
            'note' => 'Notes DESC',
            'vues' => 'NbrVu DESC',
            'id'   => 'id DESC'
        ];
        $orderBy = $allowedSorts[$tri] ?? $allowedSorts['id'];
    
        // Préparer les conditions
        $params = [];
        $whereClauses = [];
    
        if (!empty(trim($search))) {
            $whereClauses[] = 'Titre LIKE :search';
            $params['search'] = '%' . trim($search) . '%';
        }
    
        // Construction de la requête
        $whereSQL = count($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
        $sql = "SELECT * FROM cours $whereSQL ORDER BY $orderBy";
    
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans chercherCours : " . $e->getMessage());
            $this->message = "❌ Erreur lors de la récupération des cours.";
            return [];
        }
    }
    

    private function uploadFile($file, $dir) {
        if (!isset($file) || $file['error'] !== 0) {
            $this->message = "❌ Fichier invalide.";
            return false;
        }

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $filePath = $dir . time() . "_" . basename($file["name"]);
        if (!move_uploaded_file($file["tmp_name"], $filePath)) {
            $this->message = "❌ Échec de l'upload.";
            return false;
        }

        return $filePath;
    }

// Méthode pour récupérer un cours par son ID
public function getCoursById($id) {
    global $pdo;
    $query = "SELECT * FROM cours WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Mise à jour du cours
public function updateCours($id, $titre, $prix, $description, $files) {
    global $pdo;
    // Télécharger l'image de couverture (ImgCover)
    $imgCoverPath = null;
    if (isset($files['imgCover']) && $files['imgCover']['error'] === 0) {
        $imgCoverPath = $this->uploadFile($files['imgCover'], "uploads/covers/");
        if (!$imgCoverPath) {
            $this->message = "❌ Échec du téléchargement de l'image de couverture.";
            return;
        }
    }
    // Télécharger le fichier d'exportation (Exportation)
    $exportPath = null;
    if (isset($files['courseExport']) && $files['courseExport']['error'] === 0) {
        $exportPath = $this->uploadFile($files['courseExport'], "uploads/");
        if (!$exportPath) {
            $this->message = "❌ Échec du téléchargement du fichier d'exportation.";
            return;
        }
    }
    // Préparer la requête de mise à jour avec les nouveaux fichiers
    $query = "UPDATE cours SET Titre = ?, Prix = ?, Description = ?";
    // Ajouter les colonnes ImgCover et Exportation si les fichiers sont présents
    if ($imgCoverPath) {
        $query .= ", ImgCover = ?";
    }
    if ($exportPath) {
        $query .= ", Exportation = ?";
    }

    $query .= " WHERE id = ?";

    // Exécuter la requête
    try {
        $stmt = $pdo->prepare($query);
        $params = [$titre, $prix, $description];

        // Ajouter les chemins des fichiers si disponibles
        if ($imgCoverPath) {
            $params[] = $imgCoverPath;
        }
        if ($exportPath) {
            $params[] = $exportPath;
        }

        // Ajouter l'ID du cours à la fin
        $params[] = $id;

        // Exécuter la requête
        $stmt->execute($params);
        $this->message = "✅ Cours mis à jour avec succès";
    } catch (PDOException $e) {
        $this->message = "❌ Erreur lors de la mise à jour du cours : " . $e->getMessage();
    }
}



// Méthode pour obtenir les statistiques
public function getStatistiques() {
    global $pdo;

    try {
        // Nombre total de cours
        $totalCoursQuery = "SELECT COUNT(*) AS total FROM cours";
        $totalCoursResult = $pdo->query($totalCoursQuery)->fetch(PDO::FETCH_ASSOC);
        $totalCours = $totalCoursResult['total'] ?? 0;

        // Prix moyen des cours
        $prixMoyenQuery = "SELECT AVG(Prix) AS prixMoyen FROM cours";
        $prixMoyenResult = $pdo->query($prixMoyenQuery)->fetch(PDO::FETCH_ASSOC);
        $prixMoyen = $prixMoyenResult['prixMoyen'] ?? 0;

        return [
            'totalCours' => (int)$totalCours,
            'prixMoyen' => round((float)$prixMoyen, 2),
        ];
    } catch (PDOException $e) {
        $this->message = "❌ Erreur statistiques : " . $e->getMessage();
        return [
            'totalCours' => 0,
            'prixMoyen' => 0,
        ];
    }
}


public function getCoursLePlusPopulaire() {
    global $pdo;
    $sql = "SELECT * FROM cours ORDER BY NbrVu DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Méthode pour récupérer les derniers commentaires
public function getDerniersCommentaires($limit = 5) {
    global $pdo;

    try {
        $query = "SELECT cours_id, idUser, commentaire 
                  FROM commentaires 
                  ORDER BY date DESC 
                  LIMIT ?";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $this->message = "❌ Erreur récupération des commentaires : " . $e->getMessage();
        return [];
    }
}

function updateMoyenneNote($id_cours, $pdo) {
    $stmt = $pdo->prepare("SELECT AVG(note) FROM notes WHERE id_cours = ?");
    $stmt->execute([$id_cours]);
    $moyenne = $stmt->fetchColumn();

    $stmt = $pdo->prepare("UPDATE cours SET Notes = ? WHERE id = ?");
    $stmt->execute([$moyenne, $id_cours]);
}



}
?>