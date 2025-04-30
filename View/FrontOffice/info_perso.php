<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier mon profil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .error-message {
      color: red;
      font-size: 0.85em;
    }
  </style>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
  <strong>Succès !</strong> Votre profil a été mis à jour avec succès.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

  <div class="card shadow p-4 rounded-3" style="width: 24rem;">
    <h4 class="card-title text-center mb-4">Modifier mon profil</h4>

    <form method="POST" action="../../Controllers/update_profile.php" onsubmit="return validateForm();">
      
      <!-- ID utilisateur -->
      <div class="mb-3">
        <label for="user-id" class="form-label">ID Utilisateur</label>
        <input type="text" class="form-control" id="user-id" name="id" required>
        <span class="error-message" id="error-id"></span>
      </div>

      <!-- Prénom -->
      <div class="mb-3">
        <label for="prenom" class="form-label">Prénom</label>
        <input type="text" class="form-control" id="prenom" name="firstname" >
        <span class="error-message" id="error-prenom"></span>
      </div>

      <!-- Nom -->
      <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="lastname" >
        <span class="error-message" id="error-nom"></span>
      </div>

      <!-- E-mail -->
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="text" class="form-control" id="email" name="email" >
        <span class="error-message" id="error-email"></span>
      </div>

        
      <!-- Mot de passe -->
      <div class="mb-4">
        <label for="motdepasse" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="motdepasse" name="password" >
        <span class="error-message" id="error-password"></span>
      </div>

      <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
    </form>
  </div>

  <script>
    window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
      // Afficher l'alerte de succès
      document.getElementById('success-alert').style.display = 'block';
    }
  };    
    function validateForm() {
      let valid = true;

      // ID : chiffres uniquement
      const id = document.getElementById('user-id').value.trim();
      if (!/^[0-9]+$/.test(id)) {
        document.getElementById('error-id').textContent = "ID invalide (chiffres uniquement).";
        valid = false;
      } else {
        document.getElementById('error-id').textContent = "";
      }

      // Prénom : lettres uniquement
      const prenom = document.getElementById('prenom').value.trim();
      if (!/^[A-Za-zÀ-ÿ\s\-]+$/.test(prenom)) {
        document.getElementById('error-prenom').textContent = "Prénom invalide (lettres uniquement).";
        valid = false;
      } else {
        document.getElementById('error-prenom').textContent = "";
      }

      // Nom : lettres uniquement
      const nom = document.getElementById('nom').value.trim();
      if (!/^[A-Za-zÀ-ÿ\s\-]+$/.test(nom)) {
        document.getElementById('error-nom').textContent = "Nom invalide (lettres uniquement).";
        valid = false;
      } else {
        document.getElementById('error-nom').textContent = "";
      }

      // Email : format strict
      const email = document.getElementById('email').value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        document.getElementById('error-email').textContent = "Format d'email invalide.";
        valid = false;
      } else {
        document.getElementById('error-email').textContent = "";
      }

      // Mot de passe : minimum 8 caractères (si rempli)
      const password = document.getElementById('motdepasse').value.trim();
      if (password !== "" && password.length < 8) {
        document.getElementById('error-password').textContent = "Le mot de passe doit contenir au moins 8 caractères.";
        valid = false;
      } else {
        document.getElementById('error-password').textContent = "";
      }

      return valid;
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
