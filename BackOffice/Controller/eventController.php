<?php
// controller/EventController.php
require_once __DIR__ . '/../model/EventModel.php';
require_once __DIR__ . '/../config.php';

$eventModel = new EventModel($pdo);

// Suppression d'un événement
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $eventId = $_GET['id'];
    if ($eventModel->deleteEvent($eventId)) {
        header("Location: Event.php"); // Redirection après suppression
        exit();
    } else {
        echo "<script>alert('Erreur lors de la suppression de l\'événement');</script>";
    }
}

// Vérifier si l'événement à modifier est passé via l'URL
$event = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $eventId = $_GET['id'];
    $event = $eventModel->getEventById($eventId);
    
    if (!$event) {
        echo "<script>alert('Événement non trouvé.');</script>";
        exit();
    }
}

// Traitement du formulaire (ajouter ou modifier)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $date = $_POST['date'] ?? '';
    $description = $_POST['description'] ?? '';
    $lieu = $_POST['lieu'] ?? '';
    $imageBlob = null;

    // Validation des champs
    if (empty($nom) || empty($date) || empty($description) || empty($lieu)) {
        echo "<script>alert('Tous les champs doivent être remplis.');</script>";
    } else {
        // Vérification et traitement de l'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $maxFileSize = 5 * 1024 * 1024;
            if ($_FILES['image']['size'] > $maxFileSize) {
                echo "<script>alert('Le fichier image est trop volumineux. La taille maximale autorisée est de 5 Mo.');</script>";
            } else {
                $imageBlob = file_get_contents($_FILES['image']['tmp_name']);
            }
        } else {
            // Si aucune image n'est téléchargée, utiliser l'image existante ou une image par défaut
            $imageBlob = $event ? $event['img_event'] : file_get_contents(__DIR__ . '/../assets/default.jpg');
        }

        // Ajouter ou modifier l'événement dans la base de données
        if (isset($_POST['event_id'])) {
            // Mise à jour de l'événement existant
            $eventId = $_POST['event_id'];
            if ($eventModel->updateEvent($eventId, $nom, $date, $description, $lieu, $imageBlob)) {
                header("Location: Event.php"); // Redirection après mise à jour
                exit();
            } else {
                echo "<script>alert('Erreur lors de la mise à jour de l\'événement');</script>";
            }
        } else {
            // Ajout d'un nouvel événement
            if ($eventModel->addEvent($nom, $date, $description, $lieu, $imageBlob)) {
                header("Location: Event.php"); // Redirection après ajout
                exit();
            } else {
                echo "<script>alert('Erreur lors de l\'ajout de l\'événement');</script>";
            }
        }
    }
}

// Récupérer tous les événements
$events = $eventModel->getAllEvents();
?>
