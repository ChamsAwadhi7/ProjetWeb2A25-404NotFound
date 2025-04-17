<?php
require_once __DIR__ . '/../controller/EventController.php';
require_once __DIR__ . '/../config.php';
?>
<?php


// Exécution de la requête pour récupérer tous les événements
$stmt = $pdo->query("SELECT * FROM events");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Récupération des résultats sous forme de tableau associatif

// Retour des événements sous forme de JSON pour utilisation côté frontend
echo json_encode($events);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UI/UX</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css">
</head>
<body>
   <div class="container">
      <aside>
         <div class="top">
           <div class="logo">
             <h2>C <span class="danger">NextStep</span> </h2>
           </div>
           <div class="close" id="close_btn">
            <span class="material-symbols-sharp">close</span>
           </div>
         </div>
          <div class="sidebar">
            <a href="#">
              <span class="material-symbols-sharp">grid_view</span>
              <h3>Dashboard</h3>
           </a>
           <a href="index.html">
              <span class="material-symbols-sharp">person_outline</span>
              <h3>Clients</h3>
           </a>
           <a href="Event.php" class="active">
              <span class="material-symbols-sharp">receipt_long</span>
              <h3>Événements</h3>
           </a>
           <a href="#">
              <span class="material-symbols-sharp">logout</span>
              <h3>Déconnexion</h3>
           </a>
            <a href="#">
                <span class="material-symbols-sharp">settings</span>
                <h3>Paramètres</h3>
            </a>
          </div>
      </aside>
      <main>
        <h1>Tableau de bord Admin – Événements</h1>
        
        <div id="form-container">
            <h2 id="form-title"><?= isset($event) ? 'Modifier un événement' : 'Créer un événement' ?></h2>
            <form method="POST" enctype="multipart/form-data" id="event-form">
    <?php if (isset($event)): ?>
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= $event['id_event'] ?>">
    <?php endif; ?>

    <label>Nom de l'événement:</label>
    <input type="text" id="nom" name="nom" value="<?= isset($event) ? htmlspecialchars($event['nom_event']) : '' ?>">
    <span class="error" id="error-nom"></span><br />

    <label>Date:</label>
    <input type="date" id="date" name="date" value="<?= isset($event) ? htmlspecialchars($event['date_event']) : '' ?>">
    <span class="error" id="error-date"></span><br />

    <label>Description:</label>
    <textarea name="description" id="desc"><?= isset($event) ? htmlspecialchars($event['desc_event']) : '' ?></textarea>
    <span class="error" id="error-desc"></span><br />

    <label>Lieu:</label>
    <input type="text" id="lieu" name="lieu" value="<?= isset($event) ? htmlspecialchars($event['lieu_event']) : '' ?>">
    <span class="error" id="error-lieu"></span><br />

    <label>Image:</label>
    <input type="file" id="img" name="image" accept="image/*">
    <span class="error" id="error-img"></span><br />

    <button type="submit"><i class="fas fa-save"></i> <?= isset($event) ? 'Mettre à jour' : 'Enregistrer' ?></button>

    <?php if (isset($event)): ?>
        <a href="Event.php" class="btn btn-cancel">Annuler</a>
    <?php endif; ?>
</form>

        </div>

        <hr />

        <h2>Liste des événements</h2>
        <div class="search-container">
          <i class="fas fa-search"></i>
          <input type="text" id="search-box" placeholder="Rechercher des événements...">
          <select id="sort-select" class="sort-select">
            <option value="asc">Trier par date ASC</option>
            <option value="desc">Trier par date DESC</option>
          </select>
        </div>
        <table id="event-table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Date</th>
              <th>Description</th>
              <th>Lieu</th>
              <th>Image</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($events as $event): ?>
              <tr>
                <td><?= htmlspecialchars($event['nom_event']) ?></td>
                <td><?= htmlspecialchars($event['date_event']) ?></td>
                <td><?= htmlspecialchars($event['desc_event']) ?></td>
                <td><?= htmlspecialchars($event['lieu_event']) ?></td>
                <td>
                  <?php if (!empty($event['img_event'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($event['img_event']) ?>" alt="Image événement" style="width: 80px; height: 80px; object-fit: cover;" />
                  <?php else: ?>
                    <img src="../assets/default.jpg" alt="Image par défaut" style="width: 80px; height: 80px; object-fit: cover;" />
                  <?php endif; ?>
                </td>
                <td>
                    <a href="Event.php?id=<?= $event['id_event'] ?>&action=edit" class="btn btn-primary">Modifier</a>
                    <a href="Event.php?id=<?= $event['id_event'] ?>&action=delete" class="btn btn-danger">Supprimer</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </main>
   </div>
   <script src="script.js"></script>
</body>
</html>
