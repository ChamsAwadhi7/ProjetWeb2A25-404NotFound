<?php

include_once __DIR__ . '/../config.php'; 
include_once __DIR__ . '/../Model/startup.php';

class startupC
{
    private $db;

    public function __construct()
    {
        global $pdo; // Utilise le PDO global défini dans config.php
        $this->db = $pdo;
    }

    public function liststartup()
    {
        $sql = "SELECT startup_id_id, nom_startup, nom_hoster, prenom_hoster, but_startup, desc_startup, date_startup, img_startup, nitro FROM startup";
        try {
            $liste = $this->db->query($sql);
            return $liste->fetchAll();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function liststartupWithLimit($start, $limit)
    {
        $sql = "SELECT * FROM startup LIMIT :start, :limit";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':start', (int)$start, PDO::PARAM_INT);
            $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deletestartup($startup_id_id)
    {
        $sql = "DELETE FROM startup WHERE startup_id_id = :startup_id_id";
        try {
            $query = $this->db->prepare($sql);
            $query->execute(['startup_id_id' => $startup_id_id]);
            error_log("Startup supprimée avec succès : ID {$startup_id_id}");
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression : " . $e->getMessage());
            throw new Exception('Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function addstartup($startup)
    {
        $sql = "INSERT INTO startup (startup_id_id, nom_startup, nom_hoster, prenom_hoster, but_startup, desc_startup, date_startup, img_startup)
                VALUES (:startup_id_id, :nom_startup, :nom_hoster, :prenom_hoster, :but_startup, :desc_startup, :date_startup, :img_startup)";
        try {
            $query = $this->db->prepare($sql);
            $query->execute([
                'startup_id_id' => $startup->getStartupIdId(),
                'nom_startup' => $startup->getNomStartup(),
                'nom_hoster' => $startup->getNomHoster(),
                'prenom_hoster' => $startup->getPrenomHoster(),
                'but_startup' => $startup->getButStartup(),
                'desc_startup' => $startup->getDescStartup(),
                'date_startup' => $startup->getDateStartup(),
                'img_startup' => $startup->getImgStartup(),
            ]);
            error_log("Startup ajoutée avec succès : " . $startup->getStartupIdId());
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout : " . $e->getMessage());
            throw new Exception("Erreur lors de l'ajout : " . $e->getMessage());
        }
    }

    public function updatestartup($startup, $startup_id_id)
    {
        $sql = "UPDATE startup SET 
                    nom_startup = :nom_startup, 
                    nom_hoster = :nom_hoster,
                    prenom_hoster = :prenom_hoster,
                    but_startup = :but_startup,
                    desc_startup = :desc_startup,
                    date_startup = :date_startup,
                    img_startup = :img_startup
                WHERE startup_id_id = :startup_id_id";
        try {
            $query = $this->db->prepare($sql);
            $query->execute([
                'startup_id_id' => $startup_id_id,
                'nom_startup' => $startup->getNomStartup(),
                'nom_hoster' => $startup->getNomHoster(),
                'prenom_hoster' => $startup->getPrenomHoster(),
                'but_startup' => $startup->getButStartup(),
                'desc_startup' => $startup->getDescStartup(),
                'date_startup' => $startup->getDateStartup(),
                'img_startup' => $startup->getImgStartup(),
            ]);
            error_log($query->rowCount() . " ligne(s) mise(s) à jour avec succès");
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour : " . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

    public function affectNitro($id_nitro, $nom)
    {
        $sql = "UPDATE startup SET 
                    nitro = :nitro
                WHERE nom_startup = :nom_startup";
        try {
            $query = $this->db->prepare($sql);
            $query->execute([
                'nom_startup' => $nom,
                'nitro' => $id_nitro,
            ]);
            error_log($query->rowCount() . " ligne(s) mise(s) à jour avec succès (affectation Nitro)");
        } catch (PDOException $e) {
            error_log("Erreur lors de l'affectation de Nitro : " . $e->getMessage());
            throw new Exception("Erreur lors de l'affectation de Nitro : " . $e->getMessage());
        }
    }
}
?>
