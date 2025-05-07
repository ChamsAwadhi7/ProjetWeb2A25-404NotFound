document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById('adminForm');

  form.addEventListener('submit', function (e) {
    let hasError = false;

    // Vider les messages d'erreur
    document.querySelectorAll('.error-message').forEach(span => span.textContent = '');

    const lastname = document.getElementById('lastname').value.trim();
    const firstname = document.getElementById('firstname').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const password = document.getElementById('password').value.trim();

    // Nom : pas vide, pas de chiffres
    if (lastname === '' || /\d/.test(lastname)) {
      document.getElementById('error-lastname').textContent = "Le nom est requis et ne doit pas contenir de chiffres.";
      hasError = true;
    }

    // Prénom : pas vide, pas de chiffres
    if (firstname === '' || /\d/.test(firstname)) {
      document.getElementById('error-firstname').textContent = "Le prénom est requis et ne doit pas contenir de chiffres.";
      hasError = true;
    }

    // Email : format valide
    if (!/^[\w.-]+@[\w.-]+\.[a-z]{2,}$/.test(email)) {
      document.getElementById('error-email').textContent = "Adresse email invalide.";
      hasError = true;
    }

    // Téléphone : 8 chiffres
    if (!/^\d{8}$/.test(phone)) {
      document.getElementById('error-phone').textContent = "Le numéro doit contenir exactement 8 chiffres.";
      hasError = true;
    }

    // Mot de passe : au moins 6 caractères
    if (password.length < 6) {
      document.getElementById('error-password').textContent = "Le mot de passe doit contenir au moins 6 caractères.";
      hasError = true;
    }

    if (hasError) {
      e.preventDefault(); // Empêche l'envoi du formulaire
    }
  });
});


  // Mise à jour des statistiques
  function updateStats() {
    fetch('../../Controllers/stats.php')
      .then(response => response.json())
      .then(data => {
        document.querySelector('.users p').textContent = `${data.utilisateurs} utilisateurs actifs`;
        document.querySelector('.events p').textContent = `${data.evenements} événements à venir`;
        document.querySelector('.startups p').textContent = `${data.startups} startups incubées`;
      });
  }
  setInterval(updateStats, 300000); // Actualisation toutes les 5 minutes

  // Recherche en temps réel des utilisateurs
  document.getElementById("userSearch").addEventListener("input", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#usersTable tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});

  // Chargement de la liste des utilisateurs
  fetch('../../Controllers/liste_utilisateurs.php')
    .then(res => res.json())
    .then(users => {
      const tbody = document.querySelector("table tbody");
      tbody.innerHTML = "";
      users.forEach(user => {
        tbody.innerHTML += `
          <tr>
            <td>${user.id}</td>
            <td>${user.nom}</td>
            <td>${user.email}</td>
            <td>
              <select class="role-select" data-user-id="${user.id}">
                <option value="user"${user.role === 'user' ? ' selected' : ''}>Utilisateur</option>
                <option value="admin"${user.role === 'admin' ? ' selected' : ''}>Admin</option>
                <option value="moderator"${user.role === 'moderator' ? ' selected' : ''}>Modérateur</option>
              </select>
            </td>
            <td><button class="update-btn" data-user-id="${user.id}">Mettre à jour</button></td>
          </tr>
        `;
      });
    });

  // Initialiser DataTable
  $(document).ready(function () {
    $('table').DataTable({
      language: {
        search: "Rechercher:",
        lengthMenu: "Afficher _MENU_ utilisateurs par page",
        info: "Affichage de _START_ à _END_ sur _TOTAL_ utilisateurs",
        paginate: {
          first: "Premier",
          last: "Dernier",
          next: "Suivant",
          previous: "Précédent"
        },
        zeroRecords: "Aucun utilisateur trouvé",
        infoEmpty: "Aucun utilisateur disponible",
        infoFiltered: "(filtré à partir de _MAX_ utilisateurs au total)"
      }
    });
  });

