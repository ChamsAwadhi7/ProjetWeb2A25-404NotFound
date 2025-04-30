document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('.signup-form form');
  
    form.addEventListener("submit", function (e) {
      let hasError = false;
  
      // Récupération des champs
      const nom = document.getElementById("nom");
      const prenom = document.getElementById("prenom");
      const email = document.getElementById("email");
      const password = document.getElementById("password");
  
      // Nettoyage des anciens messages
      document.querySelectorAll(".error-message").forEach(el => el.textContent = "");
  
      // Nom
      if (!nom.value.trim() || /\d/.test(nom.value)) {
        document.getElementById("nom-error").textContent = "Le nom ne doit pas contenir de chiffres.";
        hasError = true;
      }
  
      // Prénom
      if (!prenom.value.trim() || /\d/.test(prenom.value)) {
        document.getElementById("prenom-error").textContent = "Le prénom ne doit pas contenir de chiffres.";
        hasError = true;
      }
  
      // Email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email.value)) {
        document.getElementById("email-error").textContent = "Email invalide.";
        hasError = true;
      }
  
      // Mot de passe
      if (password.value.length < 6) {
        document.getElementById("password-error").textContent = "Mot de passe ≥ 6 caractères.";
        hasError = true;
      }
  
      // Si erreur, empêcher la soumission
      if (hasError) {
        e.preventDefault();
      }
    });
  });
  