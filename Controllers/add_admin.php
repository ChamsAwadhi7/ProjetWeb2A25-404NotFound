<?php
require_once __DIR__ . '/../config/Database.php';

// Récupérer les données du formulaire
$lastname = $_POST['lastname'];
$firstname = $_POST['firstname'];
$email = $_POST['email'];
$password = $_POST['password'];  // Mot de passe sans hachage
$phone = $_POST['phone'];

// Connexion à la base de données
$pdo = Database::getInstance()->getConnection();

try {
    // Démarrer une transaction pour éviter les erreurs si l'une des étapes échoue
    $pdo->beginTransaction();

    // Insérer dans la table utilisateur
    $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prénom, email, password, role) 
        VALUES (?, ?, ?, ?, 'admin')");
    $stmt->execute([$lastname, $firstname, $email, $password]);

    // Récupérer l'id du nouvel utilisateur
    $userId = $pdo->lastInsertId();

    // Insérer dans la table admin (en associant l'utilisateur à l'admin)
    $stmtAdmin = $pdo->prepare("INSERT INTO admin (id, tel) VALUES (?, ?)");
    $stmtAdmin->execute([$userId, $phone]);

    // Commit de la transaction
    $pdo->commit();

    // Redirection vers la même page d'ajout
    header('Location: ' . $_SERVER['HTTP_REFERER']);  // Cela redirige vers la page précédente
    
    exit;

} catch (Exception $e) {
    // En cas d'erreur, on annule la transaction
    $pdo->rollBack();
    echo "Erreur : " . $e->getMessage();
}
?>
