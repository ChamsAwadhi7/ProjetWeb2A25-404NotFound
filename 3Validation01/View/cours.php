<?php
// === Configuration et Contrôleur ===
require_once '../config.php';
require_once '../Controller/CoursC.php';
require_once '../Model/Cours.php';  

$coursController = new CoursController();
$commentaires = $coursController->getDerniersCommentaires();


// === Suppression d’un cours ===
if (isset($_GET['delete'])) {
    $coursController->supprimerCours(intval($_GET['delete']));
}

// === Ajout d’un cours ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['courseName'])) {
    $coursController->ajouterCours($_POST, $_FILES);
}

// === Mise à jour d’un cours ===
if (isset($_POST['update'])) {
  // Récupérer les données du formulaire
  $id = $_POST['id'] ?? null;
  $titre = $_POST['titre'] ?? '';
  $prix = $_POST['prix'] ?? 0;
  $description = $_POST['description'] ?? '';

  // Vérifier que l'ID du cours est valide
  if ($id !== null) {
      // Passer aussi les fichiers au contrôleur pour les traiter
      $coursController->updateCours($id, $titre, $prix, $description, $_FILES); // Passer $_FILES pour les fichiers
      header('Location: cours.php'); // Rediriger vers la liste des cours après mise à jour
      exit; // Terminer le script
  }
}


// === Tri et recherche ===
$tri = $_GET['tri'] ?? 'id';
$search = $_GET['search'] ?? '';
$courses = $coursController->chercherCours($tri, $search);
$message = $coursController->message;

// === Récupérer l'id du cours à mettre à jour ===
if (isset($_GET['update'])) {
  $id = $_GET['update'];
  // Récupérer le cours à partir de la base de données
  $course = $coursController->getCoursById($id);
  if ($course) {
      $titre = $course['Titre'];
      $prix = $course['Prix'];
      $description = $course['Description'];
  }
}

// === Statistiques ===
$statistiques = $coursController->getStatistiques();
$totalCours = $statistiques['totalCours'];
$prixMoyen = $statistiques['prixMoyen'];
$coursPopulaire = $coursController->getCoursLePlusPopulaire();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Cours</title>
  <link rel="stylesheet" href="cours.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link
   rel="stylesheet"
   href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />
  <link rel="stylesheet" href="BackOffice.css">
</head>

<body>
  <div class="container">
    <!-- === Sidebar === -->
    <aside>
      <div class="top">
        <div class="logo">
          <h2>C <span class="danger">NextStep</span></h2>
        </div>
        <div class="close" id="close_btn"><span class="material-symbols-sharp">close</span></div>
      </div>
      <div class="sidebar">
        <a href="index.html"><span class="material-symbols-sharp">grid_view</span><h3>Dashboard</h3></a>
        <a href="#"><span class="material-symbols-sharp">person_outline</span><h3>Clients</h3></a>
        <a href="Event.html"><span class="material-symbols-sharp">receipt_long</span><h3>Événements</h3></a>
        <a href="cours.html" class="active"><span class="material-symbols-sharp">receipt_long</span><h3>Cours</h3></a>
        <a href="startup.html"><span class="material-symbols-sharp">receipt_long</span><h3>Startup</h3></a>
        <a href="#" id="incubators-btn"><span class="material-symbols-sharp">business</span><h3>Incubateurs</h3></a>
        <a href="#"><span class="material-symbols-sharp">logout</span><h3>Déconnexion</h3></a>
        <a href="#"><span class="material-symbols-sharp">settings</span><h3>Paramètres</h3></a>
      </div>
    </aside>

    <!-- === Main Content === -->
    <main>
    <?php if (!empty($message)): ?>
       <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

      <h1>Gestion des Cours</h1>
        <!-- Affichage des statistiques -->
    
      <div id="form-container">


        <!-- Formulaire d'ajout -->
        <form id="addCourseForm" action="" method="POST" enctype="multipart/form-data">
          <h2>Ajouter un Cours</h2>
          <label for="courseName">Titre:</label>
          <input type="text" name="courseName" id="courseName" >

          <label for="courseDescription">Description :</label>
          <textarea name="courseDescription" id="courseDescription" ></textarea>

          <label for="coursePrix">Prix (dt) :</label>
          <input type="number" name="coursePrix" id="coursePrix" min="0" step="20">

          <label for="imgCover">Image de couverture :</label>
          <input type="file" name="imgCover" id="imgCover" accept="image/*">

          <label for="courseExport">Fichier exporté :</label>
          <input type="file" name="courseExport" id="courseExport" accept=".pdf,.jpg,.png,.mp4">

          <button type="submit">Ajouter</button>
        </form>


      <!-- Formulaire de mise à jour -->
<?php $id = $id ?? null; ?>
<?php if ($id): ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <h2>Mettre à jour un Cours</h2>

        <input type="hidden" name="id" value="<?= $id ?>">

        <label for="titre">Titre :</label>
        <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($titre) ?>">

        <label for="prix">Prix :</label>
        <input type="number" name="prix" id="prix" value="<?= htmlspecialchars($prix) ?>">

        <label for="description">Description :</label>
        <textarea name="description" id="description"><?= htmlspecialchars($description) ?></textarea>

        <label for="imgCover">Image de couverture (optionnel) :</label>
        <input type="file" name="imgCover" id="imgCover">

        <label for="courseExport">Fichier d'exportation (optionnel) :</label>
        <input type="file" name="courseExport" id="courseExport">

        <button type="submit" name="update">Mettre à jour</button>
    </form>
<?php endif; ?>

        <!-- Formulaire de tri/recherche -->
        <form method="GET" class="search-bar">
          <label for="tri">Trier par :</label>
          <select name="tri" id="tri" onchange="this.form.submit()">
            <option value="id" <?= ($tri === 'id') ? 'selected' : '' ?>>ID (récent)</option>
            <option value="date" <?= ($tri === 'date') ? 'selected' : '' ?>>Date</option>
            <option value="note" <?= ($tri === 'note') ? 'selected' : '' ?>>Note</option>
            <option value="vues" <?= ($tri === 'vues') ? 'selected' : '' ?>>Vues</option>
          </select>
          <input type="text" name="search" placeholder="🔍 Rechercher un titre..." value="<?= htmlspecialchars($search) ?>">
          <button type="submit">Rechercher</button><br><br>
        </form>
        
        

         
    </main>

    <!-- === Right Section  === -->
    <div class="right">
      <div class="top">
        <button id="menu_bar"><span class="material-symbols-sharp">menu</span></button>
        <div class="theme-toggler">
          <span class="material-symbols-sharp active">light_mode</span>
          <span class="material-symbols-sharp">dark_mode</span>
        </div>
        <div class="profile">
          <div class="info">
            <p><b>Maram Saidi</b></p>
            <p>Admin</p>
          </div>
          <div class="profile-photo"><img src="./images/me.jpg" alt=""/></div>
        </div>
      </div>

    <form method="GET" class="Derniers Commentaires">
    <div class="Derniers Commentaires">
    <div class="Derniers Commentaires">
        <h2>🗨️ Derniers Commentaires</h2>
        <br><br>
        <?php if (!empty($commentaires)): ?>
            <ul>
                <?php foreach ($commentaires as $commentaire): ?>
                    <li style="margin-bottom: 1rem; border-bottom: 1px solid #ccc; padding-bottom: 0.5rem;">
                        <p><strong>Cours ID :</strong> <?= htmlspecialchars($commentaire['cours_id']) ?> |
                        <strong>Utilisateur :</strong> <?= htmlspecialchars($commentaire['idUser']) ?></p>
                        <p><em><?= nl2br(htmlspecialchars($commentaire['commentaire'])) ?></em></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun commentaire pour le moment.</p>
        <?php endif; ?>
    </div>
  </div>
  </form>
      <br><br>


      <!-- Formulaire de statistiques et pdf -->
      
<form method="GET" class="statistiques">

        <h2>Statistiques</h2>
        <p><strong>Total des cours :</strong> <?php echo $totalCours; ?></p>
        <p><strong>Prix moyen des cours :</strong> <?php echo number_format($prixMoyen, 2); ?> €</p>
        <br>
    <button id="statButton" type="button" onclick="toggleStats()">Afficher les statistiques</button>
</form>
<?php if ($coursPopulaire): ?>
    <p><strong>Cours le plus populaire :</strong> 
        <?= htmlspecialchars($coursPopulaire['Titre']) ?> 
        (<?= intval($coursPopulaire['NbrVu']) ?> vues)
    </p>

<!-- Contenu des statistiques caché au départ -->
<div id="statistiquesContent" style="display: none;">
    <h2>Statistiques des Cours</h2>

    <!-- Ici, le canvas pour afficher le diagramme -->
    <canvas id="statChart" width="400" height="200"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
    // Fonction pour afficher ou cacher les statistiques
    function toggleStats() {
        var statsContent = document.getElementById('statistiquesContent');
        var statButton = document.getElementById('statButton');
        
        // Si le contenu est actuellement caché, on l'affiche
        if (statsContent.style.display === 'none') {
            statsContent.style.display = 'block';
            statButton.textContent = 'Cacher les statistiques'; // Changer le texte du bouton
        } else {
            statsContent.style.display = 'none';
            statButton.textContent = 'Afficher les statistiques'; // Revenir au texte initial
        }
        
        // Charger le graphique lorsque les statistiques sont affichées
        loadChart();
    }

    // Charger et afficher le graphique
    
<?php endif; ?>

function loadChart() {
    var ctx = document.getElementById('statChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Intelligence Artificielle', 'Lancer sa Startup de A à Z', 'Stratégies de marketing digital', 'Entrepreneuriat et Business'],
            datasets: [
                {
                    label: 'Nombre de vues',
                    data: [77, 51, 48, 40],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Note moyenne',
                    data: [4.5, 4.0, 3.8, 4.3],
                    backgroundColor: 'rgba(255, 206, 86, 0.6)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Valeurs'
                    }
                }
            }
        }
    });
}
</script>

<script>
    // Remplacer les données statiques par des données dynamiques PHP
    var labels = <?php echo $labelsJson; ?>;
    var vues = <?php echo $vuesJson; ?>;
    var notes = <?php echo $notesJson; ?>;

    // Charger le graphique avec ces données
    loadChart();
</script>


    </div>
  </div>
  
  <!-- Message de retour -->
  <?php if (!empty($message)): ?>
          <p style="color: <?= str_starts_with($message, '✅') ? 'green' : 'red' ?>;">
            <?= htmlspecialchars($message) ?>
          </p>
        <?php endif; ?>

        <!-- Tableau des cours -->
        <?php if (!empty($courses)): ?>
        <h2>Liste des Cours</h2>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Date</th>
              <th>Titre</th>
              <th>Description</th>
              <th>Prix</th>
              <th>Note</th>
              <th>Vues</th>
              <th>Image</th>
              <th>Export</th>
              <th>Contenu</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
              <td><?= $course['id'] ?></td>
              <td><?= date("d/m/Y", strtotime($course['DateAjout'])) ?></td>
              <td><?= htmlspecialchars($course['Titre']) ?></td>
              <td><?= htmlspecialchars($course['Description']) ?></td>
              <td><?= $course['Prix'] ?></td>
              <td><?= $course['Notes'] ?></td>
              <td><?= $course['NbrVu'] ?></td>
              <td>
                <?php if ($course['ImgCover']): ?>
                  <img src="<?= $course['ImgCover'] ?>" alt="Cover">
                <?php endif; ?>
              </td>
              <td>
                <?php if ($course['Exportation']): ?>
                  <a href="<?= $course['Exportation'] ?>" target="_blank">Voir</a>
                <?php endif; ?>
              </td>
              <td><a href="CoursContenu.php?id=<?= $course['id'] ?>" class="btn btn-sm btn-primary">+</a></td>
              <td class="btn-actions">
  <a href="?delete=<?= $course['id'] ?>" class="btn-delete" title="Supprimer">
    <i class="fas fa-trash"></i>
  </a>
  <a href="?update=<?= $course['id'] ?>" class="btn-edit" title="Modifier">
    <i class="fas fa-edit"></i>
  </a>
</td>

            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p>Aucun cours trouvé.</p>
        <?php endif; ?>
      </div>

  <script src="cours.js"></script>
</body>
</html>
