<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ressources du Cours</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #eef2f5;
      padding: 40px;
    }
    .container {
      max-width: 600px;
      background: white;
      padding: 30px;
      border-radius: 12px;
      margin: auto;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    input, button {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      background-color: #28a745;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background-color: #218838;
    }
    ul {
      margin-top: 20px;
      list-style: none;
      padding-left: 0;
    }
    li {
      background: #f9f9f9;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 10px;
      border-left: 5px solid #28a745;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📎 Ressources du cours #<span id="courseIdDisplay"></span></h2>

    <form id="resourceForm">
      <input type="text" id="type" placeholder="Type de ressource (PDF, Lien...)" required>
      <input type="text" id="link" placeholder="Lien de la ressource" required>
      <button type="submit">Ajouter</button>
    </form>

    <ul id="resourceList"></ul>
  </div>

  <script>
    const ressources = JSON.parse(localStorage.getItem("ressources")) || [];
    const urlParams = new URLSearchParams(window.location.search);
    const courseId = urlParams.get("id_course");

    document.getElementById("courseIdDisplay").textContent = courseId;

    function updateLocalStorage() {
      localStorage.setItem("ressources", JSON.stringify(ressources));
    }

    function showResources() {
      const list = document.getElementById("resourceList");
      list.innerHTML = "";
      ressources.filter(r => r.id_course === courseId).forEach(r => {
        const li = document.createElement("li");
        li.textContent = `${r.type} : ${r.link}`;
        list.appendChild(li);
      });
    }

    document.getElementById("resourceForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const type = document.getElementById("type").value;
      const link = document.getElementById("link").value;

      ressources.push({ id_course: courseId, type, link });
      updateLocalStorage();
      this.reset();
      showResources();
    });

    showResources();
  </script>
</body>
</html>
