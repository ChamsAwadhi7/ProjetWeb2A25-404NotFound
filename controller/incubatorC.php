<?php

include_once __DIR__ . '../../config.php'; 
include_once __DIR__ . '../../model/startup.php';
//nitro
class nitroC
{
    public function listnitro()
    {
        $sql = "SELECT id_nitro, nitro_name, nitro_price, nitro_period FROM nitro";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listnitrobyid($id)
{
    $sql = "SELECT nitro_name FROM nitro WHERE id_nitro = :id";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute([
            'id' => $id,
        ]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage());
    }
}

    public function deletenitro($id_nitro)
    {
        $sql = "DELETE FROM nitro WHERE id_nitro = :id_nitro";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id_nitro' => $id_nitro]);
            error_log("Nitro deleted successfully: ID {$id_nitro}");
        } catch (Exception $e) {
            error_log("Error deleting nitro: " . $e->getMessage());
            throw new Exception('Error deleting nitro: ' . $e->getMessage());
        }
    }

    public function addnitro($nitro)
    {
        $sql = "INSERT INTO nitro (id_nitro, nitro_name, nitro_price, nitro_period)
                VALUES (:id_nitro, :nitro_name, :nitro_price, :nitro_period)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_nitro' => $nitro->getnitro_id(),
                'nitro_name' => $nitro->getnitro_name(),
                'nitro_price' => $nitro->getnitro_price(),
                'nitro_period' => $nitro->getnitro_period(),
            ]);
            error_log("Nitro added successfully with ID: " . $nitro->getnitro_id());
        } catch (Exception $e) {
            error_log("Error adding nitro: " . $e->getMessage());
            throw new Exception('Error adding nitro: ' . $e->getMessage());
        }
    }

    public function updatenitro($nitro, $id_nitro)
    {
        $sql = "UPDATE nitro SET 
                    nitro_name = :nitro_name, 
                    nitro_price = :nitro_price,
                    nitro_period = :nitro_period
                WHERE id_nitro = :id_nitro";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_nitro' => $id_nitro,
                'nitro_name' => $nitro->getnitro_name(),
                'nitro_price' => $nitro->getnitro_price(),
                'nitro_period' => $nitro->getnitro_period(),
            ]);
            error_log($query->rowCount() . " record(s) UPDATED successfully");
        } catch (PDOException $e) {
            error_log("Error updating nitro: " . $e->getMessage());
            throw new Exception('Error updating nitro: ' . $e->getMessage());
        }
    }
}

//workingspace

    class workingspaceC
{
    public function listworkingspace()
    {
        $sql = "SELECT id_workingspace, nom_workingspace, surface, prix_workingspace, capacite_workingspace, localisation FROM workingspace";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deleteworkingspace($id_workingspace)
    {
        $sql = "DELETE FROM workingspace WHERE id_workingspace = :id_workingspace";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id_workingspace' => $id_workingspace]);
            error_log("Workingspace deleted successfully: ID {$id_workingspace}");
        } catch (Exception $e) {
            error_log("Error deleting workingspace: " . $e->getMessage());
            throw new Exception('Error deleting workingspace: ' . $e->getMessage());
        }
    }

    public function addworkingspace($workingspace)
    {
        $sql = "INSERT INTO workingspace (id_workingspace, nom_workingspace, surface, prix_workingspace, capacite_workingspace, localisation)
                VALUES (:id_workingspace, :nom_workingspace, :surface, :prix_workingspace, :capacite_workingspace, :localisation)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_workingspace' => $workingspace->getid_workingspace(),
                'nom_workingspace' => $workingspace->getnom_workingspace(),
                'surface' => $workingspace->getsurface(),
                'prix_workingspace' => $workingspace->getprix_workingspace(),
                'capacite_workingspace' => $workingspace->getcapacite_workingspace(),
                'localisation' => $workingspace->getlocalisation(),
            ]);
            error_log("Workingspace added successfully with ID: " . $workingspace->getid_workingspace());
        } catch (Exception $e) {
            error_log("Error adding workingspace: " . $e->getMessage());
            throw new Exception('Error adding workingspace: ' . $e->getMessage());
        }
    }

    public function updateworkingspace($workingspace, $id_workingspace)
    {
        $sql = "UPDATE workingspace SET 
                    nom_workingspace = :nom_workingspace, 
                    surface = :surface,
                    prix_workingspace = :prix_workingspace,
                    capacite_workingspace = :capacite_workingspace,
                    localisation = :localisation
                WHERE id_workingspace = :id_workingspace";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_workingspace' => $id_workingspace,
                'nom_workingspace' => $workingspace->getnom_workingspace(),
                'surface' => $workingspace->getsurface(),
                'prix_workingspace' => $workingspace->getprix_workingspace(),
                'capacite_workingspace' => $workingspace->getcapacite_workingspace(),
                'localisation' => $workingspace->getlocalisation(),
            ]);
            error_log($query->rowCount() . " record(s) UPDATED successfully");
        } catch (PDOException $e) {
            error_log("Error updating workingspace: " . $e->getMessage());
            throw new Exception('Error updating workingspace: ' . $e->getMessage());
        }
    }
}
// workshop

class workshopC
{
    public function listworkshop()
    {
        $sql = "SELECT id_workshop, nom_workshop, date_workshop, lieu_workshop, sujet_workshop, responsable FROM workshop";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deleteworkshop($id_workshop)
    {
        $sql = "DELETE FROM workshop WHERE id_workshop = :id_workshop";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id_workshop' => $id_workshop]);
            error_log("Workshop deleted successfully: ID {$id_workshop}");
        } catch (Exception $e) {
            error_log("Error deleting workshop: " . $e->getMessage());
            throw new Exception('Error deleting workshop: ' . $e->getMessage());
        }
    }

    public function addworkshop($workshop)
    {
        $sql = "INSERT INTO workshop (id_workshop, nom_workshop, date_workshop, lieu_workshop, sujet_workshop, responsable)
                VALUES (:id_workshop, :nom_workshop, :date_workshop, :lieu_workshop, :sujet_workshop, :responsable)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_workshop' => $workshop->getid_workshop(),
                'nom_workshop' => $workshop->getnom_workshop(),
                'date_workshop' => $workshop->getdate_workshop(),
                'lieu_workshop' => $workshop->getlieu_workshop(),
                'sujet_workshop' => $workshop->getsujet_workshop(),
                'responsable' => $workshop->getresponsable(),
            ]);
            error_log("Workshop added successfully with ID: " . $workshop->getid_workshop());
        } catch (Exception $e) {
            error_log("Error adding workshop: " . $e->getMessage());
            throw new Exception('Error adding workshop: ' . $e->getMessage());
        }
    }

    public function updateworkshop($workshop, $id_workshop)
    {
        $sql = "UPDATE workshop SET 
                    nom_workshop = :nom_workshop, 
                    date_workshop = :date_workshop,
                    lieu_workshop = :lieu_workshop,
                    sujet_workshop = :sujet_workshop,
                    responsable = :responsable
                WHERE id_workshop = :id_workshop";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_workshop' => $id_workshop,
                'nom_workshop' => $workshop->getnom_workshop(),
                'date_workshop' => $workshop->getdate_workshop(),
                'lieu_workshop' => $workshop->getlieu_workshop(),
                'sujet_workshop' => $workshop->getsujet_workshop(),
                'responsable' => $workshop->getResponsable(),
            ]);
            error_log($query->rowCount() . " record(s) UPDATED successfully");
        } catch (PDOException $e) {
            error_log("Error updating workshop: " . $e->getMessage());
            throw new Exception('Error updating workshop: ' . $e->getMessage());
        }
    }
}

?>