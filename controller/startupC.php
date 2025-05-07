<?php

include_once __DIR__ . '../../config.php'; 
include_once __DIR__ . '../../model/startup.php';

class startupC
{
    public function liststartup()
    {
        $sql = "SELECT startup_id_id, nom_startup, nom_hoster, prenom_hoster, but_startup, desc_startup, date_startup, img_startup,nitro FROM startup";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    public function liststartupWithLimit($start, $limit) {
        $sql = "SELECT * FROM startup LIMIT $start, $limit";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
    

    public function deletestartup($startup_id_id)
    {
        $sql = "DELETE FROM startup WHERE startup_id_id = :startup_id_id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['startup_id_id' => $startup_id_id]);
            error_log("Startup deleted successfully: ID {$startup_id_id}");
        } catch (Exception $e) {
            error_log("Error deleting startup: " . $e->getMessage());
            throw new Exception('Error deleting startup: ' . $e->getMessage());
        }
    }

    public function addstartup($startup)
    {
        $sql = "INSERT INTO startup (startup_id_id, nom_startup, nom_hoster, prenom_hoster, but_startup, desc_startup, date_startup, img_startup)
                VALUES (:startup_id_id, :nom_startup, :nom_hoster, :prenom_hoster, :but_startup, :desc_startup, :date_startup, :img_startup)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
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
            error_log("Startup added successfully with ID: " . $startup->getStartupIdId());
        } catch (Exception $e) {
            error_log("Error adding startup: " . $e->getMessage());
            throw new Exception('Error adding startup: ' . $e->getMessage());
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
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
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
            error_log($query->rowCount() . " record(s) UPDATED successfully");
        } catch (PDOException $e) {
            error_log("Error updating startup: " . $e->getMessage());
            throw new Exception('Error updating startup: ' . $e->getMessage());
        }
    }


    public function affectNitro($id_nitro, $nom)
    {
    $sql = "UPDATE startup SET 
                nitro = :nitro
            WHERE nom_startup = :nom_startup";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute([
            'nom_startup' =>$nom,
            'nitro' => $id_nitro,
        ]);
        error_log($query->rowCount() . " record(s) UPDATED successfully");
    } catch (PDOException $e) {
        error_log("Error updating startup: " . $e->getMessage());
        throw new Exception('Error updating startup: ' . $e->getMessage());
    }
}

}

?>
