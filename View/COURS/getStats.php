<?php
require_once 'config.php'; // ou ta connexion PDO
header('Content-Type: application/json');

try {
    global $pdo;
    $stmt = $pdo->query("SELECT Titre, NbrVu, Notes FROM cours ORDER BY NbrVu DESC LIMIT 5");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur lors de la récupération des données']);
}
?>
