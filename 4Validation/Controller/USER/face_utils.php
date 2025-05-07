<?php
// Chemin vers le script Python de reconnaissance faciale
define('PYTHON_SCRIPT', __DIR__.'/face_recognition.py');

function extractFaceDescriptor($imageData) {
    // Sauvegarder l'image temporairement
    $tempFile = tempnam(sys_get_temp_dir(), 'face');
    file_put_contents($tempFile, base64_decode(explode(',', $imageData)[1]));
    
    // Exécuter le script Python
    $command = "python3 " . PYTHON_SCRIPT . " extract " . escapeshellarg($tempFile);
    $output = shell_exec($command);
    unlink($tempFile);
    
    return json_decode($output, true);
}

function findUserByFaceDescriptor($descriptor) {
    $users = User::getAllWithFaceDescriptors();
    
    foreach ($users as $user) {
        if (!$user['face_descriptor']) continue;
        
        $savedDescriptor = json_decode($user['face_descriptor'], true);
        $distance = computeFaceDistance($savedDescriptor, $descriptor);
        
        if ($distance < 0.6) { // Seuil de similarité
            return new User($user);
        }
    }
    
    return null;
}

function computeFaceDistance($descriptor1, $descriptor2) {
    // Implémentation simple de distance euclidienne
    $sum = 0;
    for ($i = 0; $i < count($descriptor1); $i++) {
        $diff = $descriptor1[$i] - $descriptor2[$i];
        $sum += $diff * $diff;
    }
    return sqrt($sum);
}