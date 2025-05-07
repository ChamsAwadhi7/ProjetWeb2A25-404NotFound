<?php
require 'C:/xampp/htdocs/gestion_investissements/auth/config.php';





class InvestissementC {
    public function listInvestissements() {
        $sql = "SELECT * FROM investissements";
        $db = config::getConnexion();
        try {
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function listInvestissementsParUtilisateur($user_id)
    {
        $sql = "SELECT * FROM investissements WHERE user_id = :user_id";
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function getInvestissement(int $id)
    {
        try {
            $db = config::getConnexion();
            $sql = "SELECT * FROM investissements WHERE id_investissement = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Erreur lors de la récupération de l\'investissement : ' . $e->getMessage();
            return false;
        }
    }
  

    public function addInvestissement(
        int     $user_id,
        float   $montant,
        string  $date,
        int     $id_startups,
        ?string $date_fin = null,
        string  $type = 'carte',
        ?array  $ressourceData = null
    ): ?int {
        $db = config::getConnexion();

        try {
            $db->beginTransaction();

            // INSERT principal
            $sql = "
                INSERT INTO investissements
                    (user_id, montant_investissement, date, id_startups, date_fin, type_investissement)
                VALUES
                    (:user_id, :montant, :date, :id_startups, :date_fin, :type_investissement)
            ";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':montant', $montant);
            $stmt->bindValue(':date', $date, PDO::PARAM_STR);
            $stmt->bindValue(':id_startups', $id_startups, PDO::PARAM_INT);
            // Gérer correctement le NULL pour date_fin
            if (is_null($date_fin)) {
                $stmt->bindValue(':date_fin', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':date_fin', $date_fin, PDO::PARAM_STR);
            }
            $stmt->bindValue(':type_investissement', $type, PDO::PARAM_STR);
            $stmt->execute();

            $newId = (int) $db->lastInsertId();

            // Ressource supplémentaire
            if ($type === 'autre' && !empty($ressourceData)) {
                $sql2 = "
                    INSERT INTO autre_ressource
                        (id_investissement, type_ressource, caracteristique)
                    VALUES
                        (:id_inv, :type_res, :carac)
                ";
                $stmt2 = $db->prepare($sql2);
                $stmt2->bindValue(':id_inv', $newId, PDO::PARAM_INT);
                $stmt2->bindValue(':type_res', $ressourceData['type_ressource'], PDO::PARAM_STR);
                $stmt2->bindValue(':carac', $ressourceData['caracteristique'], PDO::PARAM_STR);
                $stmt2->execute();
            }

            $db->commit();
            return $newId;
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log('Erreur addInvestissement: ' . $e->getMessage());
            return null;
        }
    }
    
    public function updateInvestissement(
        int $id,
        float $montant,
        string $dateDebut,
        int $idStartups,
        ?string $dateFin = null,
        string $type = 'carte',
        ?array $ressourceData = null
    ): bool {
        $db = config::getConnexion();
        try {
            $db->beginTransaction();
    
            // 1) On met à jour la ligne dans `investissements`
            $sql = "
                UPDATE `investissements`
                   SET `montant_investissement`  = :montant,
                       `date`                    = :dateDebut,
                       `id_startups`             = :idStartups,
                       `date_fin`                = :dateFin,
                       `type_investissement`     = :typeInvest
                 WHERE `id_investissement`       = :idInvest
            ";
            $stmt = $db->prepare($sql);
            // liaison des paramètres
            $stmt->bindValue(':montant',      $montant,     PDO::PARAM_STR);
            $stmt->bindValue(':dateDebut',    $dateDebut,   PDO::PARAM_STR);
            $stmt->bindValue(':idStartups',   $idStartups,  PDO::PARAM_INT);
            // pour date_fin on gère bien le NULL
            if (empty($dateFin)) {
                $stmt->bindValue(':dateFin', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':dateFin', $dateFin, PDO::PARAM_STR);
            }
            $stmt->bindValue(':typeInvest',   $type,        PDO::PARAM_STR);
            $stmt->bindValue(':idInvest',     $id,          PDO::PARAM_INT);
            $stmt->execute();
    
            // 2) Le reste de ta gestion de `autre_ressource` inchangé...
            //    (vérification existence, update ou insert ou delete)
    
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            error_log("Erreur updateInvestissement: " . $e->getMessage());
            return false;
        }
    }
    

    public function deleteInvestissement($id)
    {
        $sql = "DELETE FROM investissements WHERE id_investissement = :id";
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    public function rechercherInvestissements(string $champ, string $valeur): array
    {
        // 1) Sécurité : limiter aux champs autorisés
        $allowed = ['user_id','type_investissement','id_startups'];
        if (!in_array($champ, $allowed)) {
            throw new InvalidArgumentException("Champ de recherche invalide");
        }

        // 2) Préparer la requête
        $sql = "SELECT * FROM investissements
                WHERE `$champ` LIKE :valeur
                ORDER BY date_fin ASC";  // ou tout autre ordre par défaut
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':valeur', "%{$valeur}%", PDO::PARAM_STR);
        $stmt->execute();

        // 3) Retourner le résultat
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function trierParMontantCroissant()
    {
        $sql = "SELECT * FROM investissements ORDER BY montant_investissement ASC";
        $db = config::getConnexion();
        try {
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function trierParMontantDecroissant()
    {
        $sql = "SELECT * FROM investissements ORDER BY montant_investissement DESC";
        $db = config::getConnexion();
        try {
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function trierParDateCroissante()
    {
        $sql = "SELECT * FROM investissements ORDER BY date ASC";
        $db = config::getConnexion();
        try {
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function trierParDateDecroissante()
    {
        $sql = "SELECT * FROM investissements ORDER BY date DESC";
        $db = config::getConnexion();
        try {
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    // ➡️ C'est ici qu'on doit ajouter la fonction !!
    public function getInvestissementsProchesEcheance(int $days = 15): array
    {
        $db = config::getConnexion();
        $sql = "
            SELECT *
            FROM investissements
            WHERE date_fin 
              BETWEEN CURDATE() 
                  AND DATE_ADD(CURDATE(), INTERVAL :days DAY)
            ORDER BY date_fin ASC
        ";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
// Dans InvestissementC.php
public function getInvestissementStats() {
    $sql = "SELECT type_investissement as type, 
            COUNT(*) as count, 
            (COUNT(*) / (SELECT COUNT(*) FROM investissements)) * 100 as percentage 
            FROM investissements 
            GROUP BY type_investissement";

    $db = config::getConnexion();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    
    
    
    
    
}


?>
