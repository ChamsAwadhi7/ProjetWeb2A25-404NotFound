<?php
require_once '../config.php';
require_once '../model/Cours.php';
require_once '../controller/CoursC.php';

class CoursC
{
    // Ajouter un cours
    public function ajouterCours($cours)
    {
        global $pdo;
        try {
            $sql = "INSERT INTO cours (id, DateAjout, Titre, Description, Notes, NbrVu, Prix, ImgCover, Exportation)
                    VALUES (?, ?, ?, 0, 0, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $cours->getDateAjout(),
                $cours->getTitre(),
                $cours->getDescription(),
                $cours->getPrix(),
                $cours->getExportation(),
                $cours->getImgCover()
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Supprimer un cours
    public function supprimerCours($id)
    {
        global $pdo;
        try {
            $sql = "DELETE FROM cours WHERE ID = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Afficher tous les cours
    public function afficherCours($tri = 'id', $search = '')
    {
        global $pdo;
        $orderBy = match ($tri) {
            'date' => 'DateAjout DESC',
            'note' => 'Notes DESC',
            'vues' => 'NbrVu DESC',
            default => 'id DESC'
        };

        $where = "";
        $params = [];

        if (!empty($search)) {
            $where = "WHERE Titre LIKE :search";
            $params['search'] = "%" . $search . "%";
        }

        try {
            $stmt = $pdo->prepare("SELECT * FROM cours $where ORDER BY $orderBy");
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Récupérer un cours par ID
    public function getCoursById($id)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT * FROM cours WHERE ID = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // Incrémenter le nombre de vues d’un cours
    public function incrementerVues($id)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("UPDATE cours SET NbrVu = NbrVu + 1 WHERE ID = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            // ignore silently
        }
    }
}
