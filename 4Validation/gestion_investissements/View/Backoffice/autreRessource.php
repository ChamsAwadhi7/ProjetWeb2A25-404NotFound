<?php
session_start();

require_once __DIR__ . '/../../auth/config.php';
// Génération d’un mot CAPTCHA si non déjà défini
if (!isset($_SESSION['captcha_word'])) {
    // 6 caractères choisis aléatoirement (sans voyelles pour limiter les vrais mots)
    $_SESSION['captcha_word'] = substr(
        str_shuffle('bcdfghjkmnpqrstvwxyz23456789'),
        0, 6
    );
}
$captcha_error = '';  // stockera le message d’erreur si besoin


$db = config::getConnexion();
$idInv = isset($_GET['id_investissement']) ? (int)$_GET['id_investissement'] : null;
$ressources = [];
$ressourceEdit = null;

// Suppression d'une ressource (pivot)
if (isset($_GET['delete']) && $idInv) {
    $idRes = (int)$_GET['delete'];
    $stmt = $db->prepare(
        "DELETE FROM investissement_ressource
         WHERE id_investissement = :inv AND id_ressource = :id"
    );
    $stmt->execute([':inv' => $idInv, ':id' => $idRes]);
    header("Location: autreRessource.php?id_investissement=$idInv");
    exit;
}

// Chargement pour modification
if (isset($_GET['edit']) && $idInv) {
    $idEdit = (int)$_GET['edit'];
    $stmt = $db->prepare(
        "SELECT r.id_ressource, r.type_ressource, r.caracteristique
          FROM ressource AS r
          JOIN investissement_ressource AS ir
            ON r.id_ressource = ir.id_ressource
         WHERE ir.id_investissement = :inv
           AND r.id_ressource       = :id"
    );
    $stmt->execute([':inv' => $idInv, ':id' => $idEdit]);
    $ressourceEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ajout ou mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $typeRes = trim($_POST['type_ressource']);
    $carac   = trim($_POST['caracteristique']);
// ─── Étape 3 : Récupérer la saisie CAPTCHA en POST ───
       $captchaInput = trim($_POST['captcha'] ?? '');

    // Mise à jour
    if (isset($_POST['update']) && $ressourceEdit) {
        $idRes = (int)$_POST['id_ressource'];
        if ($typeRes !== '' && $carac !== '') {
            $stmt = $db->prepare(
                "UPDATE ressource
                   SET type_ressource = :type,
                       caracteristique = :carac
                 WHERE id_ressource = :id"
            );
            $stmt->execute([
                ':type'  => $typeRes,
                ':carac' => $carac,
                ':id'    => $idRes
            ]);
            header("Location: autreRessource.php?id_investissement=$idInv");
            exit;
        }
    }
    // Ajout
    elseif (isset($_POST['add']) && $idInv) {
        if ($captchaInput === $_SESSION['captcha_word']) {
            // —> CAPTCHA correct : on insère
            // Insertion dans ressource
            $stmt = $db->prepare(
                "INSERT INTO ressource (type_ressource, caracteristique)
                 VALUES (:type, :carac)"
            );
            $stmt->execute([':type' => $typeRes, ':carac' => $carac]);
            $newId = $db->lastInsertId();
            // Liaison dans le pivot
            $stmt2 = $db->prepare(
                "INSERT INTO investissement_ressource (id_investissement, id_ressource)
                 VALUES (:inv, :res)"
            );
            $stmt2->execute([':inv' => $idInv, ':res' => $newId]);
            // On force la régénération à la prochaine page
            unset($_SESSION['captcha_word']);
            header("Location: autreRessource.php?id_investissement=$idInv");
            exit;
        } else {
            // —> CAPTCHA incorrect : on conserve la page et on affiche l’erreur
            $captcha_error = "Mot CAPTCHA incorrect. Veuillez réessayer.";
        }
    }
}

// Récupération des ressources liées
if ($idInv) {
    $stmt = $db->prepare(
        "SELECT r.id_ressource, r.type_ressource, r.caracteristique
          FROM ressource AS r
          JOIN investissement_ressource AS ir
            ON r.id_ressource = ir.id_ressource
         WHERE ir.id_investissement = :inv
         ORDER BY r.id_ressource DESC"
    );
    $stmt->execute([':inv' => $idInv]);
    $ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ressources - Investissement #<?= htmlspecialchars($idInv) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --text: #0f172a;
        }

        .dark-mode {
            --background: #0f172a;
            --card-bg: #1e293b;
            --text: #f8fafc;
        }

        * {
            box-sizing: border-box;
            transition: background-color 0.3s, color 0.1s;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            margin: 0;
            color: var(--text);
            min-height: 100vh;
        }

        .glass-container {
            background: rgba(var(--card-bg), 0.8);
            backdrop-filter: blur(12px);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .header {
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-weight: 700;
            font-size: 1.8rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .form-section {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text);
            opacity: 0.9;
        }

        .input-field {
            width: 100%;
            padding: 0.875rem 1.25rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 1rem;
            background: rgba(var(--card-bg), 0.5);
            color: var(--text);
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary), #7c3aed);
            color: white;
            padding: 0.875rem 1.75rem;
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .resource-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
        }

        .resource-card {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.1);
            transition: transform 0.3s;
        }

        .resource-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            border-bottom: 1px solid rgba(226, 232, 240, 0.1);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .edit-btn {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .delete-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        #toggle-dark {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--card-bg);
            border: none;
            padding: 1rem;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
        }

        .empty-state {
            text-align: center;
            padding: 4rem;
            opacity: 0.6;
        }
        .captcha-box {
  position: relative;         /* 1. indispensable pour le ::after */
  font-weight: bold;
  font-size: 1.25rem;
  letter-spacing: 4px;
  background: #e0e7ff;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  display: inline-block;
  user-select: none;
  margin-bottom: 0.5rem;
  /* tu peux aussi ajouter : text-decoration: line-through; 
     mais ici on préfère un trait contrôlé en épaisseur et angle */
}

.captcha-box::after {
  content: '';
  position: absolute;
  top: 50%;                   /* barre au milieu vertical */
  left: 8%;                   /* petit marge à gauche */
  width: 84%;                 /* longueur du trait */
  height: 3px;                /* épaisseur du trait */
  background: rgba(239,68,68,0.8); /* rouge semi-opaque */
  transform: rotate(-2deg);   /* légère inclinaison */
  pointer-events: none;       /* pour ne pas gêner la sélection */
}



        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.875rem;
            font-weight: 500;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }
    </style>
</head>
<body>
<button id="toggle-dark">🌓</button>

<div class="header">
    <h1>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="32">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        Ressources - Investissement #<?= htmlspecialchars($idInv) ?>
    </h1>
</div>

<div class="glass-container form-section">
    <h2 style="margin-bottom: 2rem; font-size: 1.5rem;"><?= $ressourceEdit ? 'Modifier' : 'Ajouter' ?> une ressource</h2>
    <form method="post">
        <?php if ($ressourceEdit): ?>
            <input type="hidden" name="id_ressource" value="<?= htmlspecialchars($ressourceEdit['id_ressource']) ?>">
        <?php endif; ?>

        <div class="input-group">
            <label class="input-label" for="type_ressource">Type de ressource</label>
            <select class="input-field" id="type_ressource" name="type_ressource" required>
                <option value="voiture" <?= ($ressourceEdit && $ressourceEdit['type_ressource']==='voiture') ? 'selected' : '' ?>>Voiture</option>
                <option value="maison"  <?= ($ressourceEdit && $ressourceEdit['type_ressource']==='maison') ? 'selected' : '' ?>>Maison</option>
            </select>
        </div>

        <div class="input-group">
            <label class="input-label" for="caracteristique">Caractéristiques</label>
            <input class="input-field" type="text" id="caracteristique" name="caracteristique"
                   value="<?= $ressourceEdit ? htmlspecialchars($ressourceEdit['caracteristique']) : '' ?>"
                   placeholder="Renault Clio, 2018" required>
        </div>

        <?php if ($captcha_error): ?>
            <div style="color:red; margin-bottom:1rem;">
    <?= htmlspecialchars($captcha_error) ?>
  </div>
<?php endif; ?>
<div class="input-group">
  <label class="input-label" for="captcha">Recopiez le mot ci-dessous :</label>
  <div class="captcha-box"><?= $_SESSION['captcha_word'] ?></div>
  <input
    class="input-field"
    type="text"
    id="captcha"
    name="captcha"
    placeholder="Entrez le mot ci-dessus"
    required
  >
</div>

  


        <div class="input-group">
            <label class="input-label" for="phone">Téléphone</label>
            <input class="input-field" type="tel" id="phone" name="phone" 
                   pattern="[0-9]{8}"
                   placeholder="29 00 00 00"
                   required>
            <div id="errorPhone" style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;"></div>
        </div>
        


        <button type="submit" name="<?= $ressourceEdit ? 'update' : 'add' ?>" class="btn-primary">
            <?= $ressourceEdit ? 'Modifier' : 'Ajouter' ?>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" width="20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
            </svg>
        </button>
    </form>
</div>

<div class="glass-container" style="margin: 2rem auto; max-width: 1200px;">
    <div style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="font-size: 1.5rem;">Liste des ressources</h2>
            <div style="display: flex; gap: 1rem;">
                <a href="generate_pdf_ressources.php?id_investissement=<?= $idInv ?>" class="btn-primary" target="_blank">
                    Exporter PDF
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" width="20">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                    </svg>
                </a>
                <a class="btn-primary" href="index.php<?= $idInv ? '?edit='.$idInv : '' ?>">
                    Retour
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" width="20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>

        <?php if (count($ressources) > 0): ?>
            <div class="resource-grid">
                <?php foreach ($ressources as $r): ?>
                    <div class="resource-card">
                        <div class="card-header">
                            <div class="badge">#<?= htmlspecialchars($r['id_ressource']) ?></div>
                        </div>
                        <p><strong>Type :</strong> <?= htmlspecialchars($r['type_ressource']) ?></p>
                        <p><strong>Détails :</strong> <?= htmlspecialchars($r['caracteristique']) ?></p>
                        <div class="card-actions">
                            <a href="autreRessource.php?id_investissement=<?= $idInv ?>&edit=<?= $r['id_ressource'] ?>" class="action-btn edit-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" width="16">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                                Modifier
                            </a>
                            <a href="autreRessource.php?id_investissement=<?= $idInv ?>&delete=<?= $r['id_ressource'] ?>" class="action-btn delete-btn" onclick="return confirm('Supprimer cette ressource ?');">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" width="16">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Supprimer
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p style="margin-top: 1rem;">Aucune ressource trouvée</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('toggle-dark');
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
    toggleBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  const caracInput = document.getElementById('caracteristique');

  form.addEventListener('submit', function(e) {
    const val = caracInput.value.trim();
    // Regex : au moins une minuscule, une majuscule et un chiffre
    const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
    if (!pattern.test(val)) {
      e.preventDefault();
      alert(
        'Le champ "Caractéristique" doit contenir au moins :\n' +
        '- une lettre minuscule\n' +
        '- une lettre majuscule\n' +
        '- un chiffre'
      );
      caracInput.focus();
    }
  });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form     = document.querySelector('form');
  const phoneIn  = document.getElementById('phone');
  const errPhone = document.getElementById('errorPhone');

  form.addEventListener('submit', function(e) {
    const val = phoneIn.value.trim();
    // Regex tunisienne : commence par 2,3,4,5 ou 9 + 7 chiffres
    const phonePattern = /^(?:2|3|4|5|9)\d{7}$/;

    if (!phonePattern.test(val)) {
      e.preventDefault();
      errPhone.textContent = 
        'Numéro invalide : 8 chiffres, commence par 2, 3, 4, 5 ou 9.';
      phoneIn.focus();
    } else {
      errPhone.textContent = ''; // tout est OK
    }
  });

  // Feedback en temps réel dès qu'on quitte le champ
  phoneIn.addEventListener('blur', function() {
    if (phoneIn.value.trim() !== '' && !phonePattern.test(phoneIn.value.trim())) {
      errPhone.textContent = 'Format invalide (ex : 29XXXXXX)';
    } else {
      errPhone.textContent = '';
    }
  });
});
</script>
<script>
    const toggleDark = document.getElementById('toggle-dark');
    const body = document.body;

    toggleDark.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        toggleDark.textContent = body.classList.contains('dark-mode') ? '☀️' : '🌓';
    });

</body>
</html>
