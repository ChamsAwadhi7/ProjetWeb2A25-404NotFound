<?php
session_start();

// Simuler un utilisateur connecté avec ID fixe (ex: 1)
$_SESSION['user_id'] = 2;

require_once __DIR__ . '/../../../Controller/eventController.php';
require_once __DIR__ . '/../../../Controller/rejoindreController.php';

$authPassword = 'monMotDePasseUltraSecret';

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    if (!isset($_POST['password']) || $_POST['password'] !== $authPassword) {
        echo '<form method="POST">
                <label>Mot de passe :</label>
                <input type="password" name="password">
                <input type="submit" value="Accéder">
              </form>';
        exit;
    } else {
        $_SESSION['auth'] = true;
    }
}

$id_event = filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);
$id_user = $_SESSION['user_id'] ?? null; // Assuming user is logged in

if (!$id_event || !$id_user) {
    die("Invalid parameters or missing form data");
}

$controller = new RejoindreController();

// Appel de la méthode addParticipation avec les 2 paramètres nécessaires
$result = $controller->addParticipation($id_event, $id_user);

// Vérifier que la participation a bien été ajoutée
if ($result['success']) {
    $id_participation = $result['id_participation'];
    $controller->sendConfirmationEmailForConfirmedParticipation($id_participation);

    header("Location: eventsF.php?id=$id_event&success=1");
} else {
    // Redirection en cas d'erreur
    header("Location: eventsF.php?id=$id_event&error=" . urlencode($result['message']));
}
exit();
?>
