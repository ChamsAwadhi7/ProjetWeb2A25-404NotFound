<?php

include_once __DIR__ . '/../config.php'; 
include_once __DIR__ . '/../model/startup.php';

// nitro
class nitroC
{
    public function listnitro()
    {
        global $pdo;
        $sql = "SELECT id_nitro, nitro_name, nitro_price, nitro_period FROM nitro";
        try {
            $liste = $pdo->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listnitrobyid($id)
    {
        global $pdo;
        $sql = "SELECT nitro_name FROM nitro WHERE id_nitro = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deletenitro($id_nitro)
    {
        global $pdo;
        $sql = "DELETE FROM nitro WHERE id_nitro = :id_nitro";
        try {
            $query = $pdo->prepare($sql);
            $query->execute(['id_nitro' => $id_nitro]);
        } catch (Exception $e) {
            throw new Exception('Error deleting nitro: ' . $e->getMessage());
        }
    }

    public function addnitro($nitro)
    {
        global $pdo;
        $sql = "INSERT INTO nitro (id_nitro, nitro_name, nitro_price, nitro_period)
                VALUES (:id_nitro, :nitro_name, :nitro_price, :nitro_period)";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                'id_nitro' => $nitro->getnitro_id(),
                'nitro_name' => $nitro->getnitro_name(),
                'nitro_price' => $nitro->getnitro_price(),
                'nitro_period' => $nitro->getnitro_period(),
            ]);
        } catch (Exception $e) {
            throw new Exception('Error adding nitro: ' . $e->getMessage());
        }
    }

    public function updatenitro($nitro, $id_nitro)
    {
        global $pdo;
        $sql = "UPDATE nitro SET 
                    nitro_name = :nitro_name, 
                    nitro_price = :nitro_price,
                    nitro_period = :nitro_period
                WHERE id_nitro = :id_nitro";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                'id_nitro' => $id_nitro,
                'nitro_name' => $nitro->getnitro_name(),
                'nitro_price' => $nitro->getnitro_price(),
                'nitro_period' => $nitro->getnitro_period(),
            ]);
        } catch (PDOException $e) {
            throw new Exception('Error updating nitro: ' . $e->getMessage());
        }
    }
}

// workingspace
class workingspaceC
{
    public function listworkingspace()
    {
        global $pdo;
        $sql = "SELECT id_workingspace, nom_workingspace, surface, prix_workingspace, capacite_workingspace, localisation FROM workingspace";
        try {
            $liste = $pdo->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deleteworkingspace($id_workingspace)
    {
        global $pdo;
        $sql = "DELETE FROM workingspace WHERE id_workingspace = :id_workingspace";
        try {
            $query = $pdo->prepare($sql);
            $query->execute(['id_workingspace' => $id_workingspace]);
        } catch (Exception $e) {
            throw new Exception('Error deleting workingspace: ' . $e->getMessage());
        }
    }

    public function addworkingspace($workingspace)
    {
        global $pdo;
        $sql = "INSERT INTO workingspace (id_workingspace, nom_workingspace, surface, prix_workingspace, capacite_workingspace, localisation)
                VALUES (:id_workingspace, :nom_workingspace, :surface, :prix_workingspace, :capacite_workingspace, :localisation)";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                'id_workingspace' => $workingspace->getid_workingspace(),
                'nom_workingspace' => $workingspace->getnom_workingspace(),
                'surface' => $workingspace->getsurface(),
                'prix_workingspace' => $workingspace->getprix_workingspace(),
                'capacite_workingspace' => $workingspace->getcapacite_workingspace(),
                'localisation' => $workingspace->getlocalisation(),
            ]);
        } catch (Exception $e) {
            throw new Exception('Error adding workingspace: ' . $e->getMessage());
        }
    }

    public function updateworkingspace($workingspace, $id_workingspace)
    {
        global $pdo;
        $sql = "UPDATE workingspace SET 
                    nom_workingspace = :nom_workingspace, 
                    surface = :surface,
                    prix_workingspace = :prix_workingspace,
                    capacite_workingspace = :capacite_workingspace,
                    localisation = :localisation
                WHERE id_workingspace = :id_workingspace";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                'id_workingspace' => $id_workingspace,
                'nom_workingspace' => $workingspace->getnom_workingspace(),
                'surface' => $workingspace->getsurface(),
                'prix_workingspace' => $workingspace->getprix_workingspace(),
                'capacite_workingspace' => $workingspace->getcapacite_workingspace(),
                'localisation' => $workingspace->getlocalisation(),
            ]);
        } catch (PDOException $e) {
            throw new Exception('Error updating workingspace: ' . $e->getMessage());
        }
    }
}

// workshop
class workshopC
{
    public function listworkshop()
    {
        global $pdo;
        $sql = "SELECT id_workshop, nom_workshop, date_workshop, lieu_workshop, sujet_workshop, responsable FROM workshop";
        try {
            $liste = $pdo->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deleteworkshop($id_workshop)
    {
        global $pdo;
        $sql = "DELETE FROM workshop WHERE id_workshop = :id_workshop";
        try {
            $query = $pdo->prepare($sql);
            $query->execute(['id_workshop' => $id_workshop]);
        } catch (Exception $e) {
            throw new Exception('Error deleting workshop: ' . $e->getMessage());
        }
    }

    public function addworkshop($workshop)
    {
        global $pdo;
        $sql = "INSERT INTO workshop (id_workshop, nom_workshop, date_workshop, lieu_workshop, sujet_workshop, responsable)
                VALUES (:id_workshop, :nom_workshop, :date_workshop, :lieu_workshop, :sujet_workshop, :responsable)";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                'id_workshop' => $workshop->getid_workshop(),
                'nom_workshop' => $workshop->getnom_workshop(),
                'date_workshop' => $workshop->getdate_workshop(),
                'lieu_workshop' => $workshop->getlieu_workshop(),
                'sujet_workshop' => $workshop->getsujet_workshop(),
                'responsable' => $workshop->getresponsable(),
            ]);
        } catch (Exception $e) {
            throw new Exception('Error adding workshop: ' . $e->getMessage());
        }
    }

    public function updateworkshop($workshop, $id_workshop)
    {
        global $pdo;
        $sql = "UPDATE workshop SET 
                    nom_workshop = :nom_workshop, 
                    date_workshop = :date_workshop,
                    lieu_workshop = :lieu_workshop,
                    sujet_workshop = :sujet_workshop,
                    responsable = :responsable
                WHERE id_workshop = :id_workshop";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                'id_workshop' => $id_workshop,
                'nom_workshop' => $workshop->getnom_workshop(),
                'date_workshop' => $workshop->getdate_workshop(),
                'lieu_workshop' => $workshop->getlieu_workshop(),
                'sujet_workshop' => $workshop->getsujet_workshop(),
                'responsable' => $workshop->getResponsable(),
            ]);
        } catch (PDOException $e) {
            throw new Exception('Error updating workshop: ' . $e->getMessage());
        }
    }
}

?>
