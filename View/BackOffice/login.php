<?php
session_start();
$errorMessage = '';
if (isset($_SESSION['login_error'])) {
    $errorMessage = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion Admin</title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <style>
    /* Ton CSS existant… */
    body { margin:0; padding:0; font-family:"Segoe UI"; background:url('background.jpg') center/cover fixed; display:flex; align-items:center; justify-content:center; height:100vh; }
    .login-container { background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); padding:40px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.3); max-width:400px; width:100%; color:#fff; text-align:center; }
    .login-container input { width:100%; padding:12px; margin-bottom:20px; border:1px solid #ffffff70; border-radius:8px; background:transparent; color:#fff; font-size:16px; }
    .login-container input[type="submit"] { background:#4e54c8; cursor:pointer; transition:.3s; }
    .login-container input[type="submit"]:hover { background:#3b41b5; }
    .error-message { background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px; }
    /* Modal styles… */
    .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:999; }
    .modal-content { background:#fff; margin:10% auto; padding:30px; border-radius:12px; max-width:400px; animation:slideDown .3s ease; }
    .close { float:right; font-size:24px; cursor:pointer; color:#aaa; }
    .close:hover { color:#000; }
    @keyframes slideDown { from { transform:translateY(-50px); opacity:0; } to { transform:translateY(0); opacity:1; } }
  </style>
</head>
<body>

  <form class="login-container" action="login_admin.php" method="post">
    <h2>Connexion Administrateur</h2>

    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>

    <?php if ($errorMessage): ?>
      <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <input type="submit" value="Se connecter">
    <p><a href="#" onclick="$('#resetModal').fadeIn()">Mot de passe oublié ?</a></p>
  </form>

  <!-- Modal de réinitialisation -->
  <div id="resetModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="$('#resetModal').fadeOut()">&times;</span>
      <h3>Réinitialisation du mot de passe</h3>
      <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
      <?php endif; ?>
      <form action="reset_password_request.php" method="post">
        <label for="reset-email">Entrez votre email :</label>
        <input type="email" id="reset-email" name="email" placeholder="exemple@domaine.com" required>
        <input type="submit" value="Envoyer le code">
      </form>
    </div>
</div>

  <script>
    // Fermer le modal en cliquant en dehors
    $(window).on('click', function(e) {
      if ($(e.target).is('#resetModal')) {
        $('#resetModal').fadeOut();
      }
    });
  </script>

</body>
</html>
