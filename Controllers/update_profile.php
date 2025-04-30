<?php
require_once __DIR__ . '/../config/Database.php';

$pdo = Database::getInstance()->getConnection();

if (isset($_POST['id'], $_POST['lastname'], $_POST['firstname'], $_POST['email'])) {
    $id = $_POST['id'];
    $prenom = trim($_POST['firstname']);
    $nom = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $motdepasse = isset($_POST['password']) ? trim($_POST['password']) : '';

    try {
        if (!empty($motdepasse)) {
            // Mise à jour avec mot de passe
            $stmt = $pdo->prepare("UPDATE utilisateur 
                                   SET prénom = ?, nom = ?, email = ?, password = ? 
                                   WHERE id = ?");
            $stmt->execute([$prenom, $nom, $email, $motdepasse, $id]);
        } else {
            // Mise à jour sans changer le mot de passe
            $stmt = $pdo->prepare("UPDATE utilisateur 
                                   SET prénom = ?, nom = ?, email = ? 
                                   WHERE id = ?");
            $stmt->execute([$prenom, $nom, $email, $id]);
        }

        // Redirection vers la page avec succès
header('Location: ' . $_SERVER['HTTP_REFERER'] . '?success=1');
exit;

    } catch (Exception $e) {
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }

} else {
    echo "Formulaire incomplet.";
}
?>
