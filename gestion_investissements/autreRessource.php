<?php
include_once __DIR__ . '/auth/config.php';

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
        if ($typeRes !== '' && $carac !== '') {
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

            header("Location: autreRessource.php?id_investissement=$idInv");
            exit;
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
    <style>
        /* Logo size */
        .logo-invest, .logo-liste { width: 32px; height: 32px; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #f0f4f8, #ffffff);
            margin: 0; padding: 0; color: #333;
            animation: fadeIn 0.8s ease-in;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; }}
        .section-header {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            margin: 30px auto 10px;
            max-width: 800px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .section-container {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .small-form { max-width: 600px; margin: auto; }
        form label { font-weight: 600; display: block; margin-top: 15px; }
        form input, form select {
            width: 100%; padding: 10px; margin-top: 5px;
            border: 1px solid #ccc; border-radius: 8px;
            font-size: 15px;
        }
        form button {
            background: #1976d2; color: #fff; border: none;
            padding: 12px 20px; margin-top: 20px;
            border-radius: 8px; cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        form button:hover { transform: translateY(-2px); }
        .resource-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px,1fr)); gap: 1.5rem; }
        .resource-card {
            background: #fff; border-radius: 12px;
            padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .resource-card h3 { margin: 0 0 10px; color: #1976d2; }
        .resource-card p { margin: 6px 0; }
        .actions { display: flex; gap: 10px; margin-top: 15px; }
        .actions a {
            text-decoration: none; padding: 8px 12px;
            border-radius: 6px; font-weight: 600;
            transition: background 0.3s;
        }
        .edit-link { background: #0288d1; color: #fff; }
        .edit-link:hover { background: #0277bd; }
        .delete-link { background: #e53935; color: #fff; }
        .delete-link:hover { background: #c62828; }
        .button {
            display: inline-block; margin-top: 20px;
            background: #1976d2; color: #fff;
            padding: 10px 16px; border-radius: 8px;
            text-decoration: none; font-weight: 600;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .button:hover { transform: translateY(-2px); }
        /* Dark mode */
        .dark-mode body, .dark-mode .section-header, .dark-mode .section-container, .dark-mode .resource-card {
            background: #2c2c2c; color: #f0f0f0;
            box-shadow: 0 4px 15px rgba(255,255,255,0.05);
        }
        .dark-mode form input, .dark-mode form select { background: #3a3a3a; border: 1px solid #555; }
        .dark-mode .section-header { background: #333; }
        .dark-mode form button, .dark-mode .button { background: #0d47a1; }
        .dark-mode .edit-link { background: #039be5; }
        .dark-mode .delete-link { background: #ef5350; }
        #toggle-dark {
            position: fixed; top: 20px; right: 20px;
            background: #333; color: #fff;
            border: none; padding: 10px 15px;
            border-radius: 8px; cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        #toggle-dark:hover { background: #0d47a1; }
    </style>
</head>
<body>
<button id="toggle-dark">🌙 Mode Sombre</button>

<div class="section-header">
    <h1>Ressources - Investissement #<?= htmlspecialchars($idInv) ?></h1>
    <img src="logo-invest.png" alt="Logo Invest" class="logo-invest">
</div>

<div class="section-container small-form">
    <h2><?= $ressourceEdit ? 'Modifier' : 'Ajouter' ?> une ressource</h2>
    <form method="post">
        <?php if ($ressourceEdit): ?>
            <input type="hidden" name="id_ressource" value="<?= htmlspecialchars($ressourceEdit['id_ressource']) ?>">
        <?php endif; ?>

        <label for="type_ressource">Type de ressource :</label>
        <select id="type_ressource" name="type_ressource" required>
            <option value="voiture" <?= ($ressourceEdit && $ressourceEdit['type_ressource']==='voiture') ? 'selected' : '' ?>>Voiture</option>
            <option value="maison"  <?= ($ressourceEdit && $ressourceEdit['type_ressource']==='maison') ? 'selected' : '' ?>>Maison</option>
        </select>

        <label for="caracteristique">Caractéristique :</label>
        <input type="text" id="caracteristique" name="caracteristique"
               value="<?= $ressourceEdit ? htmlspecialchars($ressourceEdit['caracteristique']) : '' ?>"
               placeholder="Ex : Renault Clio, 2018" required>

        <button type="submit" name="<?= $ressourceEdit ? 'update' : 'add' ?>">
            <?= $ressourceEdit ? 'Modifier la ressource' : 'Ajouter la ressource' ?>
        </button>
    </form>
    <label for="phone">Téléphone :</label>
<input type="text" id="phone" name="phone" placeholder="Ex : 29xxxxxx" required>
<div id="errorPhone" style="color: red; margin-top:5px;"></div>

</div>


<div class="section-header">
    <h2>Liste des ressources</h2>
    <img src="logo-liste.png" alt="Logo Liste" class="logo-liste">
</div>
<div class="section-container">
    <?php if (count($ressources) > 0): ?>
        <div class="resource-list">
            <?php foreach ($ressources as $r): ?>
                <div class="resource-card">
                    <h3>Ressource #<?= htmlspecialchars($r['id_ressource']) ?></h3>
                    <p><strong>Type :</strong> <?= htmlspecialchars($r['type_ressource']) ?></p>
                    <p><strong>Détails :</strong> <?= htmlspecialchars($r['caracteristique']) ?></p>
                    <div class="actions">
                        <a class="edit-link" href="autreRessource.php?id_investissement=<?= $idInv ?>&edit=<?= $r['id_ressource'] ?>">✏️ Modifier</a>
                        <a class="delete-link" href="autreRessource.php?id_investissement=<?= $idInv ?>&delete=<?= $r['id_ressource'] ?>" onclick="return confirm('Supprimer cette ressource ?');">🗑 Supprimer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune ressource pour cet investissement.</p>
    <?php endif; ?>

    
    <div style="text-align: right; margin-bottom: 1rem;">
  <a href="generate_pdf_ressources.php?id_investissement=<?= $idInv ?>"
     class="button"
     target="_blank">
    📄 Exporter les ressources en PDF
  </a>
  <a class="button" href="index.php<?= $idInv ? '?edit='.$idInv : '' ?>">← Retour au formulaire principal</a>
    <div style="text-align: right; margin-bottom: 1rem;">
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


</body>
</html>
