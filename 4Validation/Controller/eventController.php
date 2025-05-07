<?php
require 'C:/xampp/htdocs/4Validation/config.php';
include_once(__DIR__ . '/../Model/eventModel.php');

class EventC {

    
    
    public function listEvents() {
        global $pdo;
        $sql = "SELECT * FROM events ORDER BY date_event DESC";
        try {
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la récupération des événements : ' . $e->getMessage());
        }
    }

    public function listEventsAsc() {
        global $pdo;
        $sql = "SELECT * FROM events ORDER BY date_event ASC";
        try {
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la récupération des événements : ' . $e->getMessage());
        }
    }

    public function getEventById($id_event) {
        global $pdo;
        $sql = "SELECT * FROM events WHERE id_event = :id_event";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_event' => $id_event]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la récupération de l\'événement : ' . $e->getMessage());
        }
    }

    public function addEvent($event) {
        global $pdo;
        $sql = "INSERT INTO events (nom_event, date_event, desc_event, lieu, img_event)
                VALUES (:nom_event, :date_event, :desc_event, :lieu, :img_event)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nom_event'   => $event->getNomEvent(),
                'date_event'  => $event->getDateEvent(),
                'desc_event'  => $event->getDescEvent(),
                'lieu'        => $event->getLieuEvent(),
                'img_event'   => $event->getImgEvent()
            ]);
            return $pdo->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'ajout de l\'événement : ' . $e->getMessage());
        }
    }

    public function updateEvent($event, $id_event) {
        global $pdo;
        $sql = "UPDATE events SET 
                    nom_event = :nom_event,
                    date_event = :date_event,
                    desc_event = :desc_event,
                    lieu = :lieu,
                    img_event = :img_event
                WHERE id_event = :id_event";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id_event'    => $id_event,
                'nom_event'   => $event->getNomEvent(),
                'date_event'  => $event->getDateEvent(),
                'desc_event'  => $event->getDescEvent(),
                'lieu'        => $event->getLieuEvent(),
                'img_event'   => $event->getImgEvent()
            ]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function deleteEvent($id_event = null) {
        global $pdo;
        $today = date('Y-m-d');
        try {
            // Suppression auto des événements passés
            $stmtAuto = $pdo->prepare("DELETE FROM events WHERE date_event < :today");
            $stmtAuto->execute(['today' => $today]);
            $autoDeleted = $stmtAuto->rowCount();

            if ($id_event) {
                $stmt = $pdo->prepare("DELETE FROM events WHERE id_event = :id_event");
                $stmt->execute(['id_event' => $id_event]);
                return $stmt->rowCount();
            }

            return $autoDeleted;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function searchAndSortEvents($searchTerm, $sortOrder = 'ASC') {
        global $pdo;
        $order = ($sortOrder === 'desc') ? 'DESC' : 'ASC';
        $sql = "SELECT * FROM events 
                WHERE nom_event LIKE :search 
                OR desc_event LIKE :search 
                OR lieu LIKE :search 
                ORDER BY date_event $order";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['search' => "%$searchTerm%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Erreur de recherche : ' . $e->getMessage());
        }
    }

    public function searchEvents($searchTerm, $order = 'DESC') {
        global $pdo;
        $sql = "SELECT * FROM events 
                WHERE nom_event LIKE :search 
                OR desc_event LIKE :search 
                OR lieu LIKE :search
                ORDER BY date_event $order";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['search' => "%$searchTerm%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Erreur de recherche : ' . $e->getMessage());
        }
    }

    // ----- Vous pouvez ajouter ici les fonctions de participation une fois nettoyées -----
}
