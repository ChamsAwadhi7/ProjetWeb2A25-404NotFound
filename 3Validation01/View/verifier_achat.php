<?php
require_once '../config.php';

$id_user = $_GET['id_user'] ?? null;
$id_cours = $_GET['id_cours'] ?? null;

if ($id_user && $id_cours) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM achetercours WHERE id_user = ? AND id_cours = ?");
    $stmt->execute([$id_user, $id_cours]);
    $dejaAchete = $stmt->fetchColumn() > 0;

    echo json_encode(['dejaAchete' => $dejaAchete]);
} else {
    echo json_encode(['error' => 'Paramètres manquants']);
}
?>