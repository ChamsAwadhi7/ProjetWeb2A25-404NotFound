//delete user 
document.addEventListener('DOMContentLoaded', function () {
  const deleteButtons = document.querySelectorAll('.delete-btn');
  deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
      const userId = this.getAttribute('data-user-id');
      if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        window.location.href = `../../Controllers/delete_user.php?id=${userId}`;
      }
    });
  });
});


document.addEventListener('DOMContentLoaded', function () {
  var telInput = document.getElementById('telInput');
  var telError = document.getElementById('telError');
  var updateBtn = document.getElementById('updateBtn');
  var roleSelect = document.getElementById('roleSelect');

  updateBtn.addEventListener('click', function (event) {
    var role = roleSelect.value;

    // Vérification seulement si admin
    if (role === 'admin') {
      var value = telInput.value.replace(/\D/g, '');

      if (value.length !== 8) {
        // Afficher l'erreur et bloquer l'action
        telError.style.display = 'block';
        telError.textContent = "Le numéro de téléphone doit contenir exactement 8 chiffres.";
        event.preventDefault(); // Empêche la soumission ou l’appel AJAX
        return; // Bloque complètement le reste du code
      } else {
        telError.style.display = 'none';
      }
    } else {
      telError.style.display = 'none';
    }

    // Si ici = OK → tu peux lancer ton appel AJAX ou traitement
    // Exemple :
    // updateUser();
  });
});




document.addEventListener('DOMContentLoaded', function () {
  // Gestion de la sidebar mobile
  const closeBtn = document.getElementById('close_btn');
  if (closeBtn) {
    closeBtn.addEventListener('click', function () {
      document.querySelector('aside').style.display = 'none';
    });
  }

  // Mise à jour des rôles utilisateurs
  document.querySelectorAll('.update-btn').forEach(button => {
    button.addEventListener('click', function (event) {
      const userId = this.dataset.userId;
      const selectElement = document.querySelector(`select[data-user-id="${userId}"]`);
      const newRole = selectElement.value;
  
      const telInput = document.querySelector(`input[data-user-id="${userId}"]`);
      const newTel = telInput ? telInput.value.trim() : '';
      const telError = document.querySelector(`#telError-${userId}`);
  
      // Vérification du téléphone pour rôle admin
      if (newRole === 'admin') {
        const cleanedTel = newTel.replace(/\D/g, '');
        if (cleanedTel.length !== 8) {
          if (telError) {
            telError.textContent = 'Le numéro de téléphone doit contenir exactement 8 chiffres.';
            telError.style.display = 'block';
          }
          return; // Blocage de la mise à jour
        } else if (telError) {
          telError.style.display = 'none';
        }
      }
  
      // Envoi des données
      const params = new URLSearchParams({
        user_id: userId,
        new_role: newRole,
        tel: newTel
      });
  
      fetch('../../Controllers/updateRole.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
      })
      .then(r => r.text())
      .then(msg => {
        alert(msg);
        location.reload();
      })
      .catch(err => console.error('Erreur :', err));
    });
  });
  



  // Gestion du formulaire de recrutement d'un administrateur
  const recruitForm = document.getElementById('recruit-admin-form');
  recruitForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('../../Controllers/add_admin.php', {  // Assurez-vous du bon chemin
      method: 'POST',
      body: formData
    }).then(res => res.text()).then(msg => {
      alert(msg);
      this.reset(); // Réinitialiser le formulaire
    });
  });

  // Mise à jour des statistiques
  function updateStats() {
    fetch('../../Controllers/stats.php')  // Vérifiez le bon chemin
      .then(response => response.json())
      .then(data => {
        document.querySelector('.users p').textContent = `${data.utilisateurs} utilisateurs actifs`;
        document.querySelector('.events p').textContent = `${data.evenements} événements à venir`;
        document.querySelector('.startups p').textContent = `${data.startups} startups incubées`;
      });
  }

  // Actualiser les stats toutes les 5 minutes
  setInterval(updateStats, 300000);

  // Recherche en temps réel des utilisateurs
  document.getElementById('userSearch').addEventListener('input', function (e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(row => {
      const name = row.children[1].textContent.toLowerCase();
      const email = row.children[2].textContent.toLowerCase();
      row.style.display = (name.includes(searchTerm) || email.includes(searchTerm)) ? '' : 'none';
    });
  });

  

  // Charger la liste des utilisateurs et afficher dans le tableau
  fetch('../../Controllers/liste_utilisateurs.php')  // Assurez-vous du bon chemin
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
});
//rechreche un user
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('userSearch');
  const tableRows = document.querySelectorAll('.user-table-container tbody tr');

  searchInput.addEventListener('input', function () {
    const searchTerm = searchInput.value.toLowerCase();

    tableRows.forEach(row => {
      const nom = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
      const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

      if (nom.includes(searchTerm) || email.includes(searchTerm)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
});
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

