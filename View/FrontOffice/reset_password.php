<?php
session_start();

// Vérification du lien
if (!isset($_GET['token']) || !isset($_SESSION['reset_token']) || !isset($_SESSION['reset_expire'])) {
    die("Lien invalide ou expiré.");
}

if ($_GET['token'] !== $_SESSION['reset_token'] || time() > $_SESSION['reset_expire']) {
    unset($_SESSION['reset_token'], $_SESSION['reset_email'], $_SESSION['reset_expire']);
    die("Ce lien est invalide ou a expiré.");
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../config/Database.php';
    $pdo = Database::getInstance()->getConnection();

    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($new_password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE utilisateur SET password = ? WHERE email = ?");
        $stmt->execute([$hashed, $_SESSION['reset_email']]);

        unset($_SESSION['reset_token'], $_SESSION['reset_email'], $_SESSION['reset_expire']);
        $_SESSION['message'] = "Mot de passe réinitialisé avec succès.";
        header('Location: login_register.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Réinitialiser le mot de passe</title>
  <style>
    body {
      background: #f5f6fa;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .reset-container {
      background-color: #fff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }
    .reset-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #2f3640;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #444;
      font-weight: 600;
    }
    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 16px;
    }
    .btn {
      background-color: #0984e3;
      color: #fff;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .btn:hover {
      background-color: #74b9ff;
    }
    .message {
      color: red;
      margin-bottom: 15px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="reset-container">
    <h2>Réinitialiser votre mot de passe</h2>
    <?php if (!empty($error)): ?>
      <div class="message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label for="password">Nouveau mot de passe</label>
        <input type="password" name="password" id="password" required minlength="6">
      </div>
      <div class="form-group">
        <label for="confirm_password">Confirmer le mot de passe</label>
        <input type="password" name="confirm_password" id="confirm_password" required minlength="6">
      </div>
      <button type="submit" class="btn">Réinitialiser</button>
    </form>
  </div>
</body>
</html>
