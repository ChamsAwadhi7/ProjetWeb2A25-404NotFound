<?php
// FaceRecognitionService.php
require_once __DIR__.'/../../config/Database.php';
require_once __DIR__.'/../../Models/Users.php';

class FaceRecognitionService {
    private $pdo;

    public function __construct() {
        $database = Database::getInstance();
        $this->pdo = $database->getConnection();
    }

    /**
     * Traite une image de visage et retourne un descripteur
     * @param string $imageData Données de l'image en base64
     * @return array Tableau contenant le descripteur et la confiance
     */
    public function processFace(string $imageData): array {
        // Implémentation simplifiée pour développement
        return [
            'descriptor' => md5($imageData), // Hash unique comme descripteur factice
            'confidence' => 0.95 // Valeur de confiance factice pour les tests
        ];
    }

    /**
     * Compare un descripteur facial avec une image
     * @param string $imageData Données de l'image à vérifier
     * @param string $storedDescriptor Descripteur stocké (JSON)
     * @return bool True si correspondance
     */
    public function verifyFace(string $imageData, string $storedDescriptor): bool {
        $current = $this->processFace($imageData);
        $stored = json_decode($storedDescriptor, true);
        
        return $current['descriptor'] === $stored['descriptor'];
    }
}