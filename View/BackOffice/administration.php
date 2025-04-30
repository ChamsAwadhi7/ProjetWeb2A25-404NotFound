<?php
  require_once __DIR__ . '/../../config/Database.php';
  
  require_once '../../Models/users.php';
  require_once '../../Models/Evenement.php';
  require_once '../../Models/Projet.php';
  // ... vérification de session ...

  $pdo = Database::getInstance()->getConnection();

  // Récupération des statistiques
  $userCount = User::countActiveUsers($pdo);
  $eventCount = Evenement::countUpcomingEvents($pdo);
  $startupCount = Projet::countIncubatedStartups($pdo);
  session_start();




  // Vérification admin
  //if ($_SESSION['user_role'] !== 'admin') {
    //  header('HTTP/1.0 403 Forbidden');
    //  exit;
  //}

  
  // Nombre d'utilisateurs par page
$utilisateursParPage = 5;

// Quelle page est demandée ?
$pageActuelle = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

// Calcul de l'offset pour la requête SQL
$offset = ($pageActuelle - 1) * $utilisateursParPage;

// Récupérer le nombre total d'utilisateurs
$totalUtilisateursStmt = $pdo->query("SELECT COUNT(*) FROM utilisateur");
$totalUtilisateurs = $totalUtilisateursStmt->fetchColumn();

// Récupérer les utilisateurs pour cette page
$stmt = $pdo->prepare("SELECT * FROM utilisateur LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $utilisateursParPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');

// Calculer le nombre total de pages
$nombrePages = ceil($totalUtilisateurs / $utilisateursParPage);

// tri liste ------------------
// Définir les colonnes valides pour le tri
$validSortColumns = ['nom', 'email', 'role'];
$sort = in_array($_GET['sort'] ?? '', $validSortColumns) ? $_GET['sort'] : 'id';
$order = ($_GET['order'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

// Récupérer le terme de recherche
$search = $_GET['search'] ?? '';
$searchParam = '%' . $search . '%';

// Calculer l'offset pour la pagination
$offset = ($pageActuelle - 1) * $utilisateursParPage;

// Préparer la requête SQL avec recherche et tri
$query = "SELECT * FROM utilisateur WHERE nom LIKE :search OR email LIKE :search ORDER BY $sort $order LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
$stmt->bindValue(':limit', $utilisateursParPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');




  
  ?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord Admin - NextStep</title>
  <link rel="stylesheet"  
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>
  <div class="container">

    <!-- Votre sidebar reste inchangée -->
    <aside>
      <div class="top">
        <div class="logo">
          <h2>C <span class="danger">NextStep</span></h2>
        </div>

      </div>

      <div class="sidebar" class="active">
        <a href="dashboard.php">
          <span class="material-symbols-sharp">grid_view </span>
          <h3>Dashboard</h3>
        </a>
        <a href="administration.php" class="active">
          <span class="material-symbols-sharp">admin_panel_settings</span>
          <h3>Admin</h3>
        </a>

        <a href="customers.php">
          <span class="material-symbols-sharp">person_outline </span>
          <h3>Customers</h3>
        </a>
        <a href="users.php">
          <span class="material-symbols-sharp">group</span>
          <h3>Users</h3>
        </a>

        <a href="events.php">
          <span class="material-symbols-sharp">receipt_long </span>
          <h3>Events</h3>
        </a>
        <a href="cours.php">
          <span class="material-symbols-sharp">receipt_long </span>
          <h3>Cours</h3>
        </a>
        <a href="startup.php">
          <span class="material-symbols-sharp"> business </span>
          <h3>Startups</h3>
        </a>
        <a href="incubator.php" id="incubators-btn">
          <span class="material-symbols-sharp">business</span>
          <h3>Incubators</h3>
        </a>
        <a href="../Controllers/logout.php">
          <span class="material-symbols-sharp">logout </span>
          <h3>Logout</h3>
        </a>
        <a href="settings.php">
          <span class="material-symbols-sharp">settings</span>
          <h3>Settings</h3>
        </a>
      </div>
    </aside>
    <!-- Votre sidebar reste inchangée -->

    <main>
      <div class="administration-title">
        <h1>Tableau de Bord Administrateur</h1>

        <div class="alert success"></div>




        <div class="alert error"></div>


      </div>

      <!-- Section des cartes statistiques -->
      <div class="administration-grid">
        <div class="administration-card users">
          <div class="card-icon">
            <span class="material-icons-sharp">group</span>
          </div>
          <h3>Utilisateurs</h3>
          <p>
            <?= htmlspecialchars($userCount) ?> utilisateurs actifs
          </p>
        </div>

        <div class="administration-card events">
          <div class="card-icon">
            <span class="material-icons-sharp">event</span>
          </div>
          <h3>Événements</h3>
          <p>
            <?= htmlspecialchars($eventCount) ?> événements à venir
          </p>
        </div>

        <div class="administration-card startups">
          <div class="card-icon">
            <span class="material-icons-sharp">business</span>
          </div>
          <h3>Startups</h3>
          <p>
            <?= htmlspecialchars($startupCount) ?> startups incubées
          </p>
        </div>
      </div>
      <!-- Barre de recherche -->
      <div class="search-bar">
        <input type="text" id="userSearch" placeholder="Rechercher par nom, email...">
      </div>

      <!-- User Management -->
      <div class="user-management">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
  <h2>Liste des utilisateurs</h2>
  <a href="../../Controllers/export_pdf.php" class="export-btn">Exporter PDF</a>
</div>
        <div class="user-table-container">
          
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th><a href="?sort=nom&order=asc">Nom ▲</a> | <a href="?sort=nom&order=desc">Nom ▼</a></th>
                <th><a href="?sort=email&order=asc">Email ▲</a> | <a href="?sort=email&order=desc">Email ▼</a></th>
                <th>Rôle</th>
                <th>Téléphone (si admin)</th>
                <th>Action</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            

              <?php foreach ($users as $user): ?>
              <tr>
                <td>
                  <?= htmlspecialchars($user->getId()) ?>
                </td>
                <td>
                  <?= htmlspecialchars($user->getNom()) ?>
                </td>
                <td>
                  <?= htmlspecialchars($user->getEmail()) ?>
                </td>
                <td>
                <?php
    $tel = ''; // Initialiser par défaut

    if ($user->getRole() === 'admin') {
      $stmt = $pdo->prepare("SELECT tel FROM admin WHERE id = ?");
      $stmt->execute([$user->getId()]);
      $tel = $stmt->fetchColumn();
    }
  ?>  
                <select id="roleSelect" class="role-select" data-user-id="<?= $user->getId() ?>">
                <option value="user" <?= $user->getRole() === 'user' ? 'selected' : '' ?>>Utilisateur</option>
  <option value="admin" <?= $user->getRole() === 'admin' ? 'selected' : '' ?>>Admin</option>
  <option value="investisseur" <?= $user->getRole() === 'investisseur' ? 'selected' : '' ?>>Investisseur</option>
  <option value="entrepreneur" <?= $user->getRole() === 'entrepreneur' ? 'selected' : '' ?>>Entrepreneur</option>
</select>
                </td>
                <td>
                      <!-- Champ téléphone visible uniquement si le rôle est "admin" -->
      
          <input type="text" class="tel-input" data-user-id="<?= $user->getId() ?>"value="<?= htmlspecialchars($tel) ?>" >
<div id="telError-<?= $user->getId() ?>" class="tel-error" style="color: red; font-size: 13px; display: none;"></div>
          <div id="telError" style="color: red; font-size: 13px; display: none;">
          Le numéro de téléphone doit contenir exactement 8 chiffres.
          </div>
                </td>
                <td>
                <button id="updateBtn" class="update-btn" data-user-id="<?= $user->getId() ?>">Mettre à jour</button>
                </td>
                <td>
                  <button class="delete-btn" data-user-id="<?= $user->getId() ?>">Suppriimer</button>
                  
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>


        <div class="pagination">
  <?php if ($pageActuelle > 1): ?>
    <a href="administration.php?page=<?= $pageActuelle - 1 ?>">&laquo; Précédent</a>
  <?php endif; ?>

  <?php for ($i = 1; $i <= $nombrePages; $i++): ?>
    <a href="administration.php?page=<?= $i ?>" class="<?= $i == $pageActuelle ? 'active' : '' ?>">
      <?= $i ?>
    </a>
  <?php endfor; ?>

  <?php if ($pageActuelle < $nombrePages): ?>
    <a href="administration.php?page=<?= $pageActuelle + 1 ?>">Suivant &raquo;</a>
  <?php endif; ?>
</div>

      </div>

      <!-- Admin Recruitment -->
      <div class="admin-recruitment">
        <h2>Ajouter un Administrateur</h2>
        <form method="POST" action="../../Controllers/add_admin.php">
          <input type="hidden" name="csrf_token" value="">

          <div class="form-grid">
            <div class="form-group">
              <label for="lastname">Nom</label>
              <input type="text" id="lastname" name="lastname" required>
              <span id="error-lastname" class="error-message"></span>
            </div>

            <div class="form-group">
              <label for="firstname">Prénom</label>
              <input type="text" id="firstname" name="firstname" required>
              <span id="error-firstname" class="error-message"></span>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="text" id="email" name="email" required>
              <span id="error-email" class="error-message"></span>
            </div>

            <div class="form-group">
              <label for="phone">Téléphone</label>
              <input type="tel" id="phone" name="phone" required>
              <span id="error-phone" class="error-message"></span>
            </div>

            <div class="form-group">
              <label for="password">Mot de passe</label>
              <input type="password" id="password" name="password" required>
              <span id="error-password" class="error-message"></span>
            </div>
          </div>

          <button type="submit" class="submit-btn">Ajouter Administrateur</button>
        </form>
      </div>
    </main>
  </div>
  
  <script src="../../assets/js/admin.js"></script>
  
</body>

</html>