<?php
require_once '../config.php';

class Commentaire {
    public static function getDerniersCommentaires($limit = 10) {
        global $pdo;

        try {
            $query = "SELECT cours_id, idUser, commentaire 
                      FROM commentaires 
                      ORDER BY DateAjout DESC 
                      LIMIT ?";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
