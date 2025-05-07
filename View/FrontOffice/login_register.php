<?php
session_start();
$error = '';
$email_value = '';
$registerErrors = [];

if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

if (isset($_SESSION['register_errors'])) {
    $registerErrors = $_SESSION['register_errors'];
    unset($_SESSION['register_errors']);
}

if (isset($_POST['email'])) {
    $email_value = htmlspecialchars($_POST['email']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Login & Register</title>
  <link rel="stylesheet" href="style_Front.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
      <div class="front">
        <img src="frontImg.jpg" alt="">
        <div class="text">
          <span class="text-1">Every new friend is a <br> new adventure</span>
          <span class="text-2">Let's get connected</span>
        </div>
      </div>
      <div class="back">
        <img class="backImg" src="backImg.jpg" alt="">
        <div class="text">
          <span class="text-1">Complete miles of journey <br> with one step</span>
          <span class="text-2">Let's get started</span>
        </div>
      </div>
    </div>

    <div class="forms">
      <div class="form-content">
        <!-- Login Form -->
        <div class="login-form">
          <div class="title">Login</div>
          <form id="loginForm" method="post" action="AuthUsers.php" onsubmit="return validateLogin()">
            <div class="input-boxes">
              <?php if (!empty($error)): ?>
              <div style="color:#b30000; background:#ffe6e6; padding:10px; border:1px solid #b30000; border-radius:5px; margin-bottom:15px;">
                <?= $error ?>
              </div>
              <?php endif; ?>
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input id="loginEmail" name="email" type="email" placeholder="Enter your email" value="<?= $email_value ?>" required>
              </div>
              <div class="input-box">
                <i class="fas fa-lock"></i>
                <input id="loginPassword" name="password" type="password" placeholder="Enter your password" required>
              </div>
              <div id="loginError" style="color:#b30000; margin-bottom:15px;"></div>
              <div class="text"><a href="#" onclick="toggleForms('forgot')">Forgot password?</a></div>
              <?php if (!empty($_SESSION['message'])): ?>
      <div style="color: red; text-align: center; margin-top: 10px;">
        <?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

              <div class="button input-box">
                <input type="submit" name="login" value="Login">
              </div>
              <div class="text sign-up-text">Don't have an account? <label for="flip">Signup now</label></div>

              <!-- Option de connexion faciale -->
              <div class="face-login-option" style="margin-top: 20px;">
                <div class="text" style="text-align: center; margin-bottom: 10px;">
                  <span style="color: #666;">Ou connectez-vous avec votre visage</span>
                </div>
                <div style="text-align: center;">
                  <button type="button" id="face-login-btn" style="background: #4285f4; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-user-circle"></i> Reconnaissance faciale
                  </button>
                </div>
                <div id="face-login-container" style="display: none; margin-top: 15px;">
                  <video id="video" width="100%" autoplay style="border-radius: 5px;"></video>
                  <div style="text-align: center; margin-top: 10px;">
                    <button  type="button" id="capture-face-login" style="background: #34a853; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
                      <i class="fas fa-camera"></i> Capturer
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </form>

          <div id="forgot_password_form" style="display: none;" class="wrapper">
  <h2 class="title">Réinitialiser le mot de passe</h2>
  <form action="reset_password_request.php" method="post">
    <div class="input-box">
      <input type="text" name="email" placeholder="Entrez votre email" >
    </div>
    <div class="input-box button">
      <input type="submit" value="Envoyer le lien">
    </div>
    
    <div class="text">
      <a href="#" onclick="toggleForms('login')">← Retour à la connexion</a>
    </div>
  </form>
</div>


        </div>

        <div class="signup-form">
          <div class="title">Register</div>
          <form id="registerForm" method="post" action="AuthUsers.php" onsubmit="return validateRegister()" enctype="multipart/form-data">
            <div class="input-boxes">
              <?php if (!empty($registerErrors)): ?>
                <div style="color:#b30000; background:#ffe6e6; padding:10px; border:1px solid #b30000; border-radius:5px; margin-bottom:15px;">
                  <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($registerErrors as $e): ?>
                      <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>

              <div class="input-box">
                <i class="fas fa-user"></i>
                <input id="nom" name="nom" type="text" placeholder="Nom" >
              </div>
              <div class="input-box">
                <i class="fas fa-user"></i>
                <input id="prenom" name="prenom" type="text" placeholder="Prénom" >
              </div>
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input id="email" name="email" type="text" placeholder="Email" >
              </div>
              <div class="input-box">
                <i class="fas fa-phone"></i>
                <input id="tel" name="tel" type="text" placeholder="Téléphone" >
              </div>
              <div class="input-box">
                <i class="fas fa-lock"></i>
                <input id="password" name="password" type="password" placeholder="Mot de passe" >
              </div>
              <div class="input-box">
                <i class="fas fa-user-tag"></i>
                <select id="role" name="role" required style="width: 100%; padding: 10px; border-radius: 5px;">
                  <option value="">-- Choisir un rôle --</option>
                  <option value="utilisateur">Utilisateur</option>
                  <option value="investisseur">Investisseur</option>
                  <option value="entrepreneur">Entrepreneur</option>
                </select>
              </div>

              <!-- Capture faciale pour inscription -->
              <div style="margin-top: 20px; text-align: center;">
                <span style="color: #666;">Capture faciale (optionnelle)</span><br>
                <button type="button" id="face-register-btn" style="margin-top: 10px; background: #4285f4; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
                  <i class="fas fa-camera"></i> Ouvrir la caméra
                </button>
              </div>
              <div id="face-register-container" style="display: none; margin-top: 15px;">
                <video id="videoRegister" width="100%" autoplay style="border-radius: 5px;"></video>
                <input type="hidden" name="photo_data" id="photo_data_register">
                <div style="text-align: center; margin-top: 10px;">
                  <button type="button" id="capture-face-register" style="background: #34a853; color: white; padding: 10px 20px; border: none; border-radius: 4px;">
                    <i class="fas fa-camera"></i> Capturer
                  </button>
                </div>
              </div>

              <div class="button input-box">
                <input type="submit" name="register" value="Register">
              </div>
              <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
            </div>
          </form>
          

        </div>

      </div>
    </div>
  </div>
  
  <script>

function toggleForms(target) {
    document.querySelector('.wrapper').style.display = (target === 'login') ? 'block' : 'none';
    document.getElementById('forgot_password_form').style.display = (target === 'forgot') ? 'block' : 'none';}


// Validation pour Login
function validateLogin() {
  var email = document.getElementById('loginEmail').value.trim();
  var pwd   = document.getElementById('loginPassword').value;
  var errorDiv = document.getElementById('loginError');
  errorDiv.innerText = '';

  // Email non vide et format basique
  if (email === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    errorDiv.innerText = 'Veuillez saisir une adresse email valide.';
    return false;
  }

  // Mot de passe au moins 7 caractères
  if (pwd.length <= 6) {
    errorDiv.innerText = 'Le mot de passe doit contenir au moins 7 caractères.';
    return false;
  }

  return true;
}

// Validation pour Register
function validateRegister() {
  var nom    = document.getElementById('nom').value.trim();
  var prenom = document.getElementById('prenom').value.trim();
  var email  = document.getElementById('email').value.trim();
  var tel    = document.getElementById('tel').value.trim();
  var pwd    = document.getElementById('password').value;
  var msg = [];

  // Nom et prénom non vides et sans chiffres
  if (nom === '' || /\d/.test(nom)) {
    msg.push('Le nom ne doit pas être vide et ne doit pas contenir de chiffres.');
  }
  if (prenom === '' || /\d/.test(prenom)) {
    msg.push('Le prénom ne doit pas être vide et ne doit pas contenir de chiffres.');
  }

  // Email non vide et format standard
  if (email === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    msg.push('Veuillez saisir une adresse email valide.');
  }

  // Téléphone exactement 8 chiffres
  if (!/^\d{8}$/.test(tel)) {
    msg.push('Le numéro de téléphone doit contenir exactement 8 chiffres.');
  }

  // Mot de passe au moins 7 caractères
  if (pwd.length <= 6) {
    msg.push('Le mot de passe doit contenir au moins 7 caractères.');
  }

  if (msg.length > 0) {
    alert(msg.join("\n"));
    return false;
  }
  return true;
}

// Scripts de capture faciale
document.addEventListener('DOMContentLoaded', function() {
  const registerBtn       = document.getElementById('face-register-btn');
  const captureRegister   = document.getElementById('capture-face-register');
  const videoRegister     = document.getElementById('videoRegister');
  const faceRegisterBlock = document.getElementById('face-register-container');
  const photoInput        = document.getElementById('photo_data_register');
  const canvasRegister    = document.createElement('canvas');
  let streamRegister = null;

  registerBtn.addEventListener('click', async () => {
    if (faceRegisterBlock.style.display === 'none') {
      try {
        streamRegister = await navigator.mediaDevices.getUserMedia({ video: true });
        videoRegister.srcObject = streamRegister;
        faceRegisterBlock.style.display = 'block';
        registerBtn.innerHTML = '<i class="fas fa-camera"></i> Masquer la caméra';
      } catch (err) {
        alert("Erreur caméra: " + err.message);
      }
    } else {
      stopStream(streamRegister);
      faceRegisterBlock.style.display = 'none';
      registerBtn.innerHTML = '<i class="fas fa-camera"></i> Ouvrir la caméra';
    }
  });

  captureRegister.addEventListener('click', function() {
    canvasRegister.width = videoRegister.videoWidth;
    canvasRegister.height = videoRegister.videoHeight;
    canvasRegister.getContext('2d').drawImage(videoRegister, 0, 0);
    var dataURL = canvasRegister.toDataURL('image/jpeg');
    photoInput.value = dataURL;
    alert("Photo capturée avec succès !");
  });

  function stopStream(stream) {
    if (stream) stream.getTracks().forEach(t => t.stop());
  }

  // Vous pouvez ajouter ici les scripts de face-login similaires...
});
</script>

</body>
</html>
