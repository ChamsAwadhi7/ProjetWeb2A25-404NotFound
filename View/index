<?php
require_once '../config.php';

$message = "";

// SUPPRESSION
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM cours WHERE ID = ?");
        $stmt->execute([$id]);
        $message = "✅ Cours supprimé avec succès.";
    } catch (PDOException $e) {
        $message = "❌ Erreur suppression : " . $e->getMessage();
    }
}

// AJOUT
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['courseName'])) {
    $titre = $_POST['courseName'] ?? '';
    $description = $_POST['courseDescription'] ?? '';
    $prix = $_POST['coursePrix'] ?? 0;
    $date = date("Y-m-d");
    $imgCoverPath = "";

    if (isset($_FILES['imgCover']) && $_FILES['imgCover']['error'] === 0) {
        $imgDir = "uploads/covers/";
        $imgName = basename($_FILES["imgCover"]["name"]);
        $imgCoverPath = $imgDir . time() . "_" . $imgName;

        if (!is_dir($imgDir)) {
            mkdir($imgDir, 0777, true);
        }

        if (!move_uploaded_file($_FILES["imgCover"]["tmp_name"], $imgCoverPath)) {
            $message = "❌ Échec de l'upload de l'image de couverture.";
            $imgCoverPath = "";
        }
    } else {
        $message = "❌ Veuillez sélectionner une image de couverture.";
    }

    if (isset($_FILES['courseExport']) && $_FILES['courseExport']['error'] === 0 && $imgCoverPath !== "") {
        $uploadDir = "uploads/";
        $fileName = basename($_FILES["courseExport"]["name"]);
        $targetFilePath = $uploadDir . time() . "_" . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES["courseExport"]["tmp_name"], $targetFilePath)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO cours (DateAjout, Titre, Description, Notes, NbrVu, Prix, Exportation, ImgCover)
                                       VALUES (?, ?, ?, 0, 0, ?, ?, ?)");
                $stmt->execute([$date, $titre, $description, $prix, $targetFilePath, $imgCoverPath]);
                $message = "✅ Cours ajouté avec succès.";
            } catch (PDOException $e) {
                $message = "❌ Erreur SQL : " . $e->getMessage();
            }
        } else {
            $message = "❌ Échec de l'upload du fichier exporté.";
        }
    } elseif ($imgCoverPath !== "") {
        $message = "❌ Veuillez sélectionner un fichier exporté.";
    }
}

// TRI ET RECHERCHE
$orderBy = "id DESC"; // Par défaut
$search = "";
$whereClause = "";

if (isset($_GET['tri'])) {
    switch ($_GET['tri']) {
        case 'id': $orderBy = "id DESC"; break;
        case 'date': $orderBy = "DateAjout DESC"; break;
        case 'note': $orderBy = "Notes DESC"; break;
        case 'vues': $orderBy = "NbrVu DESC"; break;
    }
}

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = trim($_GET['search']);
    $whereClause = "WHERE Titre LIKE :search";
}

// AFFICHAGE
try {
    $query = "SELECT * FROM cours $whereClause ORDER BY $orderBy";
    $stmt = $pdo->prepare($query);

    if ($whereClause) {
        $stmt->execute(['search' => "%$search%"]);
    } else {
        $stmt->execute();
    }

    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $courses = [];
    $message = "❌ Erreur récupération : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Cours</title>
  <link rel="stylesheet" href="cours.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    table { border-collapse: collapse; width: 100%; margin-top: 30px; }
    th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
    th { background-color: #eee; }
    img { max-width: 80px; max-height: 80px; }
    .btn { padding: 5px 10px; margin: 2px; border-radius: 5px; border: none; cursor: pointer; }
    .btn-delete { background-color: #e74c3c; color: white; }
    .btn-edit { background-color: #3498db; color: white; }
    .btn-ressource { background-color: #2ecc71; color: white; }
    .search-bar { margin-top: 20px; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Gestion des Cours</h1>

    <?php if (!empty($message)): ?>
      <p style="color: <?= str_starts_with($message, '✅') ? 'green' : 'red' ?>;">
        <?= htmlspecialchars($message) ?>
      </p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
      <label for="courseName">Titre:</label>
      <input type="text" name="courseName" id="courseName" required>

      <label for="courseDescription">Description :</label>
      <textarea name="courseDescription" id="courseDescription" required></textarea>

      <label for="coursePrix">Prix (dt) :</label>
      <input type="number" name="coursePrix" id="coursePrix" min="0" step="20">

      <label for="imgCover">Image de couverture :</label>
      <input type="file" name="imgCover" id="imgCover" accept="image/*" required>

      <label for="courseExport">Fichier exporté :</label>
      <input type="file" name="courseExport" id="courseExport" accept=".pdf,.jpg,.png,.mp4" required>

      <button type="submit">Ajouter</button>
    </form>

    <form method="GET" class="search-bar">
      <label for="tri">Trier par :</label>
      <select name="tri" id="tri" onchange="this.form.submit()">
        <option value="id" <?= ($_GET['tri'] ?? '') === 'id' ? 'selected' : '' ?>>ID (récent)</option>
        <option value="date" <?= ($_GET['tri'] ?? '') === 'date' ? 'selected' : '' ?>>Date</option>
        <option value="note" <?= ($_GET['tri'] ?? '') === 'note' ? 'selected' : '' ?>>Note</option>
        <option value="vues" <?= ($_GET['tri'] ?? '') === 'vues' ? 'selected' : '' ?>>Vues</option>
      </select>

      <input type="text" name="search" placeholder="🔍 Rechercher un titre..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit">Rechercher</button>
    </form>

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
            <td class="btn-actions">
  <!-- Supprimer -->
  <a href="?delete=<?= $course['id'] ?>" class="btn btn-delete" onclick="return confirm('Supprimer ce cours ?')">
    <i class="fas fa-trash"></i> <!-- Icône de suppression -->
  </a>

  <!-- Éditer -->
  <a href="edit_cours.php?id=<?= $course['id'] ?>" class="btn btn-edit">
    <i class="fas fa-edit"></i> <!-- Icône d'édition -->
  </a>

  <!-- Ajouter Ressource -->
  <a href="ressource.php?id=<?= $course['id'] ?>" class="btn btn-ressource">
    <i class="fas fa-file-upload"></i> <!-- Icône de téléchargement -->
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
  <script>
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
</script>


</body>
</html>
