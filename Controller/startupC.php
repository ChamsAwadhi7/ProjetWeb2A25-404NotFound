<?php

require 'C:/xampp/htdocs/4Validation/config.php'; // Assure-toi que ce chemin est correct
include_once __DIR__ . '../../Model/startup.php';

class startupC
{
    private $pdo;

    public function __construct()
    {
        global $pdo; // Récupère l'objet PDO défini globalement
        $this->pdo = $pdo;
    }

    public function liststartup()
    {
        $sql = "SELECT startup_id_id, nom_startup,utilisateur_id, but_startup, desc_startup, date_startup, img_startup, nitro FROM startup";
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function deletestartup($startup_id_id)
    {
        $sql = "DELETE FROM startup WHERE startup_id_id = :startup_id_id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['startup_id_id' => $startup_id_id]);
            error_log("Startup supprimée avec succès : ID {$startup_id_id}");
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression : " . $e->getMessage());
            throw $e;
        }
    }

    public function addstartup($startup)
    {
        $sql = "INSERT INTO startup (startup_id_id, nom_startup, but_startup, desc_startup, date_startup, img_startup, utilisateur_id)
                VALUES (:startup_id_id, :nom_startup, :but_startup, :desc_startup, :date_startup, :img_startup , utilisateur_id)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'startup_id_id' => $startup->getStartupIdId(),
                'nom_startup' => $startup->getNomStartup(),
                'but_startup' => $startup->getButStartup(),
                'desc_startup' => $startup->getDescStartup(),
                'date_startup' => $startup->getDateStartup(),
                'img_startup' => $startup->getImgStartup(),
                'utilisateur_id' => $startup->getUtilisateur_id(),
            ]);
            error_log("Startup ajoutée avec succès avec ID : " . $startup->getStartupIdId());
        } catch (Exception $e) {
            error_log("Erreur lors de l'ajout : " . $e->getMessage());
            throw $e;
        }
    }

    public function updatestartup($startup, $startup_id_id)
    {
        $sql = "UPDATE startup SET 
                    nom_startup = :nom_startup, 
                    prenom_hoster = :prenom_hoster,
                    but_startup = :but_startup,
                    desc_startup = :desc_startup,
                    date_startup = :date_startup,
                    img_startup = :img_startup
                WHERE startup_id_id = :startup_id_id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'startup_id_id' => $startup_id_id,
                'nom_startup' => $startup->getNomStartup(),
                'but_startup' => $startup->getButStartup(),
                'desc_startup' => $startup->getDescStartup(),
                'date_startup' => $startup->getDateStartup(),
                'img_startup' => $startup->getImgStartup(),
            ]);
            error_log($stmt->rowCount() . " enregistrement(s) modifié(s).");
        } catch (PDOException $e) {
            error_log("Erreur mise à jour startup : " . $e->getMessage());
            throw $e;
        }
    }

    public function affectNitro($id_nitro, $nom)
    {
        $sql = "UPDATE startup SET nitro = :nitro WHERE nom_startup = :nom_startup";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nitro' => $id_nitro,
                'nom_startup' => $nom,
            ]);
            error_log($stmt->rowCount() . " enregistrement(s) modifié(s) avec nitro.");
        } catch (PDOException $e) {
            error_log("Erreur nitro : " . $e->getMessage());
            throw $e;
        }
    }
}
?>
