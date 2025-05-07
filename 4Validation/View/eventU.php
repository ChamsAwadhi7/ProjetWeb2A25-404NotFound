<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['utilisateur']['id'];

// Récupérer les événements liés à cet utilisateur
$stmt = $pdo->prepare("
    SELECT e.*
    FROM rejoindre r
    JOIN events e ON r.id_event = e.id_event
    WHERE r.id_user = ?
    ORDER BY e.date_event DESC
");
$stmt->execute([$user_id]);
$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Événements</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 220px;
            background: #2c3e50;
            color: white;
            padding-top: 30px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
        }

        .sidebar a {
            display: block;
            padding: 15px 20px;
            color: #ecf0f1;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #34495e;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .main {
            margin-left: 220px;
            padding: 40px;
        }

        h2 {
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card-content {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-content h3 {
            margin: 0 0 10px;
            color: #34495e;
        }

        .info {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }

        .info i {
            color: #888;
            margin-right: 5px;
        }

        .export-btn {
            margin-top: auto;
            background: #27ae60;
            color: white;
            padding: 10px 15px;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
            font-weight: bold;
        }

        .export-btn:hover {
            background: #219150;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="sidebar">
        <h3>My Informations</h3>
        <a href="profile.php"><i class="fas fa-user"></i> profile</a>
        <a href="financeU.php"><i class="fas fa-chart-line"></i> Finance</a>
        <a href="#"><i class="fas fa-lightbulb"></i> Startups</a>
        <a href="eventU.php"><i class="fas fa-calendar-alt"></i> Événements</a>
        <a href="#"><i class="fas fa-graduation-cap"></i> Formations</a>
        <a href="coursU.php"><i class="fas fa-book"></i> Cours</a>
        <a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
    </div>
<div class="main">
<h1><i class="fas fa-calendar-alt"></i> Mes Événements</h1>

<div class="grid">
    <?php if (empty($evenements)): ?>
        <p style="grid-column: 1 / -1; text-align: center;">Aucun événement trouvé.</p>
    <?php else: ?>
        <?php foreach ($evenements as $event): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($event['img_event']) ?>" alt="Image de l'événement">
                <div class="card-content">
                    <h3><?= htmlspecialchars($event['nom_event']) ?></h3>
                    <p class="date"><i class="fas fa-clock"></i> <?= htmlspecialchars($event['date_event']) ?></p>
                    <p class="lieu"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['lieu']) ?></p>
                    <p><?= nl2br(htmlspecialchars($event['desc_event'])) ?></p>
                </div>
            </div>
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
    </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</div>
    
</body>
</html>
