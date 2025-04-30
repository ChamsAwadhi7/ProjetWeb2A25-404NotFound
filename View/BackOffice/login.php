<?php
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
$email = $_POST['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin - NextStep</title>
    <link rel="stylesheet" href="/NextStep/views/BackOffice/assets/css/auth.css">
</head>
<body>
    <div class="wrapper">
        <h2>Connexion Admin</h2>
        
        <?php if (!empty($_SESSION['login_error'])): ?>
<div class="alert alert-danger">
    <?= htmlspecialchars($_SESSION['login_error']) ?>
    <?php unset($_SESSION['login_error']); ?>
</div>
<?php endif; ?>

<form method="POST" action="/NextStep/index.php?action=login">
    <input type="email" name="email" required 
           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    <input type="password" name="password" required>
    <button type="submit">Se connecter</button>
</form>
    </div>

    <script src="/NextStep/views/BackOffice/assets/js/auth.js"></script>
</body>
</html>