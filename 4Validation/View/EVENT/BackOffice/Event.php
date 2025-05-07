<?php
require_once __DIR__ . '/../../../Controller/eventController.php';
require_once __DIR__ . '/../../../Model/eventModel.php';
require_once __DIR__ . '/../../../config.php';

$eventC = new EventC();

// Initialisation des variables
$error = '';
$success = '';
$eventToEdit = null;
$events = [];

// Traitement des actions POST (add/update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom = trim($_POST['nom'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $lieu = trim($_POST['lieu'] ?? '');

        if (empty($nom) || empty($date) || empty($description) || empty($lieu)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $event = new Event();
        $event->setNomEvent($nom);
        $event->setDateEvent($date);
        $event->setDescEvent($description);
        $event->setLieuEvent($lieu);

        // Gestion de l'image
        if (!empty($_FILES['image']['name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $_FILES['image']['type'];

            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Seuls les fichiers JPEG, PNG et GIF sont autorisés.");
            }

            if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                throw new Exception("La taille de l'image ne doit pas dépasser 2MB.");
            }

            $uploadDir = 'uploads/events/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $uploadPath = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                throw new Exception("Erreur lors de l'enregistrement de l'image.");
            }

            $event->setImgEvent($uploadPath);
        } elseif (isset($_POST['id']) && empty($_FILES['image']['name'])) {
            $existingEvent = $eventC->getEventById($_POST['id']);
            $event->setImgEvent($existingEvent['img_event']);
        } else {
            $event->setImgEvent('assets/default-event.jpg');
        }

        if ($_POST['action'] === 'update') {
            $event->setIdEvent($_POST['id']);
            $rowsAffected = $eventC->updateEvent($event, $_POST['id']);
            if ($rowsAffected > 0) {
                header("Location: Event.php?success=update");
                exit();
            } else {
                throw new Exception("Aucune modification effectuée.");
            }
        } else {
            $event->setIdEvent(uniqid());
            $eventC->addEvent($event);
            header("Location: Event.php?success=add");
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Traitement des actions GET
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;
$searchTerm = $_GET['search'] ?? null;
$sortOrder = $_GET['sort'] ?? 'desc';

if ($action === 'delete' && $id) {
    try {
        $rowsAffected = $eventC->deleteEvent($id);
        if ($rowsAffected > 0) {
            header("Location: Event.php?success=delete");
            exit();
        } else {
            throw new Exception("Événement non trouvé ou déjà supprimé.");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

if ($action === 'edit' && $id) {
    $eventToEdit = $eventC->getEventById($id);
    if (!$eventToEdit) {
        $error = "Événement non trouvé.";
    }
}

try {
    if ($searchTerm) {
        $events = ($sortOrder === 'asc') ?
            $eventC->searchEventsAsc($searchTerm) :
            $eventC->searchEventsDesc($searchTerm);
    } else {
        $events = ($sortOrder === 'asc') ?
            $eventC->listEventsAsc() :
            $eventC->listEvents();
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'add':
            $success = "Événement ajouté avec succès.";
            break;
        case 'update':
            $success = "Événement mis à jour avec succès.";
            break;
        case 'delete':
            $success = "Événement supprimé avec succès.";
            break;
    }
}
?>
