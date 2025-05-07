<?php
session_start();


require_once '../config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Requête sécurisée avec préparation
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && $utilisateur['password'] === $password) {
        $_SESSION['utilisateur'] = [
            'id' => $utilisateur['id'],
            'nom' => $utilisateur['nom'],
            'prenom' => $utilisateur['prenom'],
            'email' => $utilisateur['email'],
            'role' => $utilisateur['role']
        ];
        // Redirection selon le rôle
    if ($utilisateur['role'] === 'admin') {
        header('Location: Back.php');
    } else {
        header('Location: index.php');
    }
    exit;
}
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <?php if ($erreur): ?>
        <p style="color:red"><?= $erreur ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Email :</label>
        <input type="email" name="email" required><br><br>
        <label>Mot de passe :</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>
