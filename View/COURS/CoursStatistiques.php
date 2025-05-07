<?php
// === Configuration et Contrôleur ===
require_once '../config.php';
require_once '../Controller/CoursC.php';

$coursController = new CoursController();

// Récupérer les statistiques (moyenne des notes et total des vues)
$statistiques = $coursController->getStatistiques(); // À implémenter dans ton contrôleur
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Cours</title>
    <link rel="stylesheet" href="cours.css">
</head>
<body>
    <div class="container">
        <!-- === Sidebar === -->
        <!-- (Incorporer ton code de sidebar ici) -->

        <!-- === Main Content === -->
        <main>
            <h1>Statistiques des Cours</h1>
            <div class="statistiques">
                <h2>Résumé des Statistiques</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Cours</th>
                            <th>Moyenne des Notes</th>
                            <th>Total des Vues</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statistiques as $stat): ?>
                            <tr>
                                <td><?= htmlspecialchars($stat['Titre']) ?></td>
                                <td><?= number_format($stat['moyenne_notes'], 2) ?></td>
                                <td><?= $stat['total_vues'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
