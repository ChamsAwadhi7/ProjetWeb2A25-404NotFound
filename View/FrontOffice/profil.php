<?php
session_start();
require_once '../../Models/users.php';
require_once '../../config/Database.php';

if (!isset($_SESSION['user'])) { header('Location: login_register.php'); exit; }
$userObj = unserialize($_SESSION['user']);
if (!$userObj instanceof User) { header('Location: login_register.php'); exit; }
$userId = $userObj->getId();

try {
    $pdo = Database::getInstance()->getConnection();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom    = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email  = trim($_POST['email']);
        $tel    = trim($_POST['tel']);
        $pdo->prepare("UPDATE utilisateur SET nom=?, `prénom`=?, email=?, tel=? WHERE id=?")
            ->execute([$nom, $prenom, $email, $tel, $userId]);
        header('Location: profil.php?update=success'); exit;
    }
    $stmt = $pdo->prepare("SELECT id, nom, `prénom` AS prenom, email, tel, role, date_inscription, photo FROM utilisateur WHERE id=?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Profil Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { margin:0; font-family:'Segoe UI',sans-serif; background:#f0f2f5; display:flex; }
        .sidebar { width:220px; background:#2c3e50; color:#ecf0f1; display:flex; flex-direction:column; padding-top:30px; position:fixed; height:100%; }
        .sidebar h3 { text-align:center; margin-bottom:30px; font-weight:normal; }
        .sidebar a { padding:15px 20px; color:#ecf0f1; text-decoration:none; display:flex; align-items:center; transition:0.3s; }
        .sidebar a:hover, .sidebar a.active { background:#34495e; }
        .sidebar a i { margin-right:10px; }
        .main { margin-left:220px; padding:40px; width:calc(100% - 220px); }
        .profile-card { background:#fff; padding:30px; border-radius:16px; box-shadow:0 8px 20px rgba(0,0,0,0.1); }
        .profile-header { text-align:center; margin-bottom:30px; }
        .profile-header img { width:120px; height:120px; object-fit:cover; border-radius:50%; border:4px solid #6c5ce7; }
        .profile-header h2 { margin-top:15px; font-size:24px; color:#333; }
        .profile-header p { color:#777; }
        .tab-content .card-body { padding:2rem; }
        .nav-tabs .nav-link.active { border-color:#6c5ce7 #6c5ce7 #fff; color:#6c5ce7; }
        .form-control-plaintext { padding-left:0; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Menu</h3>
        <a href="profil.php" class="active"><i class="fas fa-user"></i> Profil</a>
        <a href="financeU.php"><i class="fas fa-chart-line"></i> Finance</a>
        <a href="startupU.php"><i class="fas fa-lightbulb"></i> Startups</a>
        <a href="eventU.php"><i class="fas fa-calendar-alt"></i> Événements</a>
        <a href="#"><i class="fas fa-graduation-cap"></i> Formations</a>
        <a href="coursU.php"><i class="fas fa-book"></i> Cours</a>
        <a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
    </div>
    <div class="main">
        <div class="profile-card">
            <div class="profile-header">
                <img src="<?= htmlspecialchars($user['photo'] ?? 'https://via.placeholder.com/120') ?>" alt="Avatar">
                <h2><?= htmlspecialchars($user['prenom'].' '.$user['nom']) ?></h2>
                <p><i class="fas fa-user-tag"></i> <?= htmlspecialchars(ucfirst($user['role'])) ?></p>
            </div>
            <ul class="nav nav-tabs justify-content-center" id="profileTabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#general">General</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#edit">Modifier</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#password">Mot de passe</a></li>
            </ul>
            <div class="tab-content">
                <div id="general" class="tab-pane fade show active">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr><th>ID</th><td><?= htmlspecialchars($user['id']) ?></td></tr>
                            <tr><th>Nom</th><td><?= htmlspecialchars($user['nom']) ?></td></tr>
                            <tr><th>Prénom</th><td><?= htmlspecialchars($user['prenom']) ?></td></tr>
                            <tr><th>Email</th><td><?= htmlspecialchars($user['email']) ?></td></tr>
                            <tr><th>Téléphone</th><td><?= htmlspecialchars($user['tel']) ?></td></tr>
                            <tr><th>Date inscription</th><td><?= htmlspecialchars($user['date_inscription']) ?></td></tr>
                        </table>
                    </div>
                </div>
                <div id="edit" class="tab-pane fade">
                    <?php if (isset($_GET['update']) && $_GET['update']==='success'): ?>
                        <div class="alert alert-success">Profil mis à jour avec succès.</div>
                    <?php endif; ?>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Prénom</label>
                                    <input type="text" name="prenom" class="form-control" placeholder="Prénom" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Nom</label>
                                    <input type="text" name="nom" class="form-control" placeholder="Nom" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Téléphone</label>
                                    <input type="text" name="tel" class="form-control" placeholder="Téléphone" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Enregistrer</button>
                        </form>
                    </div>
                </div>
                <div id="password" class="tab-pane fade">
                    <div class="card-body">
                        <form action="change_password.php" method="post">
                            <input type="hidden" name="id" value="<?= $userId ?>">
                            <div class="form-group">
                                <label>Mot de passe actuel</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Nouveau mot de passe</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Confirmer mot de passe</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning float-right">Changer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
