<?php
ini_set('display_errors', 1); // 👈 debugging only
error_reporting(E_ALL);       // 👈 debugging only
header('Content-Type: application/json');
require_once __DIR__ . '/../../../config.php';


try {
    $pdo = config::getConnexion();
    $query = "SELECT nom_event AS title, lieu AS location, date_event AS date 
              FROM events 
              WHERE DATE(date_event) = CURDATE() + INTERVAL 3 DAY";
    $stmt = $pdo->query($query);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode(["error" => "Query failed: " . $e->getMessage()]);
}
?>
