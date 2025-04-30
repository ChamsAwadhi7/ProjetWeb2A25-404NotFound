<?php
require_once '../config.php';

// Sécurité : vérifier si les données sont envoyées
$id_user = $_POST['id_user'] ?? null;
$coursId = $_POST['coursId'] ?? null;
$prixCours = $_POST['prixCours'] ?? null;

if (!$id_user || !$coursId || !$prixCours) {
    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants.']);
    exit;
}

// Requête pour vérifier l'utilisateur dans la table finance
$stmt = $pdo->prepare("SELECT balance FROM finance WHERE id_user = :id_user");
$stmt->execute(['id_user' => $id_user]);
$userFinance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userFinance) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non trouvé dans finance.']);
    exit;
}

// Solde actuel
$soldeActuel = (float) $userFinance['balance'];  // Assurez-vous que c'est un nombre à virgule flottante
$prixCours = (float) $prixCours;  // Assurez-vous que c'est un nombre à virgule flottante

// Vérification du solde par rapport au prix du cours
if ($soldeActuel >= $prixCours) {
    // Déduire le prix du cours du solde
    $nouveauSolde = $soldeActuel - $prixCours;

    // Mettre à jour la table finance avec le nouveau solde
    $updateStmt = $pdo->prepare("UPDATE finance SET balance = :nouveauSolde WHERE id_user = :id_user");

    // Ajout de gestion des erreurs lors de l'exécution de la requête
    if ($updateStmt->execute(['nouveauSolde' => $nouveauSolde, 'id_user' => $id_user])) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Achat effectué avec succès.',
            'soldeActuel' => $soldeActuel,
            'prixCours' => $prixCours,
            'nouveauSolde' => $nouveauSolde
        ]);
    } else {
        // Affichage d'une erreur en cas d'échec de la mise à jour
        $errorInfo = $updateStmt->errorInfo();
        echo json_encode([
            'status' => 'error',
            'message' => 'Erreur lors de la mise à jour du solde.',
            'error' => $errorInfo  // Afficher l'erreur SQL
        ]);
    }
} else {
    // Solde insuffisant
    echo json_encode([
        'status' => 'error',
        'message' => 'Solde insuffisant pour accéder au cours.',
        'soldeActuel' => $soldeActuel,  // Solde avant l'achat
        'prixCours' => $prixCours
    ]);
}

// Enregistrer l'achat
$stmt = $pdo->prepare("INSERT INTO achetercours (id_user, id_cours) VALUES (?, ?)");
$stmt->execute([$id_user, $coursId]);
// Incrémenter le compteur de vues dans la table cours
$updateViewStmt = $pdo->prepare("UPDATE cours SET NbrVu = NbrVu + 1 WHERE id = ?");
$updateViewStmt->execute([$coursId]);


?>

