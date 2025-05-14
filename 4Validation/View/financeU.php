<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['utilisateur'];

// Récupérer les données financières de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM finance WHERE id_user = ?");
$stmt->execute([$user['id']]);
$finance = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Finance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <h2><i class="fas fa-chart-line"></i> Informations Financières</h2>
        <?php if ($finance): ?>
        <table>
            <tr><td class="label">ID Finance</td><td><?= htmlspecialchars($finance['id_finance']) ?></td></tr>
            <tr><td class="label">Numéro de Carte</td><td><?= htmlspecialchars($finance['num_carte']) ?></td></tr>
            <tr><td class="label">Balance</td><td><?= htmlspecialchars($finance['balance']) ?> dt</td></tr>
            <tr><td class="label">Pays</td><td><?= htmlspecialchars($finance['pays']) ?></td></tr>
            <tr><td class="label">Banque</td><td><?= htmlspecialchars($finance['nom_bank']) ?></td></tr>
        </table>
        <?php else: ?>
            <p class="no-data">Aucune information financière trouvée.</p>
        <?php endif; ?>
        <div style="margin-top: 30px;">
        <a href="index.php" style="
            display: inline-block;
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s;
        ">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>
        <div style="margin-top: 20px;">
    <a href="FINANCE/financeF.php" style="
        display: inline-block;
        padding: 12px 20px;
        background-color: #27ae60;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
        transition: background 0.3s;
        margin-right: 10px;
    ">
        <i class="fas fa-pen"></i> Gérer mes finances
    </a>
</div>

    </div>
    </div>
    
</div>

</body>
</html>
