const searchInput = document.querySelector('input[name="search"]');
  const tableBody = document.querySelector("tbody");

  // Fonction de filtrage des lignes
  const filterRows = () => {
    const query = searchInput.value.toLowerCase();
    const rows = Array.from(tableBody.querySelectorAll("tr"));
    
    // Tri des lignes en 2 groupes: celles qui commencent par la recherche et celles qui contiennent
    const startsWith = [];
    const contains = [];

    rows.forEach(row => {
      const titleCell = row.querySelectorAll("td")[2]; // Titre
      const title = titleCell.textContent.toLowerCase();

      row.style.transition = "all 0.3s ease"; // Ajout d'une transition pour une animation douce

      if (title.startsWith(query)) {
        startsWith.push(row);
      } else if (title.includes(query)) {
        contains.push(row);
      } else {
        row.style.display = "none"; // Masquer les lignes qui ne correspondent pas
      }
    });

    // Vider le tableau avant d'ajouter les nouvelles lignes triées
    tableBody.innerHTML = "";

    // Réinsérer les lignes triées (commençant par la recherche en premier)
    startsWith.concat(contains).forEach(row => {
      tableBody.appendChild(row);
      row.style.display = "table-row"; // Afficher la ligne
    });
  };

  // Écouteur d'événements sur la barre de recherche
  searchInput.addEventListener("input", debounce(filterRows, 300));

  // Débouncer l'événement pour éviter un filtrage trop rapide
  function debounce(func, delay) {
    let timer;
    return function (...args) {
      clearTimeout(timer);
      timer = setTimeout(() => func.apply(this, args), delay);
    };
  }



  //Condition de saisir 
  document.getElementById('addCourseForm').addEventListener('submit', function(event) {
    // Empêcher l'envoi
    event.preventDefault();

    // Récupération des champs
    const titre = document.getElementById('courseName').value.trim();
    const description = document.getElementById('courseDescription').value.trim();
    const prix = parseFloat(document.getElementById('coursePrix').value);
    const imgCover = document.getElementById('imgCover').files[0];
    const courseExport = document.getElementById('courseExport').files[0];

    // Vérifications
    if (titre.length === 0) {
        alert('Le titre est obligatoire.');
        return;
    }
    if (titre.charAt(0) !== titre.charAt(0).toUpperCase()) {
        alert('Le titre doit commencer par une majuscule.');
        return;
    }

    if (description.length === 0) {
        alert('La description est obligatoire.');
        return;
    }
    if (description.charAt(0) !== description.charAt(0).toUpperCase()) {
        alert('La description doit commencer par une majuscule.');
        return;
    }

    if (isNaN(prix) || prix < 0) {
        alert('Le prix doit être un nombre positif ou nul.');
        return;
    }

    if (!imgCover) {
        alert('Veuillez choisir une image de couverture.');
        return;
    }

    if (!courseExport) {
        alert('Veuillez choisir un fichier exporté.');
        return;
    }

    // Si tout est valide, on envoie le formulaire
    this.submit();
});