<?php
require_once __DIR__ . '/../../Controller/eventController.php';
require_once __DIR__ . '/../../Model/eventModel.php';
require_once __DIR__ . '/../../config.php';

$eventC = new EventC();
$db = config::getConnexion();

// Initialisation des variables
$error = '';
$success = '';
$eventToEdit = null;
$events = [];

// Traitement des actions CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $event = new Event();
        $event->setNomEvent($_POST['nom'] ?? '');
        $event->setDateEvent($_POST['date'] ?? '');
        $event->setDescEvent($_POST['description'] ?? '');
        $event->setLieuEvent($_POST['lieu'] ?? '');
        
        // Gestion de l'image
        // Dans la partie gestion de l'image, remplacez :
if (!empty($_FILES['image']['name'])) {
  // Vérification du type de fichier
  $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
  $fileType = $_FILES['image']['type'];
  
  if (!in_array($fileType, $allowedTypes)) {
      throw new Exception("Seuls les fichiers JPEG, PNG et GIF sont autorisés");
  }
  
  // Vérification de la taille du fichier (max 2MB)
  if ($_FILES['image']['size'] > 8097152) {
      throw new Exception("La taille de l'image ne doit pas dépasser 2MB");
  }
  
  // Nouveau code pour l'upload du fichier
  $uploadDir = 'uploads/events/';
  if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true); // Crée le dossier avec permissions
  }
  
  $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
  $uploadPath = $uploadDir . $fileName;
  
  if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
      $event->setImgEvent($uploadPath); // Stocke le chemin relatif
  } else {
      throw new Exception("Erreur lors de l'enregistrement de l'image");
  }
} elseif (isset($_POST['id']) && empty($_FILES['image']['name'])) {
  // Garder l'image existante lors de la mise à jour
  $existingEvent = $eventC->getEventById($_POST['id']);
  $event->setImgEvent($existingEvent['img_event']);
} else {
  // Image par défaut si aucune image n'est fournie
  $event->setImgEvent('assets/default-event.jpg');
}
        
        if ($_POST['action'] === 'update') {
            $event->setIdEvent($_POST['id']);
            $rowsAffected = $eventC->updateEvent($event, $_POST['id']);
            if ($rowsAffected > 0) {
                header("Location: Event.php?success=update");
                exit();
            } else {
                throw new Exception("Aucune modification effectuée");
            }
        } else {
            $event->setIdEvent(uniqid());
            $eventC->addEvent($event);
            header("Location: Event.php?success=add");
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Traitement des actions GET
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;
$searchTerm = $_GET['search'] ?? null;

if ($action === 'delete' && $id) {
    try {
        $rowsAffected = $eventC->deleteEvent($id);
        if ($rowsAffected > 0) {
            header("Location: Event.php?success=delete");
            exit();
        } else {
            throw new Exception("Événement non trouvé ou déjà supprimé");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Récupérer un événement pour modification
if ($action === 'edit' && $id) {
    $eventToEdit = $eventC->getEventById($id);
    if (!$eventToEdit) {
        $error = "Événement non trouvé";
    }
}

// Récupérer les événements (avec recherche si applicable)
try {
    if ($searchTerm) {
        $events = $eventC->searchEvents($searchTerm);
    } else {
        $events = $eventC->listEvents();
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

$sortOrder = $_GET['sort'] ?? 'desc';  // Par défaut, tri DESC

try {
    if ($searchTerm) {
        if ($sortOrder === 'asc') {
            $events = $eventC->searchEventsAsc($searchTerm); // Tri ASC
        } else {
            $events = $eventC->searchEventsDesc($searchTerm); // Tri DESC
        }
    } else {
        if ($sortOrder === 'asc') {
            $events = $eventC->listEventsAsc(); // Tri ASC
        } else {
            $events = $eventC->listEvents(); // Tri DESC
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}


// Message de succès
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'add': $success = "Événement ajouté avec succès"; break;
        case 'update': $success = "Événement mis à jour avec succès"; break;
        case 'delete': $success = "Événement supprimé avec succès"; break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Événements - NextStep</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css"/>
  <style>
    
    #form-container {
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 30px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    #form-title {
      margin-top: 0;
      color: #2c3e50;
    }
    
    form label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }
    
    form input[type="text"],
    form input[type="date"],
    form textarea,
    form input[type="file"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }
    
    form textarea {
      height: 100px;
      resize: vertical;
    }
    
    button[type="submit"] {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      margin-top: 10px;
    }
    
    button[type="submit"]:hover {
      background-color: #2980b9;
    }
    
    .btn-cancel {
      display: inline-block;
      background-color: #95a5a6;
      color: white;
      padding: 10px 15px;
      border-radius: 4px;
      text-decoration: none;
      margin-left: 10px;
    }
    
    .btn-cancel:hover {
      background-color: #7f8c8d;
    }
    
    .current-image {
      margin: 10px 0;
    }
    
    .current-image img {
      border: 1px solid #ddd;
    }
    
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }
    
    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
   /* Style général */
.event-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

.event-table th, .event-table td {
    padding: 15px;
    text-align: left;
    font-size: 14px;
    color: #333;
}

.event-table th {
    background-color: var(--clr-primary);
    color: white;
    font-weight: bold;
}

.event-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.event-table tr:hover {
    background-color: #f1f1f1;
}

.event-table .btn {
    padding: 8px 12px;
    text-decoration: none;
    color: white;
    border-radius: 4px;
    font-size: 14px;
}

.event-table .btn-primary {
    background-color: var(--clr-primary);
}

.event-table .btn-danger {
    background-color: #e74c3c;
}

.event-table .btn:hover {
    opacity: 0.8;
}

.search-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 1rem;
}

.search-container input, .search-container select {
    padding: 10px;
    font-size: 14px;
    border-radius: 5px;
    border: 1px solid #ddd;
    width: 200px;
}

.search-container input:focus, .search-container select:focus {
    border-color: var(--clr-primary);
    outline: none;
}

/* Pour l'image d'événement */
.event-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

/* Pour centrer le titre */
h2 {
    text-align: center;
    color: var(--clr-dark);
    margin-bottom: 20px;
}

/* Pour l'icône de recherche */
.search-container i {
    font-size: 18px;
    color: var(--clr-primary);
}

    .btn {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 0.9rem;
    }
    
    .btn-primary {
      background-color: #3498db;
      color: white;
    }
    
    .btn-primary:hover {
      background-color: #2980b9;
    }
    
    .btn-danger {
      background-color: #e74c3c;
      color: white;
    }
    
    .btn-danger:hover {
      background-color: #c0392b;
    }
    
    .error {
      color: #e74c3c;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  
   <div class="container">
      <aside>
         <div class="top">
           <div class="logo">
             <h2>C <span class="danger">NextStep</span></h2>
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
           <a href="participants.php">
              <span class="material-symbols-sharp">group </span>
              <h3>participants</h3>
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
      <center>
  <div class="section-title" style="display: flex; align-items: center; justify-content: center; gap: 10px; color: var(--clr-dark);">
    <span class="material-symbols-sharp" style="background-color: var(--clr-primary); color: #fff; padding: 0.6rem; border-radius: 50%; font-size: 2rem;">event</span>
    <h1 style="margin: 0; font-size: 2.5rem; font-weight: bold;">Tableau de bord Admin – Événements</h1>
  </div>
</center>

        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div id="form-container">
            <h2 id="form-title"><?= isset($eventToEdit) ? 'Modifier un événement' : 'Créer un événement' ?></h2>
            <form method="POST" enctype="multipart/form-data" id="event-form">
                <?php if (isset($eventToEdit)): ?>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($eventToEdit['id_event']) ?>">
                    <input type="hidden" name="keep_image" value="1">
                <?php else: ?>
                    <input type="hidden" name="action" value="add">
                <?php endif; ?>

                <label for="nom">
    <i class="fas fa-calendar-day"></i> Nom de l'événement:
</label>
<input type="text" id="nom" name="nom" value="<?= isset($eventToEdit) ? htmlspecialchars($eventToEdit['nom_event']) : '' ?>" >
<span class="error" id="error-nom"></span><br />

<label for="date">
    <i class="fas fa-calendar-alt"></i> Date:
</label>
<input type="date" id="date" name="date" value="<?= isset($eventToEdit) ? htmlspecialchars($eventToEdit['date_event']) : '' ?>">
<span class="error" id="error-date"></span><br />

<label for="desc">
    <i class="fas fa-pencil-alt"></i> Description:
</label>
<textarea name="description" id="desc"><?= isset($eventToEdit) ? htmlspecialchars($eventToEdit['desc_event']) : '' ?></textarea>
<span class="error" id="error-desc"></span><br />

<label for="lieu">
    <i class="fas fa-map-marker-alt"></i> Lieu:
</label>
<input type="text" id="lieu" name="lieu" value="<?= isset($eventToEdit) ? htmlspecialchars($eventToEdit['lieu']) : '' ?>">
<span class="error" id="error-lieu"></span><br />

<label for="img">
    <i class="fas fa-image"></i> Image:
</label>
<input type="file" id="img" name="image" accept="image/*">
<?php if (isset($eventToEdit) && !empty($eventToEdit['img_event'])): ?>
    <div class="current-image">
        <img src="data:image/jpeg;base64,<?= base64_encode($eventToEdit['img_event']) ?>" alt="Image actuelle" style="width: 80px; height: 80px; object-fit: cover;">
        <small>Image actuelle</small>
    </div>
<?php endif; ?>
<span class="error" id="error-img"></span><br />

<button type="submit"><i class="fas fa-save"></i> <?= isset($eventToEdit) ? 'Mettre à jour' : 'Enregistrer' ?></button>

<?php if (isset($eventToEdit)): ?>
    <a href="Event.php" class="btn btn-cancel">Annuler</a>
<?php endif; ?>
</form>
</div>
        <hr />

        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; color: var(--clr-dark);">
    <span class="material-symbols-sharp" style="background-color: var(--clr-primary); color: #fff; padding: 0.5rem; border-radius: 50%; font-size: 1.8rem;">
        list
    </span>
    <h2 style="margin: 0; font-size: 1.8rem;">Liste des événements</h2>
</div>

        <div class="search-container">
          <i class="fas fa-search"></i>
          <input type="text" id="search-box" placeholder="Rechercher des événements..." value="<?= isset($searchTerm) ? htmlspecialchars($searchTerm) : '' ?>">
          <select id="sort-select" class="sort-select">
            <option value="desc">Trier par date DESC</option>
            <option value="asc">Trier par date ASC</option>
          </select>
        </div>
        
        <?php if (empty($events)): ?>
            <p>Aucun événement trouvé.</p>
        <?php else: ?>
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
                    <td><?= date('d/m/Y', strtotime($event['date_event'])) ?></td>
                    <td><?= htmlspecialchars(substr($event['desc_event'], 0, 50)) ?><?= strlen($event['desc_event']) > 50 ? '...' : '' ?></td>
                    <td><?= htmlspecialchars($event['lieu']) ?></td>
                    <td>
  <?php if (!empty($event['img_event']) && file_exists($event['img_event'])): ?>
    <img src="<?= htmlspecialchars($event['img_event']) ?>" alt="Image événement" style="width: 80px; height: 80px; object-fit: cover;" />
  <?php else: ?>
    <img src="assets/default-event.jpg" alt="Image par défaut" style="width: 80px; height: 80px; object-fit: cover;" />
  <?php endif; ?>
</td>
                    <td>
                        <a href="Event.php?id=<?= $event['id_event'] ?>&action=edit" class="btn btn-primary">Modifier</a>
                        <a href="Event.php?id=<?= $event['id_event'] ?>&action=delete" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">Supprimer</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
        <?php endif; ?>
      </main>
   </div>
  </main>
  
   <script>
      document.getElementById('sort-select').addEventListener('change', function() {
    var sortOrder = this.value; // 'asc' ou 'desc'
    var searchTerm = document.getElementById('search-input').value; // Assurez-vous d'avoir un champ de recherche

    // Par exemple, utiliser AJAX pour envoyer le sortOrder et le searchTerm au serveur
    var url = 'path_to_your_php_handler.php';
    var data = new FormData();
    data.append('searchTerm', searchTerm);
    data.append('sortOrder', sortOrder);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Traitez les résultats reçus du serveur et mettez à jour l'interface utilisateur
            console.log(xhr.responseText);
        }
    };
    xhr.send(data);
});

       // Gestion de la recherche
       document.getElementById('search-box').addEventListener('keyup', function(e) {
           if (e.key === 'Enter') {
               const searchTerm = this.value.trim();
               if (searchTerm) {
                   window.location.href = `Event.php?search=${encodeURIComponent(searchTerm)}`;
               } else {
                   window.location.href = 'Event.php';
               }
           }
       });
       
       // Gestion du tri
       document.getElementById('sort-select').addEventListener('change', function() {
           const sortOrder = this.value;
           window.location.href = `Event.php?sort=${sortOrder}`;
       });
       
       // Confirmation avant suppression
       document.querySelectorAll('.btn-danger').forEach(btn => {
           btn.addEventListener('click', function(e) {
               if (!confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')) {
                   e.preventDefault();
               }
           });
       });
       
       // Initialisation du sélecteur de tri
       const urlParams = new URLSearchParams(window.location.search);
       const sortParam = urlParams.get('sort');
       if (sortParam) {
           document.getElementById('sort-select').value = sortParam;
       }
    document.getElementById('event-form').addEventListener('submit', function (e) {
    const nom = document.getElementById('nom').value.trim();
    const date = document.getElementById('date').value;
    const description = document.getElementById('desc').value.trim();
    const lieu = document.getElementById('lieu').value.trim();
    const image = document.getElementById('img').value;

    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    let isValid = true;

    if (!nom) {
        document.getElementById('error-nom').textContent = "Le nom ne doit pas être vide.";
        isValid = false;
    } else {
        if (!/^[A-Z]/.test(nom)) {
            document.getElementById('error-nom').textContent = "Le nom doit commencer par une majuscule.";
            isValid = false;
        } else if (nom.length > 50) {
            document.getElementById('error-nom').textContent = "Le nom ne doit pas dépasser 12 caractères.";
            isValid = false;
        }
    }


    if (!date) {
        document.getElementById('error-date').textContent = "La date ne doit pas être vide.";
        isValid = false;
    } else {
        const today = new Date();
        const selectedDate = new Date(date);
        today.setHours(0, 0, 0, 0);
        if (selectedDate < today) {
            document.getElementById('error-date').textContent = "La date doit être aujourd'hui ou plus tard.";
            isValid = false;
        }
    }

    if (!description) {
        document.getElementById('error-desc').textContent = "La description ne doit pas être vide.";
        isValid = false;
    } else {
        if (!/^[A-Z]/.test(description[0]) && !/\.\s*[A-Z]/.test(description)) {
            document.getElementById('error-desc').textContent = "La description doit commencer par une majuscule ou en contenir après un point.";
            isValid = false;
        } else if (description.length > 900) {
            document.getElementById('error-desc').textContent = "La description ne doit pas dépasser 900 caractères.";
            isValid = false;
        }
    }

    if (!lieu) {
        document.getElementById('error-lieu').textContent = "Le lieu ne doit pas être vide.";
        isValid = false;
    } else {
        if (!/^[A-Z]/.test(lieu)) {
            document.getElementById('error-lieu').textContent = "Le lieu doit commencer par une majuscule.";
            isValid = false;
        } else if (!/,/.test(lieu)) {
            document.getElementById('error-lieu').textContent = "Le lieu doit contenir une virgule.";
            isValid = false;
        } else if (!/\d/.test(lieu)) {
            document.getElementById('error-lieu').textContent = "Le lieu doit contenir des chiffres.";
            isValid = false;
        }
    }

    if (!image) {
        document.getElementById('error-img').textContent = "Veuillez sélectionner une image.";
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
    }
});

   </script>
</body>
</html>