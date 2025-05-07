<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';

if (empty($_SESSION['allow_reset']) || empty($_SESSION['email_reset'])) {
    $_SESSION['message'] = "Session expirée. Veuillez refaire la demande de réinitialisation.";
    header('Location: login.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass1 = trim($_POST['password']);
    $pass2 = trim($_POST['confirm']);

    if (strlen($pass1) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($pass1 !== $pass2) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $hashed = password_hash($pass1, PASSWORD_BCRYPT);
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("UPDATE utilisateur SET password = ? WHERE email = ?");
        $stmt->execute([$hashed, $_SESSION['email_reset']]);

        // Nettoyage sécurisé
        unset($_SESSION['allow_reset']);
        unset($_SESSION['email_reset']);
        $_SESSION['message'] = "Mot de passe réinitialisé avec succès. Veuillez vous connecter.";
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f9;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .reset-container {
        background-color: #fff;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
    }

    h3 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }

    form input[type="password"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }

    form button {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        border: none;
        color: white;
        font-size: 16px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #0056b3;
    }

    p {
        text-align: center;
        margin-bottom: 10px;
    }

    p[style="color:red"] {
        color: #e74c3c !important;
        font-weight: bold;
    }
</style>

</head>
<body>
    <div class="reset-container">
        <h3>Réinitialisez votre mot de passe</h3>
        <?php if ($error): ?>
            <p style="color:red"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="password" name="password" placeholder="Nouveau mot de passe" required><br>
            <input type="password" name="confirm" placeholder="Confirmez le mot de passe" required><br>
            <button type="submit">Réinitialiser</button>
        </form>
    </div>
</body>
</html>
