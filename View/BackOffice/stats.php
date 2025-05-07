<?php
require_once __DIR__ . '/../../config/Database.php';
header('Content-Type: application/json');

try {
    $pdo = Database::getInstance();
    
    echo json_encode([
        'users' => User::countActiveUsers($pdo),
        'events' => Evenement::countUpcomingEvents($pdo),
        'startups' => Projet::countIncubatedStartups($pdo)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de récupération des données']);
}
?>