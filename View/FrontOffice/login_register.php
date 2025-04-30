<?php
              $config = require '../../config/Database.php';
              require '../../Models/users.php';
              require '../../Controllers/AuthController.php';
              $auth = new AuthController(Database::getInstance()->getConnection());
              $errors = [];
              if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
                  $errors = $auth->handleLogin() ?: [];
              }
            ?>
            
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../assets/css/style_Front.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
  </head>
  <body>
    <div class="container">
      <!-- Checkbox to toggle between login and register forms -->
      <input type="checkbox" id="flip">
      <div class="cover">
        <div class="front">
          <img src="../../assets/images/frontImg.jpg" alt="">
          <div class="text">
            <span class="text-1">Every new friend is a <br> new adventure</span>
            <span class="text-2">Let's get connected</span>
          </div>
        </div>
        <div class="back">
          <img class="backImg" src="../../assets/images/backImg.jpg" alt="">
          <div class="text">
            <span class="text-1">Complete miles of journey <br> with one step</span>
            <span class="text-2">Let's get started</span>
          </div>
        </div>
      </div>

      <!-- Forms -->
      <div class="forms">
        <div class="form-content">
          <!-- Login Form -->
          <div class="login-form">
            <div class="title">Login</div>


            
            
            <form method="post">
              <div class="input-boxes">
                <div class="input-box">
                  <i class="fas fa-envelope"></i>
                  <input name="email" type="email" placeholder="Enter your email" required>
                </div>
                <?php if($errors):?>
              <ul>
                <?php foreach($errors as $e) echo "<li>$e</li>";?>
              </ul>
            <?php endif; ?>
                <div class="input-box">
                  <i class="fas fa-lock"></i>
                  <input name="password" type="password" placeholder="Enter your password" required>
                </div>
                <div class="text"><a href="#">Forgot password?</a></div>
                <div class="button input-box">
                  <input type="submit" name="login" value="Login">
                </div>
                <div class="text sign-up-text">Don't have an account? <label for="flip">Signup now</label></div>
              </div>
            </form>
          </div>

          <!-- Signup Form -->
          <div class="signup-form">
            <div class="title">Register</div>
            <?php
              $errors = [];
              if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
                  $errors = $auth->handleRegister() ?: [];
              }
            ?>
            <?php if($errors):?>
              <ul>
                <?php foreach($errors as $e)  ;?>
              </ul>
            <?php endif; ?>
            <form method="post">
              <div class="input-boxes">
                <!-- Nom -->
<div class="input-box">
  <i class="fas fa-user"></i>
  <input id="nom" name="nom" placeholder="Nom" required>
  <div class="error-message" id="nom-error"></div>
</div>

<!-- Prénom -->
<div class="input-box">
  <i class="fas fa-user"></i>
  <input id="prenom" name="prenom" placeholder="Prénom" required>
  <div class="error-message" id="prenom-error"></div>
</div>

<!-- Email -->
<div class="input-box">
  <i class="fas fa-envelope"></i>
  <input id="email" name="email" type="email" placeholder="Email" required>
  <div class="error-message" id="email-error"></div>
</div>

<!-- Mot de passe -->
<div class="input-box">
  <i class="fas fa-lock"></i>
  <input id="password" name="password" type="password" placeholder="Password" required>
  <div class="error-message" id="password-error"></div>
</div>

                <!-- Sélection du rôle -->
                <div class="input-box">
                  <i class="fas fa-users"></i>
                  <select name="role" required>
                    <option value="utilisateur">Utilisateur</option>
                    <option value="investisseur">Investisseur</option>
                    <option value="entrepeneur">Entrepreneur</option>
                  </select>
                </div>
                <!-- Bouton de soumission -->
                <div class="button input-box">
                  <input type="submit" name="register" value="Register">
                </div>
                <!-- Lien vers la page de login -->
                <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="../../assets/js/register-validation.js"></script>

  </body>
</html>
