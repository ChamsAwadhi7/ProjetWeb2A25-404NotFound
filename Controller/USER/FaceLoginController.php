<?php
// Affiche les erreurs en développement
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/FaceRecognitionService.php';
require_once __DIR__ . '/../Models/User.php';

session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    // Lecture de la requête JSON brute
    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true);

    if (!isset($input['image']) || empty($input['image'])) {
        throw new Exception('Aucune image fournie');
    }

    // Service de reconnaissance faciale
    $faceService = new FaceRecognitionService();

    // Recherche de l'utilisateur par son visage
    $match = $faceService->findUserByFace($input['image']);
    if (!$match) {
        // retourne un JSON 400 et message d'erreur
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error'   => 'Visage non reconnu'
        ]);
        exit;
    }

    // Connexion à la base
    $pdo = Database::getInstance()->getConnection();

    // Récupération des infos complètes de l'utilisateur
    $stmt = $pdo->prepare('SELECT * FROM utilisateur WHERE id = ? LIMIT 1');
    $stmt->execute([ $match['id'] ]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error'   => 'Utilisateur introuvable'
        ]);
        exit;
    }

    // Sauvegarde de l'utilisateur en session
    $_SESSION['user'] = serialize(new User($userData));
    $_SESSION['face_auth'] = true;

    // Réponse JSON de succès
    echo json_encode([
        'success'  => true,
        'user'     => [
            'id'         => $match['id'],
            'email'      => $match['email'],
            'confidence' => $match['confidence']
        ],
        // frontend redirigera vers ce chemin
        'redirect' => '../../FrontOffice/dashboard.php'
    ]);
    exit;

} catch (Exception $e) {
    // Erreur inattendue côté serveur
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Erreur interne : ' . $e->getMessage()
    ]);
    exit;
}
