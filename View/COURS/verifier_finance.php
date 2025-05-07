<?php
require_once '../../config.php';
session_start();

header('Content-Type: application/json');

// Vérification des données POST
if (!isset($_POST['id_user'], $_POST['id_cours'], $_POST['prix_cours'])) {
    echo json_encode(['status' => 'error', 'message' => 'Données manquantes']);
    exit;
}

try {
    $id_user = intval($_POST['id_user']);
    $id_cours = intval($_POST['id_cours']);
    $prix_cours = floatval($_POST['prix_cours']);

    // Vérifier solde
    $stmt = $pdo->prepare("SELECT balance FROM finance WHERE id_user = ?");
    $stmt->execute([$id_user]);
    $balance = $stmt->fetchColumn();

    if ($balance === false) {
        echo json_encode(['status' => 'error', 'message' => 'Utilisateur non trouvé']);
        exit;
    }

    if ($balance < $prix_cours) {
        echo json_encode(['status' => 'error', 'message' => 'Solde insuffisant']);
        exit;
    }

    // Enregistrement de l’achat
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO achetercours (id_user, id_cours, date_achat) VALUES (?, ?, NOW())");
    $stmt->execute([$id_user, $id_cours]);

    $stmt = $pdo->prepare("UPDATE finance SET balance = balance - ? WHERE id_user = ?");
    $stmt->execute([$prix_cours, $id_user]);

    $pdo->commit();

    echo json_encode(['status' => 'success', 'message' => 'Achat réussi', 'soldeActuel' => $solde - $prix_cours]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur serveur. Impossible de vérifier vos finances. Détail: ' . $e->getMessage()
    ]);
}

