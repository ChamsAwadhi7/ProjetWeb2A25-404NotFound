<?php
require_once __DIR__ . '/../../config/Database.php';

session_start();
header('Content-Type: application/json');

// Vérification CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' || 
    $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['csrf_token']) {
  http_response_code(403);
  die(json_encode(['error' => 'Accès non autorisé']));
}

try {
  $pdo = Database::getInstance();
  $userId = $_GET['id'];
  
  $pdo->beginTransaction();
  
  // Supprimer de la table admin si nécessaire
  $stmt = $pdo->prepare("DELETE FROM admin WHERE id = ?");
  $stmt->execute([$userId]);
  
  // Supprimer de la table utilisateur
  $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = ?");
  $stmt->execute([$userId]);
  
  $pdo->commit();
  echo json_encode(['success' => true]);

} catch (Exception $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}