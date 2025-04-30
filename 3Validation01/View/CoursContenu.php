<?php
require_once '../config.php';

// Vérifiez si l'ID du cours est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de cours invalide.");
}

$cours_id = $_GET['id'];

// Ajouter ou modifier un contenu
if (isset($_POST['submit_content'])) {
    $nomChapitre = $_POST['nomChapitre'];
    $typeContenu = $_POST['typeContenu'];
    $duree = $_POST['duree'];
    $contenu_id = $_POST['contenu_id'] ?? null;

    $fichierPath = null;
    if (isset($_FILES['fichierPath']) && $_FILES['fichierPath']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $fichier_nom = basename($_FILES['fichierPath']['name']);
        $fichierPath = $upload_dir . time() . "_" . $fichier_nom;

        if (!move_uploaded_file($_FILES['fichierPath']['tmp_name'], $fichierPath)) {
            $fichierPath = null;
        }
    }

    if ($contenu_id) {
        // Mise à jour
        $sql = "UPDATE contenucours SET nomChapitre = :nomChapitre, typeContenu = :typeContenu, duree = :duree" . ($fichierPath ? ", fichierPath = :fichierPath" : "") . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nomChapitre', $nomChapitre);
        $stmt->bindParam(':typeContenu', $typeContenu);
        $stmt->bindParam(':duree', $duree);
        $stmt->bindParam(':id', $contenu_id);
        if ($fichierPath) {
            $stmt->bindParam(':fichierPath', $fichierPath);
        }
        $stmt->execute();
    } else {
        // Insertion
        if ($fichierPath !== null) {
            $stmt = $pdo->prepare("INSERT INTO contenucours (cours_id, nomChapitre, typeContenu, fichierPath, duree) VALUES (:cours_id, :nomChapitre, :typeContenu, :fichierPath, :duree)");
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->bindParam(':nomChapitre', $nomChapitre);
            $stmt->bindParam(':typeContenu', $typeContenu);
            $stmt->bindParam(':fichierPath', $fichierPath);
            $stmt->bindParam(':duree', $duree);
            $stmt->execute();
        }
    }
    header("Location: CoursContenu.php?id=" . $cours_id);
    exit();
}

// Suppression
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM contenucours WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: CoursContenu.php?id=" . $cours_id);
    exit();
}

// Récupérer les contenus du cours
$stmt = $pdo->prepare("SELECT * FROM contenucours WHERE cours_id = :cours_id");
$stmt->bindParam(':cours_id', $cours_id);
$stmt->execute();
$contenus = $stmt->fetchAll();

// Récupérer un contenu à modifier (si demandé)
$contenu_edit = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM contenucours WHERE id = :id");
    $stmt->bindParam(':id', $edit_id);
    $stmt->execute();
    $contenu_edit = $stmt->fetch();
}

// Compter le nombre de contenus pour ce cours
$stmt_count = $pdo->prepare("SELECT COUNT(*) AS total FROM contenucours WHERE cours_id = :cours_id");
$stmt_count->bindParam(':cours_id', $cours_id);
$stmt_count->execute();
$count = $stmt_count->fetch(PDO::FETCH_ASSOC);
$total_contenus = $count['total'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contenus du Cours</title>
    <link rel="stylesheet" href="CoursContenu.css">
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

      <a href="cours.php" class="btn-retour">← Retour aux cours</a>
      <h2><?= $contenu_edit ? 'Modifier' : 'Ajouter' ?> un Contenu</h2>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="contenu_id" value="<?= $contenu_edit['id'] ?? '' ?>">
    <label>Nom Chapitre</label>
    <input type="text" name="nomChapitre" value="<?= $contenu_edit['nomChapitre'] ?? '' ?>" required>

    <label>Type de Contenu</label>
<select name="typeContenu" required>
    <option value="">--Choisir--</option>
    <option value="Image" <?= isset($contenu_edit['typeContenu']) && $contenu_edit['typeContenu'] == 'Image' ? 'selected' : '' ?>>Image</option>
    <option value="Vidéo" <?= isset($contenu_edit['typeContenu']) && $contenu_edit['typeContenu'] == 'Vidéo' ? 'selected' : '' ?>>Vidéo</option>
    <option value="PDF" <?= isset($contenu_edit['typeContenu']) && $contenu_edit['typeContenu'] == 'PDF' ? 'selected' : '' ?>>PDF</option>
    <option value="Quiz" <?= isset($contenu_edit['typeContenu']) && $contenu_edit['typeContenu'] == 'Quiz' ? 'selected' : '' ?>>Quiz</option>
</select>


    <label>Durée</label>
    <input type="text" name="duree" value="<?= $contenu_edit['duree'] ?? '' ?>" required>

    <label>Fichier</label>
    <input type="file" name="fichierPath" <?= $contenu_edit ? '' : 'required' ?>>

    <button type="submit" name="submit_content" class="btn"><?= $contenu_edit ? 'Mettre à jour' : 'Ajouter' ?></button>
</form>

<h2>Contenus du Cours - ID: <?= htmlspecialchars($cours_id) ?></h2>


<table>
    <tr>
        <th>ID</th>
        <th>Chapitre</th>
        <th>Type</th>
        <th>Durée</th>
        <th>Fichier</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($contenus as $contenu): ?>
        <tr>
            <td><?= $contenu['id'] ?></td>
            <td><?= $contenu['nomChapitre'] ?></td>
            <td><?= $contenu['typeContenu'] ?></td>
            <td><?= $contenu['duree'] ?></td>
            <td><a href="<?= $contenu['fichierPath'] ?>" target="_blank">Voir</a></td>
            <td>
                <a href="?id=<?= $cours_id ?>&edit=<?= $contenu['id'] ?>" class="btn btn-secondary">Modifier</a>
                <a href="?id=<?= $cours_id ?>&delete=<?= $contenu['id'] ?>" class="btn btn-danger" onclick="return confirm('Supprimer ce contenu ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</main>

<!-- === Right Section (Sidebar droite) === -->
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

      <div class="recent_updates">
        <h2>Dernières Mises à jour</h2>
        <div class="updates">
          <div class="update">
            <div class="profile-photo"><img src="./images/design.jpg" alt=""/></div>
            <div class="message"><p><b>2025-06-01</b> Formation interactive pour designers</p></div>
          </div>
          <div class="update">
            <div class="profile-photo"><img src="./images/tech.jpg" alt=""/></div>
            <div class="message"><p><b>2025-05-10</b> Conférence Tech 2025</p></div>
          </div>
          <div class="update">
            <div class="profile-photo"><img src="./images/product.jpg" alt=""/></div>
            <div class="message"><p><b>2025-04-20</b> Lancement Produit</p></div>
          </div>
        </div>
      </div>
    </div>
  </div>

<script>
document.querySelector('form').addEventListener('submit', function (e) {
    const nomChapitre = document.querySelector('[name="nomChapitre"]').value.trim();
    const duree = parseInt(document.querySelector('[name="duree"]').value.trim(), 10);

    // Vérifie que le nom commence par une majuscule
    if (!/^[A-ZÀÂÄÇÉÈÊËÎÏÔÖÙÛÜŸ]/.test(nomChapitre)) {
        alert("Le nom du chapitre doit commencer par une majuscule.");
        e.preventDefault();
        return;
    }

    // Vérifie que la durée est un nombre entre 3 et 240
    if (isNaN(duree) || duree < 3 || duree > 240) {
        alert("La durée doit être comprise entre 3 et 240 minutes.");
        e.preventDefault();
        return;
    }
});
</script>


</body>
</html>