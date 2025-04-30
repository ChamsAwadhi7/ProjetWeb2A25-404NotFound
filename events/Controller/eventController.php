<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../Model/eventModel.php');

class EventC {
    public function listEvents() {
        $sql = "SELECT * FROM events ORDER BY date_event DESC";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            throw new Exception('Error listing events: ' . $e->getMessage());
        }
    }

    public function getEventById($id_event) {
        $sql = "SELECT * FROM events WHERE id_event = :id_event";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id_event' => $id_event]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error fetching event: ' . $e->getMessage());
        }
    }

    public function addEvent($event) {
        $sql = "INSERT INTO events (nom_event, date_event, desc_event, lieu, img_event)
                VALUES (:nom_event, :date_event, :desc_event, :lieu, :img_event)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom_event' => $event->getNomEvent(),
                'date_event' => $event->getDateEvent(),
                'desc_event' => $event->getDescEvent(),
                'lieu' => $event->getLieuEvent(),
                'img_event' => $event->getImgEvent(),
            ]);
            return $db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Error adding event: ' . $e->getMessage());
        }
    }

    public function updateEvent($event, $id_event) {
        $sql = "UPDATE events SET 
                    nom_event = :nom_event,
                    date_event = :date_event,
                    desc_event = :desc_event,
                    lieu = :lieu,
                    img_event = :img_event
                WHERE id_event = :id_event";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_event' => $id_event,
                'nom_event' => $event->getNomEvent(),
                'date_event' => $event->getDateEvent(),
                'desc_event' => $event->getDescEvent(),
                'lieu' => $event->getLieuEvent(), // fixed key
                'img_event' => $event->getImgEvent(),
            ]);
            return $query->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Error updating event: ' . $e->getMessage());
        }
    }
    

    public function deleteEvent($id_event = null) {
        $db = config::getConnexion();
        
        // Suppression automatique des événements dont la date est passée
        $today = date('Y-m-d');  // La date d'aujourd'hui
    
        // SQL pour supprimer les événements dont la date est antérieure à aujourd'hui
        $sql_auto_delete = "DELETE FROM events WHERE date_event < :today";
        try {
            // Suppression des événements dépassés (automatique)
            $query_auto = $db->prepare($sql_auto_delete);
            $query_auto->execute(['today' => $today]);
            
            // Si un id_event est passé en paramètre, suppression manuelle de cet événement
            if ($id_event) {
                $sql = "DELETE FROM events WHERE id_event = :id_event";
                $query = $db->prepare($sql);
                $query->execute(['id_event' => $id_event]);
                return $query->rowCount();  // Retourner le nombre de lignes affectées
            }
    
            // Retourner le nombre de lignes affectées par la suppression automatique
            return $query_auto->rowCount();
        } catch (Exception $e) {
            throw new Exception('Error deleting event: ' . $e->getMessage());
        }
    }
    
    public function searchAndSortEvents($searchTerm, $sortOrder) {
        $sql = "SELECT * FROM events 
                WHERE nom_event LIKE :search 
                OR desc_event LIKE :search 
                OR lieu LIKE :search 
                ORDER BY date_event " . ($sortOrder === 'desc' ? 'DESC' : 'ASC');
        
        $db = config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            $query->execute(['search' => "%$searchTerm%"]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error searching and sorting events: ' . $e->getMessage());
        }
    }
    
    

    public function searchEvents($searchTerm) {
        // Requête SQL pour rechercher les événements dont le nom, la description ou le lieu correspondent partiellement ou complètement
        $sql = "SELECT * FROM events 
                WHERE nom_event LIKE :search 
                OR desc_event LIKE :search 
                OR lieu LIKE :search
                ORDER BY date_event DESC";
        
        // Connexion à la base de données
        $db = config::getConnexion();
        
        try {
            // Préparation de la requête SQL
            $query = $db->prepare($sql);
            
            // Exécution de la requête avec le paramètre de recherche
            // %$searchTerm% permet de rechercher tous les événements qui contiennent le terme recherché, 
            // qu'il soit au début, au milieu ou à la fin du champ.
            $query->execute(['search' => "%$searchTerm%"]);
            
            // Retourner tous les résultats de la recherche sous forme de tableau associatif
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Gestion des erreurs
            throw new Exception('Error searching events: ' . $e->getMessage());
        }
    }
    public function searchEventsDesc($searchTerm) {
        $sql = "SELECT * FROM events 
                WHERE nom_event LIKE :search 
                OR desc_event LIKE :search 
                OR lieu LIKE :search
                ORDER BY date_event DESC";  // Les événements les plus récents en premier
        
        $db = config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            $query->execute(['search' => "%$searchTerm%"]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error searching events: ' . $e->getMessage());
        }
    }
    public function searchEventsAsc($searchTerm) {
        $sql = "SELECT * FROM events 
                WHERE nom_event LIKE :search 
                OR desc_event LIKE :search 
                OR lieu LIKE :search
                ORDER BY date_event ASC";  // Les événements les plus anciens en premier
        
        $db = config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            $query->execute(['search' => "%$searchTerm%"]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error searching events: ' . $e->getMessage());
        }
    }
    public function listEventsAsc() {
        $sql = "SELECT * FROM events ORDER BY date_event ASC";  // Les événements les plus anciens en premier
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            throw new Exception('Error listing events: ' . $e->getMessage());
        }
    }
    
    /*
    public function addParticipation($id_event, $id_user) {
        $db = config::getConnexion();
        
        try {
            // 1. Verify connection
            if (!$db) {
                throw new Exception("Database connection failed");
            }
    
            // 2. Validate and log IDs
            error_log("Attempting to add participation - Event: $id_event, User: $id_user");
            $id_event = (int)$id_event;
            $id_user = (int)$id_user;
    
            // 3. Verify event exists (with debug)
            $checkEvent = $db->prepare("SELECT id_event, nom_event FROM events WHERE id_event = ?");
            $checkEvent->execute([$id_event]);
            $event = $checkEvent->fetch(PDO::FETCH_ASSOC);
            
            if (!$event) {
                error_log("Event $id_event does not exist");
                throw new Exception("Événement introuvable");
            }
            error_log("Found event: ".$event['nom_event']);
    
            // 4. Verify user exists
            $checkUser = $db->prepare("SELECT id_user, nom_user FROM user WHERE id_user = ?");
            $checkUser->execute([$id_user]);
            $user = $checkUser->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                error_log("User $id_user does not exist");
                throw new Exception("Utilisateur introuvable");
            }
            error_log("Found user: ".$user['nom_user']);
    
            // 5. Check existing participation
            $checkParticipation = $db->prepare("SELECT id_participation FROM rejoindre 
                                              WHERE id_event = ? AND id_user = ?");
            $checkParticipation->execute([$id_event, $id_user]);
            
            if ($checkParticipation->fetch()) {
                error_log("Participation already exists");
                throw new Exception("Vous êtes déjà inscrit à cet événement");
            }
    
            // 6. Insert new participation
            $insert = $db->prepare("INSERT INTO rejoindre 
                                  (id_event, id_user, statut_participation, date_participation) 
                                  VALUES (?, ?, 'en attente', NOW())");
            $insert->execute([$id_event, $id_user]);
            
            // 7. Verify insertion
            $lastId = $db->lastInsertId();
            error_log("New participation ID: $lastId");
            
            if ($insert->rowCount() === 0) {
                throw new Exception("Insertion failed silently");
            }
    
            return "Inscription réussie (ID: $lastId)";
    
        } catch (PDOException $e) {
            error_log("PDO Error: ".$e->getMessage());
            
            // Handle specific constraints
            if (strpos($e->getMessage(), 'unique_participation') !== false) {
                return "Vous êtes déjà inscrit à cet événement";
            }
            if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
                return "Événement ou utilisateur invalide";
            }
            
            return "Erreur technique lors de l'inscription";
        }
    }
    
    
    public function updateParticipationStatus($id_participation, $status) {
        $sql = "UPDATE rejoindre SET statut_participation = :status 
                WHERE id_participation = :id_participation";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_participation' => $id_participation,
                'status' => $status
            ]);
            return $query->rowCount();
        } catch (Exception $e) {
            throw new Exception('Error updating participation: ' . $e->getMessage());
        }
    }
    
    public function listParticipations() {
        $db = config::getConnexion();
        $sql = "SELECT p.*, e.nom_event, u.nom_user, u.prenom_user 
                FROM rejoindre p
                JOIN events e ON p.id_event = e.id_event
                JOIN user u ON p.id_user = u.id_user
                ORDER BY p.date_participation DESC";
        
        $query = $db->prepare($sql);
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    public function deleteParticipation($id_participation) {
        $sql = "DELETE FROM rejoindre WHERE id_participation = :id_participation";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id_participation' => $id_participation]);
            return $query->rowCount();
        } catch (Exception $e) {
            throw new Exception('Error deleting participation: ' . $e->getMessage());
        }
    }
    */
}
?>