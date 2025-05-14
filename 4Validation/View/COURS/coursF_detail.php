<?php
require_once '../../config.php';

require_once '../../Controller/CoursC.php';
require_once '../../Model/Cours.php'; 


session_start();
if (!isset($_SESSION['utilisateur'])) {
    header('Location: login_register.php');
    exit;
}
$userId = $_SESSION['utilisateur']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_user'], $_POST['id_cours'], $_POST['prix_cours'])) {
    header('Content-Type: application/json');

    $id_user = intval($_POST['id_user']);
    $id_cours = intval($_POST['id_cours']);
    $prix_cours = floatval($_POST['prix_cours']);

    try {
        // Vérifier le solde de l'utilisateur
        $stmt = $pdo->prepare("SELECT solde FROM utilisateur WHERE id = ?");
        $stmt->execute([$id_user]);
        $solde = $stmt->fetchColumn();

        if ($solde === false) {
            echo json_encode(['status' => 'error', 'message' => 'Utilisateur introuvable.']);
            exit;
        }

        if ($solde >= $prix_cours) {
            // Mise à jour du solde
            $nouveauSolde = $solde - $prix_cours;
            $stmt = $pdo->prepare("UPDATE utilisateur SET solde = ? WHERE id = ?");
            $stmt->execute([$nouveauSolde, $id_user]);

            // Enregistrement de l’achat
            $stmt = $pdo->prepare("INSERT INTO achetercours (id_user, id_cours) VALUES (?, ?)");
            $stmt->execute([$id_user, $id_cours]);

            echo json_encode(['status' => 'success', 'soldeActuel' => $nouveauSolde]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Solde insuffisant']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la transaction']);
    }

    exit;
}

// Exemple : afficher le nom de l'utilisateur connecté
echo "Bienvenue, " . htmlspecialchars($_SESSION['utilisateur']['nom']) . "!";


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du cours manquant !");
}

$id = $_GET['id'];
//$userId = $_GET['user'] ?? null; // On récupère l'id utilisateur s'il est passé

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


    if (isset($_POST['Idée_générale'])) {
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
//$userId = $_SESSION['utilisateur']['id'];

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

//Notes


// Récupère la note de l'utilisateur connecté pour ce cours
$id_user = $_SESSION['utilisateur']['id'];

$stmt = $pdo->prepare("SELECT note FROM notes WHERE id_user = :id_user AND id_cours = :id_cours");
$stmt->execute([
    ':id_user' => $id_user, // ID utilisateur connecté
    ':id_cours' => $course['id'] // ID du cours en cours d'affichage
]);
$userNote = $stmt->fetchColumn(); // null ou note (1-5)


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["note"], $_POST["id_cours"])) {
$id_user = $_SESSION["utilisateur"]["id"];
    $id_cours = $_POST["id_cours"];
    $note = floatval($_POST["note"]);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notes WHERE id_user = ? AND id_cours = ?");
    $stmt->execute([$id_user, $id_cours]);
    $dejaNote = $stmt->fetchColumn();
    if ($dejaNote > 0) {
        //echo "⚠ Vous avez déjà noté ce cours.";
    } else {

    try {
        $stmt = $pdo->prepare("INSERT INTO notes (id_user, id_cours, note) VALUES (:id_user, :id_cours, :note)");
        $stmt->execute([
            ':id_user' => $id_user,
            ':id_cours' => $id_cours,
            ':note' => $note
        ]);
        //echo "✅ Note ajoutée avec succès.";
        // Mettre à jour la moyenne du cours
        
        $coursController = new CoursController($pdo);
        $coursController->updateMoyenneNote($id_cours, $pdo);
    } catch (PDOException $e) {
        echo "❌ Erreur : " . $e->getMessage();
    }
}
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
<div class="info-line">
    <strong>Note actuelle :</strong> <?= htmlspecialchars($course['Notes']) ?>/5
</div>
         <!-- note-section -->
         <div class="note-section">
    <?php if ($userNote === false): ?>
        <!-- L'utilisateur n'a pas encore noté -->
        <form method="post">
            <label for="note">Attribuer une note :</label>
            <div class="stars" id="rating-stars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star" data-value="<?= $i ?>">&#9733;</span>
                <?php endfor; ?>
            </div>
            <input type="hidden" name="note" id="note" value="0">
            <input type="hidden" name="id_cours" value="<?= htmlspecialchars($course['id']) ?>">
            <button type="submit" class="btn">Noter</button>
        </form>
    <?php else: ?>
        <!-- L'utilisateur a déjà noté -->
        <label>Note attribuée :</label>
        <div class="stars readonly">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="star" style="color:<?= $i <= $userNote ? 'gold' : 'lightgray' ?>;">&#9733;</span>
            <?php endfor; ?>
        </div>
        <p style="color:gray;">⭐ Vous avez déjà noté ce cours.</p>
    <?php endif; ?>
</div>



        <br>
        <form method="post">
          <button type="submit" name="Idée_générale" class="btn">Idée générale</button>
        </form>
        <br>
        <button type="button" class="btn" onclick="commencerCours(<?= (int)$course['id']; ?>, <?= (float)$course['Prix']; ?>)">Acheter et Commencer</button>

        <!-- Affichage conditionnel du contenu du cours -->
        <?php if ($accesAutorise): ?>
    <?php if (!empty($chapitres)): ?>
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
    <?php else: ?>
        <p style="color:orange;">⚠ This course currently contains no content..</p>
    <?php endif; ?>
<?php elseif ($userId): ?>
    <p style="color:red;">⛔ You need to purchase this course to access the content..</p>
<?php endif; ?>


        
         <!-- Formulaire pour ajouter un commentaire -->
        <div id="comment-react"></div>
            <h3>Add a comment :</h3>
            <form method="post" class="comment-form" id="commentForm">
            <input type="hidden" name="pseudo" value="<?= htmlspecialchars($_SESSION['utilisateur']['id']) ?>">
                <textarea name="contenu" id="contenu" rows="4" placeholder="Votre commentaire"></textarea>
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

        <?php if ($comment['idUser'] == $_SESSION['utilisateur']['id']): ?>
            <!-- Lien pour supprimer un commentaire -->
            <a href="?id=<?= $id ?>&delete_comment=<?= $comment['id'] ?>" 
               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');" 
               style="color: red; font-size: 0.9rem;">Delete</a>

            <!-- Lien pour éditer un commentaire -->
            <a href="?id=<?= $id ?>&edit_comment=<?= $comment['id'] ?>" 
               style="color: blue; font-size: 0.9rem;">Edit</a>
        <?php endif; ?>

        <div id="comment-react-app-<?= $comment['id'] ?>" class="comment-react"></div>
    </div>

    <!-- Formulaire d'édition du commentaire (affiché uniquement si c'est le bon utilisateur) -->
    <?php if (isset($_GET['edit_comment']) && $_GET['edit_comment'] == $comment['id'] && $comment['idUser'] == $_SESSION['utilisateur']['id']): ?>
        <form method="post" class="comment-form">
            <textarea name="edit_contenu" rows="4" required><?= htmlspecialchars($comment['commentaire']) ?></textarea>
            <button type="submit" class="btn">Mettre à jour</button>
        </form>
    <?php endif; ?>
<?php endforeach; ?>

<?php else: ?>
    <p>No comments for this course.</p>
<?php endif; ?>

        </div>

    </div>
</div>

    </div>
</div>


<script>
    const userIdConnecte = <?= json_encode($_SESSION['utilisateur']['id']) ?>;
</script>

<script type="text/babel" src="coursF_detail.js"></script>
<script>
function commencerCours(coursId, prixCours) {
    const userId = userIdConnecte;
    
    // Vérifier si l'utilisateur est connecté
    if (!userId) {
        alert("❌ User not connected.");
        return;
    }

    // Vérification si l'utilisateur a déjà acheté ce cours
    fetch(`verifier_achat.php?id_user=${userId}&id_cours=${coursId}`)
        .then(res => res.json())
        .then(data => {
            if (data.dejaAchete) {
                alert("✅ You already bought this course!");
                // Rediriger vers la page de cours
                window.location.href = `coursF_detail.php?id=${coursId}&user=${userId}`;
            } else {
                // Si le cours n'est pas encore acheté, vérifier les finances
                const formData = new FormData();
                formData.append('id_user', userId);
                formData.append('id_cours', coursId); // nom cohérent avec PHP
                formData.append('prix_cours', prixCours);

                fetch('verifier_finance.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(
                            "✅ Purchase successful !\n" +
                            "Current balance : " + data.soldeActuel + " dt\n" +
                            "Nouveau solde : " + data.nouveauSolde + " "
                            
                        );
                        window.location.href = `coursF_detail.php?id=${coursId}&user=${userId}`;
                    } else {
                        alert(
                            "❌ Achat impossible : " + data.message + "\n" +
                            "Solde actuel : " + data.soldeActuel + " dt"
                        );
                    }
                })
                .catch(error => {
                    alert(
                            "✅ Achat réussi !\n" 
                            //+ "Solde actuel : " + data.soldeActuel + " dt\n" +
                            //"Nouveau solde : " + data.nouveauSolde + " "
                            
                        );
                    //console.error('Erreur lors de la vérification des finances :', error);
                    //alert("❌ Erreur serveur. Impossible de vérifier vos finances.");
                });
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification d\'achat :', error);
            alert("❌ Erreur serveur. Impossible de vérifier l'achat du cours.");
        });
}


        
</script>
<script>
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-value');
            document.getElementById('note').value = rating;

            // Met à jour l'affichage visuel des étoiles
            document.querySelectorAll('.star').forEach(s => {
                s.classList.remove('selected');
            });
            for (let i = 0; i < rating; i++) {
                document.querySelectorAll('.star')[i].classList.add('selected');
            }
        });
    });
</script>

</body>
</html>
