<?php
require_once '../config.php';

try {
    $stmt = $pdo->query("SELECT * FROM cours ORDER BY DateAjout DESC");
    $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de récupération des cours : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cours disponibles</title>
    <link rel="stylesheet" href="coursF.css">
</head>
<body>

<h1>Catalogue des Cours</h1>

<div class="grid">
    <?php foreach ($cours as $c): ?>
        <div class="card">
            <img src="<?= htmlspecialchars($c['ImgCover']) ?>" alt="Couverture du cours">
            <div class="content">
                <div class="title"><?= htmlspecialchars($c['Titre']) ?></div>
                <div class="price"><?= number_format($c['Prix'], 2) ?> DT</div>
                <div class="description"><?= substr(htmlspecialchars($c['Description']), 0, 80) ?>...</div>
                <p class="note">
                    <?php
                        $fullStars = floor($c['Notes']);
                        $halfStar = ($c['Notes'] - $fullStars >= 0.5);
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                        for ($i = 0; $i < $fullStars; $i++) echo '★';
                        if ($halfStar) echo '☆';
                        for ($i = 0; $i < $emptyStars; $i++) echo '✩';
                    ?>
                    <span>(<?= number_format($c['Notes'], 1) ?>/5)</span>
                </p>
                <a href="coursF_detail.php?id=<?= $c['id'] ?>" class="btn">Voir détails</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
