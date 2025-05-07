<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/FaceRecognitionService.php';
require_once __DIR__ . '/../Models/Users.php';

session_start();
header('Content-Type: application/json');

try {
    // Lecture du corps de la requête JSON
    $inputRaw = file_get_contents('php://input');
    $input = json_decode($inputRaw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Format JSON invalide');
    }

    if (empty($input['image'])) {
        throw new Exception('Aucune image fournie');
    }

    // Service de reconnaissance faciale
    $faceService = new FaceRecognitionService();
    $match = $faceService->findUserByFace($input['image']);

    if (!$match) {
        throw new Exception('Visage non reconnu');
    }

    // Connexion à la base de données
    $pdo = Database::getInstance()->getConnection();

    // Requête pour récupérer l'utilisateur complet
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
    $stmt->execute([$match['id']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$userData) {
        throw new Exception('Utilisateur introuvable');
    }

    // Instanciation de l'objet User (POO)
    $user = new User($userData);
    $_SESSION['user'] = serialize($user);

    // Réponse JSON
    echo json_encode([
        'success' => true,
        'user'    => [
            'id'         => $user->getId(),
            'email'      => $user->getEmail(),
            'confidence' => $match['confidence']
        ],
        'redirect' => '../view/FrontOffice/dashboard.php'
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
