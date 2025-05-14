<?php
include_once "../config.php";
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login_register.php');
    exit;
}

$user = $_SESSION['utilisateur'];

// Récupération des données utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
$stmt->execute([$user['id']]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Met à jour l'utilisateur (avec vérification si mot de passe fourni)
    $query = "UPDATE utilisateur SET prénom = ?, email = ?" . (!empty($password) ? ", mot_de_passe = ?" : "") . " WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $params = [$prenom, $email];
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $params[] = $hashed;
    }
    $params[] = $user['id'];
    $stmt->execute($params);
    
    header("Location: settings.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Settings - Dashboard | NextStep</title>
    <link rel="website icon" type="PNG" href="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <aside> 
         <div class="top">
           <div class="logo">
           <h2>
  <a href="http://localhost/4Validation/View/index.php" style="text-decoration: none; color: inherit;">
    <img style="width: 60px; height: 60px;" src="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png" alt="">
    <span class="danger">NextStep</span>
  </a>
</h2>
           </div>
           <div class="close" id="close_btn">
            <span class="material-symbols-sharp">
              close
            </span>
           </div>
         </div>
         <!-- end top -->
          <div class="sidebar">
          <a href="Back.php" class="close">
              <span class="material-symbols-sharp">grid_view </span>
              <h3>Home</h3>
           </a>
           <a href="#">
              <span class="material-symbols-sharp">person_outline </span>
              <h3>Customers</h3>

           </a>
           <a href="EVENT/BackOffice/Event.php" class="close">
              <span class="material-symbols-sharp">receipt_long </span>
              <h3>Events</h3>
            </a>
           <a href="COURS/cours.php" class="close">
            <span class="material-symbols-sharp">receipt_long </span>
            <h3>Courses</h3>
           </a>
           <a href="STARTUP/BackOffice/startup.php" class="close">
            <span class="material-symbols-sharp">business </span>
            <h3>startups</h3>
           </a>
           <a href="STARTUP/BackOffice/incubator.php" id="incubators-btn">
            <span class="material-symbols-sharp">rocket_launch</span>
            <h3>Incubators</h3>
           </a>
           <a href="logout.php">
              <span class="material-symbols-sharp">logout </span>
              <h3>logout</h3>
           </a>
            <a href="settings.php" class="active">
                <span class="material-symbols-sharp">settings</span>
                <h3>Settings</h3>
            </a>
          </div>
      </aside>

        <main>
            <h1>Settings</h1>

            <?php if (isset($_GET['success'])): ?>
                <p style="color: green;">✅ Informations mises à jour avec succès.</p>
            <?php endif; ?>

            <form method="POST" style="max-width: 500px;">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($userData['prénom']) ?>" required><br>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required><br>

                <label for="password">Nouveau mot de passe (laisser vide si inchangé) :</label>
                <input type="password" id="password" name="password"><br>

                <button type="submit">Enregistrer les modifications</button>
            </form>
        </main>
    </div>
</body>
</html>
