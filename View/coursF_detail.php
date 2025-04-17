<?php
require_once '../config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du cours manquant !");
}

$id = $_GET['id'];

try {
    // Récupérer le cours
    $stmt = $pdo->prepare("SELECT * FROM cours WHERE id = ?");
    $stmt->execute([$id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        die("Cours introuvable !");
    }

    // Incrémenter les vues si "Commencer" cliqué
    if (isset($_POST['commencer'])) {
        $stmt = $pdo->prepare("UPDATE cours SET NbrVu = NbrVu + 1 WHERE id = ?");
        $stmt->execute([$id]);

        if (!empty($course['Exportation'])) {
            header("Location: " . $course['Exportation']);
            exit;
        }
    }

    // Mise à jour de la note
    if (isset($_POST['note']) && is_numeric($_POST['note'])) {
        $note = min(5, max(0, floatval($_POST['note'])));
        $stmt = $pdo->prepare("UPDATE cours SET Notes = ? WHERE id = ?");
        $stmt->execute([$note, $id]);
        $course['Notes'] = $note;
    }

    // Ajouter un commentaire
    if (isset($_POST['commentaire'])) {
        
        $contenu = htmlspecialchars($_POST['commentaire']);

        if (!empty($pseudo) && !empty($contenu)) {
            $stmt = $pdo->prepare("INSERT INTO commentaires (cours_id, commentaire) VALUES (?,  ?)");
            $stmt->execute([$id,  $ccommentaire]);
        }
    }

    // Récupérer les commentaires du cours
    $stmt = $pdo->prepare("SELECT * FROM commentaires WHERE cours_id = ? ORDER BY date DESC");
    $stmt->execute([$id]);
    $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail du Cours</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; }
        .container { max-width: 900px; margin: 20px auto; background: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); overflow: hidden; }
        .cover { width: 100%; height: 300px; object-fit: cover; }
        .content { padding: 30px; }
        h1 { font-size: 2rem; margin-bottom: 10px; }
        .description { margin: 20px 0; color: #555; }
        .info-line { margin: 10px 0; font-size: 1.1rem; }
        .stars { display: flex; margin-top: 10px; }
        .stars span {
            font-size: 30px; cursor: pointer; color: #ccc;
        }
        .stars span.selected { color: #f1c40f; }
        .btn {
            background-color: #27ae60; color: white;
            padding: 10px 20px; border: none; border-radius: 5px;
            cursor: pointer; font-size: 1rem;
        }
        .btn:hover { background-color: #1e8449; }

        .comment-section { margin-top: 40px; }
        .comment { border-bottom: 1px solid #ddd; padding: 15px 0; }
        .comment h4 { margin: 0; color: #2c3e50; }
        .comment small { color: #999; }
        .comment p { margin: 5px 0 0; }

        .comment-form textarea {
            width: 100%; padding: 10px; font-size: 1rem;
            border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px;
        }
        .comment-form input[type="text"] {
            width: 100%; padding: 10px; font-size: 1rem;
            border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="<?= htmlspecialchars($course['ImgCover']) ?>" alt="Image de couverture" class="cover">
        <div class="content">
            <h1><?= htmlspecialchars($course['Titre']) ?></h1>
            <div class="description"><?= nl2br(htmlspecialchars($course['Description'])) ?></div>

            <div class="info-line"><strong>Prix :</strong> <?= htmlspecialchars($course['Prix']) ?> DT</div>
            <div class="info-line"><strong>Nombre de vues :</strong> <?= htmlspecialchars($course['NbrVu']) ?></div>
            <div class="info-line"><strong>Note actuelle :</strong> <?= htmlspecialchars($course['Notes']) ?>/5</div>

            <form method="post" class="note-section">
                <label for="note">Attribuer une note :</label>
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star<?= ($i <= $course['Notes']) ? ' selected' : '' ?>" data-value="<?= $i ?>">&#9733;</span>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="note" id="note" value="<?= htmlspecialchars($course['Notes']) ?>">
                <button type="submit" class="btn">Noter</button>
            </form>

            <form method="post" style="margin-top: 30px;">
                <button type="submit" name="commencer" class="btn">Commencer le cours</button>
            </form>

            <div class="comment-section">
                <h2>Commentaires</h2>

                <?php foreach ($commentaires as $comment): ?>
                    <div class="comment">
                        <h4><?= htmlspecialchars($comment['pseudo']) ?> <small>(<?= $comment['date_commentaire'] ?>)</small></h4>
                        <p><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
                    </div>
                <?php endforeach; ?>

                <h3>Laisser un commentaire</h3>
                <form method="post" class="comment-form">
                    <input type="text" name="pseudo" placeholder="Votre nom" required>
                    <textarea name="contenu" rows="4" placeholder="Votre commentaire" required></textarea>
                    <button type="submit" class="btn">Envoyer</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const stars = document.querySelectorAll('.star');
        const noteInput = document.getElementById('note');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                noteInput.value = value;
                stars.forEach(s => s.classList.remove('selected'));
                for (let i = 0; i < value; i++) {
                    stars[i].classList.add('selected');
                }
            });
        });
    </script>
</body>
</html>
