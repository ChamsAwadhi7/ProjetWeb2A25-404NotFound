<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header('Location: login_register.php');
    exit;
}

$user = $_SESSION['utilisateur'];
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
$stmt->execute([$user['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Utilisateur introuvable.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #2c3e50;
            color: white;
            padding-top: 30px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
            font-weight: normal;
        }

        .sidebar a {
            padding: 15px 20px;
            color: #ecf0f1;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        /* Main Content */
        .main {
            margin-left: 220px;
            padding: 40px;
            width: 100%;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
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
            color: #555;
            background-color: #f9f9f9;
            width: 35%;
        }

        .icon {
            margin-right: 8px;
            color: #777;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .container {
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
        <div class="container">
            <h2><i class="fas fa-user-circle"></i> Profil de <?= htmlspecialchars($user['prénom']) . " " . htmlspecialchars($user['nom']) ?></h2>
            <table>
                <tr><td class="label"><i class="fas fa-id-badge icon"></i>ID</td><td><?= htmlspecialchars($user['id']) ?></td></tr>
                <tr><td class="label"><i class="fas fa-user icon"></i>Nom</td><td><?= htmlspecialchars($user['nom']) ?></td></tr>
                <tr><td class="label"><i class="fas fa-user icon"></i>Prénom</td><td><?= htmlspecialchars($user['prénom']) ?></td></tr>
                <tr><td class="label"><i class="fas fa-envelope icon"></i>Email</td><td><?= htmlspecialchars($user['email']) ?></td></tr>
                <tr><td class="label"><i class="fas fa-key icon"></i>Mot de passe</td><td>********</td></tr>
                <tr><td class="label"><i class="fas fa-user-tag icon"></i>Rôle</td><td><?= htmlspecialchars($user['role']) ?></td></tr>
                <tr><td class="label"><i class="fas fa-calendar-alt icon"></i>Date d'inscription</td><td><?= htmlspecialchars($user['date_inscription']) ?></td></tr>
            </table>
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
            <i class="fas fa-arrow-left"></i> Return to home 
        </a>
    </div>
            
        </div>
    </div>
    

</body>
</html>
