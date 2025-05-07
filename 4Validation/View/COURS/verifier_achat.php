<?php
session_start();

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

require_once '../../config.php';
header('Content-Type: application/json');

// Vérifie que l'ID du cours est présent
if (!isset($_GET['id_cours'])) {
    echo json_encode(['error' => 'Paramètre id_cours manquant']);
    exit;
}

$id_user = $_SESSION['utilisateur']['id'];
$id_cours = filter_var($_GET['id_cours'], FILTER_VALIDATE_INT);

if ($id_cours === false) {
    echo json_encode(['error' => 'ID du cours invalide']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM achetercours WHERE id_user = ? AND id_cours = ?");
    $stmt->execute([$id_user, $id_cours]);
    $dejaAchete = $stmt->fetchColumn() > 0;

    echo json_encode(['dejaAchete' => $dejaAchete]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Erreur lors de la vérification', 'details' => $e->getMessage()]);
}
?>
