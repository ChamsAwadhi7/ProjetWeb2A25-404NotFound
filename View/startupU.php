<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['utilisateur'];
$id_user = $user['id'];

$stmt = $pdo->prepare("SELECT * FROM startup WHERE utilisateur_id = ?");
$stmt->execute([$id_user]);
$startups = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Startups</title>
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
        }

        .card-content h3 {
            margin-top: 0;
            color: #34495e;
        }

        .info {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .info i {
            margin-right: 5px;
            color: #888;
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
    <a href="startupU.php"><i class="fas fa-lightbulb"></i> Startups</a>
    <a href="eventU.php"><i class="fas fa-calendar-alt"></i> Événements</a>
    <a href="#"><i class="fas fa-graduation-cap"></i> Formations</a>
    <a href="coursU.php"><i class="fas fa-book"></i> Cours</a>
    <a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
</div>

<div class="main">
    <h2><i class="fas fa-lightbulb"></i> Mes Startups</h2>

    <?php if (empty($startups)) : ?>
        <p>Aucune startup enregistrée.</p>
    <?php else : ?>
        <div class="grid">
            <?php foreach ($startups as $s) : ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($s['img_startup']) ?>" alt="Image de la startup">
                    <div class="card-content">
                        <h3><?= htmlspecialchars($s['nom_startup']) ?></h3>
                        <div class="info"><i class="fas fa-flag"></i> But : <?= htmlspecialchars($s['but_startup']) ?></div>
                        <div class="info"><i class="fas fa-info-circle"></i> Description : <?= htmlspecialchars($s['desc_startup']) ?></div>
                        <div class="info"><i class="fas fa-calendar"></i> Créée le : <?= htmlspecialchars($s['date_startup']) ?></div>
                        <div class="info"><i class="fas fa-bolt"></i> Nitro : <?= htmlspecialchars($s['nitro']) ?></div>
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
        </div>
    <?php endif; ?>
</div>

</body>
</html>
