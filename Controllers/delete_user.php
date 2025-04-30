<?php
require_once __DIR__ . '/../config/Database.php';

$pdo = Database::getInstance()->getConnection();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = (int) $_GET['id'];

    // Supprimer l'utilisateur de la table 'utilisateur'
    $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = ?");
    $stmt->execute([$userId]);

    // Rediriger vers la page d'administration avec un message de succès
    header('Location: ../View/BackOffice/administration.php?message=Utilisateur supprimé avec succès');
    exit;
} else {
    // Rediriger avec un message d'erreur si l'ID est invalide
    header('Location: ../View/BackOffice/administration.php?error=ID utilisateur invalide');
    exit;
}
?>
