<?php
require_once '../config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // Récupérer les commentaires
    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo json_encode([]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM commentaires WHERE cours_id = ? ORDER BY date DESC");
    $stmt->execute([$id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($comments);

} elseif ($method == 'POST') {
    // Ajouter un commentaire
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['cours_id'], $data['idUser'], $data['commentaire'], $data['reaction'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Paramètres manquants']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO commentaires (cours_id, idUser, commentaire, reaction) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['cours_id'],
        htmlspecialchars($data['idUser']),
        htmlspecialchars($data['commentaire']),
        htmlspecialchars($data['reaction'])
    ]);

    echo json_encode(['success' => true]);
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Méthode non autorisée']);
}
