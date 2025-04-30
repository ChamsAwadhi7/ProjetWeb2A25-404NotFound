<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Ressources - Cours</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 0;
    }

    .container {
      margin: 20px;
    }

    h1 {
      text-align: center;
      color: #333;
    }

    form {
      margin-bottom: 20px;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    form label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
    }

    form input, form textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    form button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      margin-top: 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    form button:hover {
      background-color: #45a049;
    }

    .resource-item {
      margin-bottom: 10px;
      padding: 10px;
      background-color: #fff;
      border-radius: 4px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .resource-item span {
      font-weight: bold;
    }

    .resource-item a {
      color: #4CAF50;
    }

    .resource-item a:hover {
      text-decoration: underline;
    }

    .back-button {
      margin-top: 20px;
      display: block;
      text-align: center;
    }

    .back-button a {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border-radius: 4px;
      text-decoration: none;
    }

    .back-button a:hover {
      background-color: #0056b3;
    }

  </style>
</head>
<body>
  <div class="container">
    <h1>📚 Gestion des Ressources - Cours</h1>

    <h2>Ajouter une Ressource</h2>
    <form id="addResourceForm">
      <label for="resourceName">Nom de la ressource :</label>
      <input type="text" id="resourceName" required>

      <label for="resourceLink">Lien de la ressource :</label>
      <input type="url" id="resourceLink" required>

      <button type="submit">Ajouter la Ressource</button>
    </form>

    <h3>Liste des Ressources</h3>
    <div id="resourceList"></div>

    <div class="back-button">
      <a href="course.html">Retour à la gestion des Cours</a>
    </div>
  </div>

  <script>
    // Récupérer l'ID du cours depuis l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const courseId = parseInt(urlParams.get("id"), 10);

    if (!courseId) {
      alert("ID du cours invalide !");
      window.location.href = 'course.html';
    }

    let courses = JSON.parse(localStorage.getItem("courses")) || [];
    let course = courses.find(c => c.id === courseId);

    if (!course) {
      alert("Cours non trouvé !");
      window.location.href = 'course.html';
    }

    // Assurez-vous que chaque cours ait une propriété 'resources' vide si elle n'existe pas déjà
    if (!course.resources) {
      course.resources = [];
    }

    function displayResources() {
      const resourceList = document.getElementById("resourceList");
      resourceList.innerHTML = ""; // Réinitialiser la liste
      course.resources.forEach(resource => {
        const resourceItem = document.createElement("div");
        resourceItem.classList.add("resource-item");
        resourceItem.innerHTML = `
          <span>${resource.name}</span> - 
          <a href="${resource.link}" target="_blank">Télécharger</a>
        `;
        resourceList.appendChild(resourceItem);
      });
    }

    document.getElementById("addResourceForm").addEventListener("submit", function(e) {
      e.preventDefault();

      const resourceName = document.getElementById("resourceName").value;
      const resourceLink = document.getElementById("resourceLink").value;

      const newResource = {
        name: resourceName,
        link: resourceLink
      };

      // Ajouter la nouvelle ressource
      course.resources.push(newResource);

      // Sauvegarder les changements dans localStorage
      localStorage.setItem("courses", JSON.stringify(courses));

      // Afficher les ressources à jour
      displayResources();

      // Réinitialiser le formulaire
      this.reset();
    });

    // Afficher les ressources existantes au chargement de la page
    displayResources();
  </script>
</body>
</html>
