<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
$pdo = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mdp = trim($_POST['password']);

    // Recherche de l'utilisateur (peu importe le rôle)
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Vérifie le mot de passe
        if (password_verify($mdp, $user['password'])) {
            // Vérifie si c’est bien un admin
            if ($user['role'] === 'admin') {
                $_SESSION['admin'] = $user['id'];
                session_regenerate_id(true);
                header('Location: dashboard.php');
                exit();
            } else {
                $_SESSION['login_error'] = "Vous n'avez pas le droit d'accéder à cette page.";
                header('Location: login.php');
                exit();
            }
        } else {
            $_SESSION['login_error'] = "Email ou mot de passe incorrect.";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Email ou mot de passe incorrect.";
        header('Location: login.php');
        exit();
    }
}
?>
