<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['admin'])) {
 header('Location: login.php');
  exit();
}
require_once __DIR__ . '/../../config/Database.php';
require_once '../../Models/users.php';
require_once '../../Controllers/userscontrollers.php';



$pdo = Database::getInstance()->getConnection();
$userCount = countActiveUsers($pdo);

// Pagination, tri, recherche
$utilisateursParPage = 5;
$pageActuelle = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($pageActuelle - 1) * $utilisateursParPage;

$allowedSorts = ['nom', 'email', 'role'];
$allowedOrders = ['asc', 'desc'];

$sort = $_GET['sort'] ?? 'id';
$order = strtolower($_GET['order'] ?? 'asc');
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM utilisateur";
$params = [];

if (!empty($search)) {
    $sql .= " WHERE nom LIKE :search OR email LIKE :search";
    $params['search'] = "%$search%";
}

if (in_array($sort, $allowedSorts) && in_array($order, $allowedOrders)) {
    $sql .= " ORDER BY $sort $order";
} else {
    $sql .= " ORDER BY id ASC"; // tri par défaut
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$users = array_map(fn($row) => new User($row), $rows);



//count utilisateurs 
$totalUtilisateurs = (int)$pdo->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
$nombrePages = ceil($totalUtilisateurs / $utilisateursParPage);

//supprission de user 
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $result = deleteUser($pdo, $id);

  if ($result) {
      echo "✅ Utilisateur supprimé avec succès.";
      header("Location: administration.php?success=1");
exit();
  } else {
      echo "❌ Erreur lors de la suppression.";
  }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de Bord Admin - NextStep</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="container">
    <aside>
      <div class="top">
        <div class="logo">
          <h2>C <span class="danger">NextStep</span></h2>
        </div>
      </div>
      <div class="sidebar">
        <a href="dashboard.php"><span class="material-symbols-sharp">grid_view</span><h3>Dashboard</h3></a>
        <a href="administration.php" class="active"><span class="material-symbols-sharp">admin_panel_settings</span><h3>Admin</h3></a>
        <a href="customers.php"><span class="material-symbols-sharp">person_outline</span><h3>Customers</h3></a>
        <a href="users.php"><span class="material-symbols-sharp">group</span><h3>Users</h3></a>
        <a href="events.php"><span class="material-symbols-sharp">receipt_long</span><h3>Events</h3></a>
        <a href="cours.php"><span class="material-symbols-sharp">receipt_long</span><h3>Cours</h3></a>
        <a href="startup.php"><span class="material-symbols-sharp">business</span><h3>Startups</h3></a>
        <a href="incubator.php"><span class="material-symbols-sharp">business</span><h3>Incubators</h3></a>
        <a href="logout_admin.php"><span class="material-symbols-sharp">logout</span><h3>Logout</h3></a>
        <a href="settings.php"><span class="material-symbols-sharp">settings</span><h3>Settings</h3></a>
      </div>
    </aside>

    <main>
      <div class="administration-title">
        <h1>Tableau de Bord Administrateur</h1>

        <?php if (isset($_GET['success'])): ?>
          <div class="alert success">
            <?php
              switch ($_GET['success']) {
                case 'add': echo "L'administrateur a été ajouté avec succès !"; break;
                case 'update': echo "L'utilisateur a été mis à jour."; break;
                case 'delete': echo "L'utilisateur a été supprimé."; break;
              }
            ?>
          </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
          <div class="alert error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
      </div>

      <div class="administration-grid">
        <div class="administration-card users">
          <div class="card-icon"><span class="material-icons-sharp">group</span></div>
          <h3>Utilisateurs</h3>
          <p><?= htmlspecialchars($userCount) ?> utilisateurs actifs</p>
        </div>
        <div class="administration-card events">
          <div class="card-icon"><span class="material-icons-sharp">event</span></div>
          <h3>Événements</h3>
          <p>événements à venir</p>
        </div>
        <div class="administration-card startups">
          <div class="card-icon"><span class="material-icons-sharp">business</span></div>
          <h3>Startups</h3>
          <p>startups incubées</p>
        </div>
      </div>

      <div class="search-bar">
      <input type="text" id="userSearch" placeholder="Rechercher par nom, email..." class="search-input">

      </div>

      <div class="user-management">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
          <h2>Liste des utilisateurs</h2>
          <a href="export_pdf.php" class="export-btn">Exporter PDF</a>
        </div>

        <div class="user-table-container">
          <table id="usersTable">
            <thead>
              <tr>
                <th>ID</th>
                <th><a href="?sort=nom&order=asc">Nom ▲</a> | <a href="?sort=nom&order=desc">Nom ▼</a></th>
                <th><a href="?sort=email&order=asc">Email ▲</a> | <a href="?sort=email&order=desc">Email ▼</a></th>
                <th>Rôle</th>
                <th>Téléphone</th>
                <th colspan="2">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $user): ?>
              <tr>
                <td><?= htmlspecialchars($user->getId()) ?></td>
                <td><?= htmlspecialchars($user->getNom()) ?></td>
                <td><?= htmlspecialchars($user->getEmail()) ?></td>
                <td>
                  <form method="POST" action="../../Controllers/userscontrollers.php?action=update_admin">
                    <input type="hidden" name="id" value="<?= $user->getId() ?>">
                    <select name="role">
                      <option value="user" <?= $user->getRole() === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                      <option value="admin" <?= $user->getRole() === 'admin' ? 'selected' : '' ?>>Admin</option>
                      <option value="investisseur" <?= $user->getRole() === 'investisseur' ? 'selected' : '' ?>>Investisseur</option>
                      <option value="entrepreneur" <?= $user->getRole() === 'entrepreneur' ? 'selected' : '' ?>>Entrepreneur</option>
                    </select>
                    <button type="submit" class="update-btn">Mettre à jour</button>
                  </form>
                </td>
                <td><?= htmlspecialchars($user->getTel()) ?></td>
                <td>
                <a href="administration.php?action=delete&id=<?= $user->getId() ?>" onclick="return confirm('Supprimer cet utilisateur ?');">
                <button class="delete-btn">Supprimer</button>
</a>

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
            <a href="administration.php?page=<?= $i ?>" class="<?= $i == $pageActuelle ? 'active' : '' ?>"><?= $i ?></a>
          <?php endfor; ?>
          <?php if ($pageActuelle < $nombrePages): ?>
            <a href="administration.php?page=<?= $pageActuelle + 1 ?>">Suivant &raquo;</a>
          <?php endif; ?>
        </div>
      </div>

      <div class="admin-recruitment">
      <h2>Ajouter un Administrateur</h2>
<form id="adminForm" method="POST" action="../../Controllers/userscontrollers.php?action=add_admin">
  <div class="form-grid">
    <div class="form-group">
      <label for="lastname">Nom</label>
      <input type="text" id="lastname" name="lastname" required>
      <span class="error-message" id="error-lastname" style="color:red;"></span>
    </div>
    <div class="form-group">
      <label for="firstname">Prénom</label>
      <input type="text" id="firstname" name="firstname" required>
      <span class="error-message" id="error-firstname" style="color:red;"></span>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>
      <span class="error-message" id="error-email" style="color:red;"></span>
    </div>
    <div class="form-group">
      <label for="phone">Téléphone</label>
      <input type="tel" id="phone" name="phone" required>
      <span class="error-message" id="error-phone" style="color:red;"></span>
    </div>
    <div class="form-group">
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required>
      <span class="error-message" id="error-password" style="color:red;"></span>
    </div>
  </div>
  <button type="submit" class="submit-btn">Ajouter Administrateur</button>
</form>

      </div>
    </main>
  </div>

  <script src="admin.js"></script>
</body>
</html>
