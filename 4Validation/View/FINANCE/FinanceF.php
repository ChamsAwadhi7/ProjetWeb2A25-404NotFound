<?php
require_once '../../config.php';
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['utilisateur'];
$id_user = $user['id'];

// Récupérer les données existantes
$stmt = $pdo->prepare("SELECT * FROM finance WHERE id_user = ?");
$stmt->execute([$id_user]);
$finance = $stmt->fetch(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $num_carte = $_POST['num_carte'];
    $balance = $_POST['balance'];
    $pays = $_POST['pays'];
    $nom_bank = $_POST['nom_bank'];

    if ($finance) {
        // Mise à jour
        $update = $pdo->prepare("UPDATE finance SET num_carte = ?, pays = ?, nom_bank = ? WHERE id_user = ?");
        $update->execute([$num_carte, $pays, $nom_bank, $id_user]);
    } else {
        // Insertion
        $insert = $pdo->prepare("INSERT INTO finance (id_user, num_carte, balance, pays, nom_bank) VALUES (?, ?, ?, ?, ?)");
        $insert->execute([$id_user, $num_carte, $balance, $pays, $nom_bank]);
    }

    header("Location: ../financeU.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer Mes Finances</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            background: #fff;
            padding: 30px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        form input, form select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            background-color: #27ae60;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #219150;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }
    </style>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            background: #2c3e50;
            color: white;
            padding-top: 30px;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            bottom: 0;
            margin-left: -40px;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
        }

        .sidebar a {
            padding: 15px 20px;
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .main {
            margin-left: 220px;
            padding: 40px;
            width: 100%;
        }

        .container {
            max-width: 600px;
            background: #fff;
            padding: 30px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 12px 18px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 16px;
        }

        td.label {
            font-weight: 600;
            background-color: #f9f9f9;
            width: 40%;
        }

        .no-data {
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
<div class="sidebar">
        <h3>My Informations</h3>
        <a href="profile.php"><i class="fas fa-user"></i> profile</a>
        <a href="financeU.php"><i class="fas fa-chart-line"></i> Finance</a>
        <a href="startupU.php"><i class="fas fa-lightbulb"></i> Startups</a>
        <a href="eventU.php"><i class="fas fa-calendar-alt"></i> Événements</a>
        <a href="#"><i class="fas fa-graduation-cap"></i> Formations</a>
        <a href="coursU.php"><i class="fas fa-book"></i> Cours</a>
        <a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
    </div>

<div class="main">

<div class="container">

    <h2><i class="fas fa-pen"></i> <?= $finance ? "Modifier" : "Ajouter" ?> mes informations financières</h2>
    <form method="POST">
    <label for="num_carte">Numéro de carte :</label>
    <input type="text" id="num_carte" name="num_carte" placeholder="Numéro de carte" required value="<?= htmlspecialchars($finance['num_carte'] ?? '') ?>">

    <p><strong>Solde actuel :</strong> <?= htmlspecialchars($finance['balance'] ) ?> DT</p>
    <a href="DemandeF.php" class="ajout-solde"><i class="fas fa-plus-circle"></i> Ajouter solde</a>

    <label for="pays">Pays :</label>
    <input type="text" id="pays" name="pays" placeholder="Pays" required value="<?= htmlspecialchars($finance['pays'] ?? '') ?>">

    <label for="nom_bank">Nom de la banque :</label>
    <input type="text" id="nom_bank" name="nom_bank" placeholder="Nom de la banque" required value="<?= htmlspecialchars($finance['nom_bank'] ?? '') ?>">

    <button type="submit"><i class="fas fa-save"></i> Enregistrer</button>
</form>

    <a href="../financeU.php"><i class="fas fa-arrow-left"></i> Retour</a>
</div>
</div>



</body>
</html>
