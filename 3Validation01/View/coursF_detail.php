<?php
require_once '../config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du cours manquant !");
}

$id = $_GET['id'];
$userId = $_GET['user'] ?? null; // On récupère l'id utilisateur s'il est passé

try {
    // Récupérer le cours
    $stmt = $pdo->prepare("SELECT * FROM cours WHERE id = ?");
    $stmt->execute([$id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        die("Cours introuvable !");
    }

    // Récupérer les chapitres
    $stmt = $pdo->prepare("SELECT * FROM contenucours WHERE cours_id = ?");
    $stmt->execute([$id]);
    $chapitres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier l'accès aux chapitres
    $accesAutorise = false;
    if ($userId) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM achetercours WHERE id_user = ? AND id_cours = ?");
        $stmt->execute([$userId, $id]);
        $accesAutorise = $stmt->fetchColumn() > 0;
    }


    //Idée Génerel 
    if (isset($_POST['Idée générale'])) {
        if (!empty($course['Exportation'])) {
            header("Location: " . $course['Exportation']);
            exit;
        }
        
    }



    // Récupérer les commentaires
    $stmt = $pdo->prepare("SELECT * FROM commentaires WHERE cours_id = ? ORDER BY date DESC");
    $stmt->execute([$id]);
    $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erreur PDO : " . $e->getMessage());
    die("Une erreur est survenue. Veuillez réessayer plus tard.");
}


// Ajouter un commentaire avec une réaction
if (isset($_POST['pseudo']) && isset($_POST['contenu'])) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $contenu = htmlspecialchars($_POST['contenu']);
    $reaction = isset($_POST['reaction']) ? htmlspecialchars($_POST['reaction']) : null;

    if (!empty($pseudo) && !empty($contenu)) {
        $stmt = $pdo->prepare("INSERT INTO commentaires (cours_id, idUser, commentaire, reaction) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id, $pseudo, $contenu, $reaction]);
        header("Location: coursF_detail.php?id=" . $id);
        exit;
    }
}


// Suppression d'un commentaire
if (isset($_GET['delete_comment']) && is_numeric($_GET['delete_comment'])) {
    $comment_id = intval($_GET['delete_comment']);
    $stmt = $pdo->prepare("DELETE FROM commentaires WHERE id = ?");
    $stmt->execute([$comment_id]);
    header("Location: coursF_detail.php?id=" . $id);
    exit;
}

// Vérifier si l'édition d'un commentaire est demandée
if (isset($_GET['edit_comment']) && is_numeric($_GET['edit_comment'])) {
    $comment_id = intval($_GET['edit_comment']);
    $stmt = $pdo->prepare("SELECT * FROM commentaires WHERE id = ?");
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment) {
        die("Commentaire introuvable !");
    }

    // Si le formulaire d'édition est soumis
    if (isset($_POST['edit_contenu']) && !empty($_POST['edit_contenu'])) {
        $edit_contenu = htmlspecialchars($_POST['edit_contenu']);
        $stmt = $pdo->prepare("UPDATE commentaires SET commentaire = ? WHERE id = ?");
        $stmt->execute([$edit_contenu, $comment_id]);
        header("Location: coursF_detail.php?id=" . $id); // Redirige après l'édition
        exit;
    }
}

// Nombre de vues réelles depuis la table achetercours
$stmt = $pdo->prepare("SELECT COUNT(*) FROM achetercours WHERE id_cours = ?");
$stmt->execute([$id]);
$nbVues = $stmt->fetchColumn();

// Mise à jour de la note
if (isset($_POST['note']) && is_numeric($_POST['note'])) {
    $note = min(5, max(0, floatval($_POST['note'])));
    $stmt = $pdo->prepare("UPDATE cours SET Notes = ? WHERE id = ?");
    $stmt->execute([$note, $id]);
    $course['Notes'] = $note;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail du Cours</title>
    <link rel="stylesheet" href="coursF_detail.css">
</head>
<body>

<a href="coursF.php" class="btn-retour">← Retour à la liste des cours</a>

<div class="container">
    <img src="<?= htmlspecialchars($course['ImgCover']) ?>" alt="Image de couverture" class="cover">
    <div class="content">
        <h1><?= htmlspecialchars($course['Titre']) ?></h1>
        <div class="description"><?= nl2br(htmlspecialchars($course['Description'])) ?></div>

        <div class="info-line"><strong>Prix :</strong> <?= htmlspecialchars($course['Prix']) ?> DT</div>
        <div class="info-line"><strong>Nombre de vues :</strong> <?= (int)$nbVues ?> utilisateurs ont acheté ce cours</div>
        <div class="info-line"><strong>Note actuelle :</strong> <?= htmlspecialchars($course['Notes']) ?>/5</div>

         <!-- note-section -->
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


        <br><br>
        <button type="submit" name="Idée générale" class="btn">Idée générale</button>
        <button type="button" class="btn" onclick="commencerCours(<?= (int)$course['id']; ?>, <?= (float)$course['Prix']; ?>)">Acheter et Commencer</button>

        <!-- Affichage conditionnel du contenu du cours -->
        <?php if ($accesAutorise && !empty($chapitres)): ?>
            <div class="chapitres">
                <h2>Contenu du cours</h2>
                <?php foreach ($chapitres as $chapitre): ?>
                    <div class="chapitre">
                        <label>
                            <?= htmlspecialchars($chapitre['nomChapitre']) ?> (<?= htmlspecialchars($chapitre['typeContenu']) ?>)
                        </label>
                        <a href="<?= htmlspecialchars($chapitre['fichierPath']) ?>" target="_blank" class="lien-contenu">Voir le contenu</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($userId): ?>
            <p style="color:red;">⛔ Vous devez acheter ce cours pour accéder au contenu.</p>
        <?php endif; ?>

        
         <!-- Formulaire pour ajouter un commentaire -->
        <div id="comment-react"></div>
            <h3>Ajouter un commentaire :</h3>
            <form method="post" class="comment-form" id="commentForm">
                <input type="text" name="pseudo" id="pseudo" placeholder="Votre ID utilisateur" required>
                <textarea name="contenu" id="contenu" rows="4" placeholder="Votre commentaire" required></textarea>
                <button type="submit" class="btn">Envoyer</button>
            </form>

            <!-- Liste des commentaires -->
            <h3>Commentaires :</h3>
            <?php if (!empty($commentaires)): ?>
    
    <?php foreach ($commentaires as $comment): ?>
        <div class="comment">
            <h4>ID Utilisateur : <?= htmlspecialchars($comment['idUser']) ?> 
                <small>(<?= htmlspecialchars($comment['date']) ?>)</small>
            </h4>
            <p><?= nl2br(htmlspecialchars($comment['commentaire'])) ?></p>

            <!-- Lien pour supprimer un commentaire -->
            <a href="?id=<?= $id ?>&delete_comment=<?= $comment['id'] ?>" 
               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');" 
               style="color: red; font-size: 0.9rem;">Supprimer</a>

            <!-- Lien pour éditer un commentaire -->
            <a href="?id=<?= $id ?>&edit_comment=<?= $comment['id'] ?>" 
               style="color: blue; font-size: 0.9rem;">Éditer</a>
            <!-- Conteneur React unique par commentaire -->
            <div id="comment-react-app-<?= $comment['id'] ?>" class="comment-react"></div>
        </div>

        <!-- Formulaire d'édition du commentaire -->
        <?php if (isset($_GET['edit_comment']) && $_GET['edit_comment'] == $comment['id']): ?>
            <form method="post" class="comment-form">
                <textarea name="edit_contenu" rows="4" required><?= htmlspecialchars($comment['commentaire']) ?></textarea>
                <button type="submit" class="btn">Mettre à jour</button>
            </form>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun commentaire pour ce cours.</p>
<?php endif; ?>

        </div>

    </div>
</div>

    </div>
</div>



<script type="text/babel" src="coursF_detail.js"></script>
<script>
function commencerCours(coursId, prixCours) {
    const userId = prompt("Veuillez entrer votre ID utilisateur :");
    if (!userId) return alert("ID requis !");

    // Étape 1 : Vérifier si le cours a déjà été acheté
    fetch(`verifier_achat.php?id_user=${userId}&id_cours=${coursId}`)
    .then(res => res.json())
    .then(data => {
        if (data.dejaAchete) {
            alert("✅ Vous avez déjà acheté ce cours !");
            window.location.href = "coursF_detail.php?id=" + coursId + "&user=" + userId;
        } else {
            // Étape 2 : Vérifier solde et enregistrer achat
            const formData = new FormData();
                formData.append('id_user', userId);
                formData.append('coursId', coursId);
                formData.append('prixCours', prixCours);

                fetch('verifier_finance.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert("✅ Achat réussi !\nSolde actuel: " + data.soldeActuel + "\nNouveau solde: " + data.nouveauSolde);
                        window.location.href = "contenu_cours.php?id=" + coursId + "&user=" + userId;
            
                    } else {
                        alert("❌ " + data.message + "\nSolde actuel: " + data.soldeActuel);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert("❌ Erreur de connexion au serveur.");
                });
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert("❌ Erreur lors de la vérification d'achat.");
        });
}
        
</script>

</body>
</html>
