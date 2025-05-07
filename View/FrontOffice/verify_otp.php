<?php
session_start();

if (empty($_SESSION['otp_reset']) || empty($_SESSION['email_reset'])) {
    header('Location: login.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['otp_code']);

    if (time() > $_SESSION['otp_expire']) {
        $error = "Le code a expiré.";
    } elseif ($code !== $_SESSION['otp_reset']) {
        $error = "Code incorrect.";
    } else {
        // ✅ C'est ici que tu mets ce bloc
        unset($_SESSION['otp_reset'], $_SESSION['otp_expire']);
        $_SESSION['allow_reset'] = true;
        header('Location: reset_password.php');
        exit();
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Vérifier OTP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        h3 { text-align: center; }
        form { width: 300px; margin: 0 auto; text-align: center; }
        input { padding: 10px; width: 100%; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #45a049; }
        p.error { color: red; text-align: center; }
    </style>
</head>
<body>
    <h3>Entrez le code reçu</h3>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <input type="text" name="otp_code" maxlength="6" required placeholder="000000" autocomplete="off">
        <button type="submit">Vérifier</button>
    </form>
</body>
</html>
