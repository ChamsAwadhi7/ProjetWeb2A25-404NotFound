<?php
session_start();
include_once __DIR__ . "/Investissement.php";
include_once __DIR__ . "/InvestissementC.php";
require_once __DIR__ . '/utils/flash.php';


$investC = new InvestissementC();
$comingDue = $investC->getInvestissementsProchesEcheance(15);

$erreur = "";

// Récupérer les paramètres GET
$searchChamp = $_GET['search_champ'] ?? '';
$searchVal   = $_GET['search_val']   ?? '';
$sort        = $_GET['sort']         ?? '';

// Si on a un critère de recherche, on l’applique en priorité
if (!empty($searchChamp) && !empty($searchVal)) {
    $listeInvestissements = $investC->rechercherInvestissements($searchChamp, $searchVal);

} else {
    // ───── Bloc de tri ─────
    switch ($sort) {
        case 'montant_asc':
            $listeInvestissements = $investC->trierParMontantCroissant();
            break;
        case 'montant_desc':
            $listeInvestissements = $investC->trierParMontantDecroissant();
            break;
        case 'date_asc':
            $listeInvestissements = $investC->trierParDateCroissante();
            break;
        case 'date_desc':
            $listeInvestissements = $investC->trierParDateDecroissante();
            break;
        default:
            $listeInvestissements = $investC->listInvestissements();
    }
    // ───────────────────────
}




if (isset($_POST['submit'])) {
    // 2.a) Récupération et nettoyage des inputs
    $user_id     = (int)   ($_POST['user_id']      ?? 0);
    $montant_dt  = (float) (trim($_POST['montant_dt'] ?? '0'));
    $date        =          ($_POST['date']          ?? date('Y-m-d'));
    $date_fin    =          ($_POST['date_fin']      ?? null);
    $id_startups = (int)   ($_POST['id_startups']   ?? 0);

    // Type & ressource
    $type = $_POST['type'] ?? 'carte';
    $ressourceData = null;
    if ($type === 'autre') {
        $ressourceData = [
            'type_ressource'  => $_POST['type_ressource']  ?? '',
            'caracteristique' => trim($_POST['caracteristique'] ?? '')
        ];
    }

    // 2.b) Validation basique
    if ($montant_dt < 100) {
        $erreur = "Le montant doit être ≥ 100 DT.";
    } elseif ((int) date('Y', strtotime($date)) < date('Y') - 2) {
        $erreur = "La date de début ne peut pas être antérieure à " . (date('Y') - 2) . ".";
    } else {
        // 2.c) Conversion DT → EUR (taux fixe)
        $taux = 3.4; // 1 EUR = 3.4 DT
        $montant_eur = round($montant_dt / $taux, 2);

        // 2.d) Appel au contrôleur (sans SMS)
        $newId = $investC->addInvestissement(
            $user_id,
            $montant_eur,
            $date,
            $id_startups,
            $date_fin,
            $type,
            $ressourceData
        );

        if ($newId !== null) {
            setFlash('success', "Investissement #{$newId} ajouté avec succès !");
            header("Location: index.php");
            exit();
        } else {
            setFlash('danger', "Erreur lors de l’ajout de l’investissement.");
        }
        
    }
}


// Traitement de la modification
if (isset($_POST['update'])) {
    $id           = trim($_POST['id_investissement']);
    $montant_dt   = trim($_POST['montant_dt']);
    $date         = trim($_POST['date']);
    $date_fin     = trim($_POST['date_fin']);
    $id_startups  = trim($_POST['id_startups']);

    // **NOUVEAU** récupération du type et de la ressource
    $type = $_POST['type'];
    $ressourceData = null;
    if ($type === 'autre') {
        $ressourceData = [
            'type_ressource'  => $_POST['type_ressource'],
            'caracteristique' => trim($_POST['caracteristique'])
        ];
    }

    if (!is_numeric($montant_dt) || $montant_dt < 100) {
        $erreur = "Le montant doit être un nombre en DT et ≥ 100 DT.";
    } elseif ((int)date('Y', strtotime($date)) < (int)date('Y') - 2) {
        $erreur = "La date de début est trop ancienne. Elle ne peut pas être avant " . (date('Y') - 2) . ".";
    } else {
        // Passer $type et $ressourceData à ta méthode
        $result = $investC->updateInvestissement(
            $id,
            $montant_dt,
            $date,
            $id_startups,
            $date_fin,
            $type,
            $ressourceData
        );
    
        // SI ÉCHEC, ON AFFICHE L’ERREUR, SINON ON REDIRIGE
        if ($result) {
            setFlash('success', "Investissement #{$id} mis à jour.");
            header("Location: index.php");
            exit();
        } else {
            setFlash('warning', "Impossible de mettre à jour l’investissement #{$id}.");
        }
        
    }
}

// Traitement de la suppression d'un investissement
if (isset($_GET['delete'])) {
    $id = trim($_GET['delete']);
    $investC->deleteInvestissement($id);
    setFlash('info', "Investissement #{$id} supprimé.");
    header("Location: index.php");
    exit();
}

// Préparation de la modification si 'edit' est passé en GET
$investmentToEdit = null;
if (isset($_GET['edit'])) {
    $investmentData = $investC->getInvestissement(trim($_GET['edit']));
    if ($investmentData) {
        $investmentToEdit = $investmentData;
    }
}

// Par ce bloc :
//$db = config::getConnexion(); // ligne 103
//$sql = "SELECT * FROM investissements"; // ligne 104
//$listeInvestissements = $db->query($sql)->fetchAll(); // ligne 105

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Investissements</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e3f2fd, #ffffff);
            margin: 0;
            padding: 0;
            color: #333;
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        h1, h2 {
            text-align: center;
            color: #1976d2;
            margin-top: 20px;
        }

        form {
            background: #ffffff;
            padding: 25px;
            margin: 30px auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        label {
            font-weight: 600;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        input[type="submit"],
        button {
            background: #1976d2;
            border: none;
            color: #fff;
            padding: 12px 20px;
            margin-top: 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        input[type="submit"]:hover,
        button:hover {
            background: #115293;
        }

        .invest {
            background: #ffffff;
            padding: 20px;
            margin: 15px auto;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            animation: fadeIn 1s ease;
        }

        .invest p {
            margin: 8px 0;
        }

        .edit-link, .delete-link {
            display: inline-block;
            text-decoration: none;
            font-weight: 600;
            margin-right: 10px;
            padding: 8px 14px;
            border-radius: 6px;
        }

        .edit-link {
            background: #0288d1;
            color: #fff;
        }

        .edit-link:hover {
            background: #0277bd;
        }

        .delete-link {
            background: #e53935;
            color: #fff;
        }

        .delete-link:hover {
            background: #c62828;
        }

        .notification-erreur {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 12px 20px;
            margin: 20px auto;
            border-radius: 6px;
            max-width: 600px;
            font-weight: bold;
            text-align: center;
        }
        .invest-container {
              display: grid;
              grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
              gap: 1.5rem;
            }


        /* 🌙 Mode sombre */
        .dark-mode {
            background: linear-gradient(to right, #121212, #1e1e1e);
            color: #f0f0f0;
        }

        .dark-mode form,
        .dark-mode .invest {
            background: #2c2c2c;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.05);
        }

        .dark-mode input[type="text"],
        .dark-mode input[type="number"],
        .dark-mode input[type="date"],
        .dark-mode select {
            background-color: #3a3a3a;
            color: #f0f0f0;
            border: 1px solid #555;
        }

        .dark-mode label {
            color: #f0f0f0;
        }

        .dark-mode input[type="submit"],
        .dark-mode button {
            background: #0d47a1;
        }

        .dark-mode input[type="submit"]:hover,
        .dark-mode button:hover {
            background: #1565c0;
        }

        .dark-mode .edit-link {
            background: #039be5;
        }

        .dark-mode .edit-link:hover {
            background: #0288d1;
        }

        .dark-mode .delete-link {
            background: #ef5350;
        }

        .dark-mode .delete-link:hover {
            background: #e53935;
        }

        .dark-mode .notification-erreur {
            background-color: #512b2b;
            color: #fdd;
            border-color: #c33;
        }
        .section-header {
    background: linear-gradient(145deg, #e3f2fd, #ffffff);
    border-radius: 16px;
    padding: 20px;
    margin: 30px auto 10px auto;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    max-width: 700px;
    text-align: center;
    animation: fadeIn 1s ease-in;
}

.section-container {
    background: linear-gradient(145deg, #e3f2fd, #ffffff);
    border-radius: 16px;
    padding: 25px;
    margin: 30px auto;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
    max-width: 700px;
}

.section-container h2 {
    font-size: 22px;
    margin-bottom: 15px;
    color: #1976d2;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.small-form {
    max-width: 500px;
    padding: 20px;
}

/* 🌙 Mode sombre */
.dark-mode .section-container {
    background: #2c2c2c;
    box-shadow: 0 8px 20px rgba(255, 255, 255, 0.05);
}

.dark-mode .section-container h2 {
    color: #f0f0f0;
}
.animated-title {
    display: inline-block;
    position: relative;
    cursor: pointer;
    transition: color 0.3s ease;
}

/* Ligne animée en bas */
.animated-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 0%;
    height: 3px;
    background: #1976d2;
    transition: width 0.4s ease;
    border-radius: 6px;
}

/* Hover effect */
.animated-title:hover {
    color: #0d47a1;
    transform: scale(1.05);
    transition: transform 0.3s ease, color 0.3s ease;
}

.animated-title:hover::after {
    width: 100%;
}
.animated-title img:hover {
    transform: scale(1.2) rotate(10deg);
    transition: transform 0.3s ease;
}
.logo-invest {
    width: 40px;
    height: 40px;
    transition: transform 0.3s ease;
    cursor: pointer;
}

.logo-invest:hover {
    transform: rotate(10deg) scale(1.1);
}
.logo-liste {
    width: 40px;
    height: 40px;
    margin-top: 10px;
    transition: transform 0.3s ease;
    cursor: pointer;
}

.logo-liste:hover {
    transform: scale(1.1) rotate(-5deg);
}
.button {
  display: inline-block;
  padding: 8px 14px;
  background-color: #1976d2;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  margin-top: 8px;
  font-weight: bold;
}
.button:hover {
  background-color: #0d47a1;
}
/* Conteneur fixe en haut de page */
.flash-container {
  position: fixed;
  top: 1rem;
  right: 1rem;
  z-index: 1050;
  width: 300px;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

/* Style commun à toutes les alertes */
.flash {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  font-weight: 500;
  color: #fff;
  animation: slideIn 0.4s ease-out;
}

/* Animations */
@keyframes slideIn {
  from { transform: translateX(100%); opacity: 0; }
  to   { transform: translateX(0);   opacity: 1; }
}

/* Types */
.flash-success { background: #28a745; }
.flash-danger  { background: #dc3545; }
.flash-warning { background: #ffc107; color: #212529; }
.flash-info    { background: #17a2b8; }

/* Bouton de fermeture */
.flash .close-btn {
  margin-left: auto;
  background: transparent;
  border: none;
  color: inherit;
  font-size: 1.2rem;
  cursor: pointer;
}



        
 /* Bouton mode sombre */
        #toggle-dark {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: #333;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }
        #toggle-dark:hover {
            background: #0d47a1;
        }
        /* Cible uniquement ce formulaire (ou adapte le sélecteur) */
form[action="investissement_stats.php"] {
  background: none;
  border: none;
  padding: 0;
  margin: 0;
  display: inline;      /* pour qu’il ne prenne pas tout l’espace */
}





    </style>
</head>
<body>
<div class="flash-container">
    <?php foreach (getFlashes() as $flash): ?>
      <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
        <?= htmlspecialchars($flash['message']) ?>
        <button class="close-btn" onclick="this.parentNode.remove()">×</button>
      </div>
    <?php endforeach; ?>
  </div>


<button id="toggle-dark">🌙 Mode Sombre</button>
<div class="section-header dashboard-header">
  <h1 class="animated-title">Gestion des Investissements</h1>
  <img src="logo-invest.png" alt="Logo Invest" class="logo-invest">
</div>



    <?php if (!empty($erreur)): ?>
        <div class="notification-erreur">
            <?php echo htmlspecialchars($erreur); ?>
        </div>
    <?php endif; ?>

   

    

    <!-- Formulaire d'ajout -->
    <?php if (!$investmentToEdit): ?>
        <div class="section-container small-form">
    <h2 class="animated-title" style="display: flex; align-items: center; gap: 10px;">
        <h2 class="animated-title" style="margin-bottom:1rem;">
        <i class="fas fa-plus-circle"></i> Ajouter un Investissement
        <img src="btn.png" alt="Ajouter" style="width: 30px; height: 30px; cursor: pointer;">
        
        
    </h2>
   
    
   

    <!-- Ton formulaire ici -->
</div>

    <form method="post" action="">
        <label for="user_id">ID Utilisateur :</label>
        <input type="text" id="user_id" name="user_id" >

        <label for="montant_dt">Montant de l'investissement (en DT) :</label>
        <input type="number" id="montant_dt" name="montant_dt"  oninput="convertToEUR()">

        <label for="montant_eur">Montant Converti (en EUR) :</label>
        <input type="text" id="montant_eur" name="montant_eur" >

        <label for="date">Date de début :</label>
        <input type="date" id="date" name="date">

        <label for="date_fin">Date de fin :</label>
        <input type="date" id="date_fin" name="date_fin">

        <label for="id_startups">ID Startup :</label>
        <input type="text" id="id_startups" name="id_startups">
        
        <!-- ─────── Ajout du champ Type ─────── -->
        <label for="type">Type d'investissement :</label>
<select id="type" name="type">
  <option value="carte" <?= (!$investmentToEdit|| $investmentToEdit['type_investissement']=='carte')?'selected':'' ?>>Carte bancaire</option>
  <option value="autre" <?= ($investmentToEdit&& $investmentToEdit['type_investissement']=='autre')?'selected':'' ?>>Autre ressource</option>
</select>


        <!-- ─────── Bloc dynamique “autre ressource” ─────── -->
        <div id="bloc-autre" style="display:none; margin-top:10px;">
          <label for="type_ressource">Ressource :</label>
          <select id="type_ressource" name="type_ressource">
            <option value="voiture">Voiture</option>
            <option value="maison">Maison</option>
          </select>

          <label for="caracteristique">Caractéristique :</label>
          <input type="text" id="caracteristique" name="caracteristique"
                 placeholder="Ex: Renault Clio, 2018">
        </div>

        <input type="submit" name="submit" value="Ajouter l'investissement">
    </form>
    <?php endif; ?>

   

    <!-- Formulaire de modification -->
    <?php if ($investmentToEdit): ?>
        <h2>Modifier l'Investissement ID: <?php echo htmlspecialchars($investmentToEdit['id_investissement']); ?></h2>
    <form method="post" action="">
        <input type="hidden" name="id_investissement" value="<?php echo htmlspecialchars($investmentToEdit['id_investissement']); ?>">

        <label for="user_id">ID Utilisateur :</label>
        <input type="text" id="user_id" name="user_id"
               value="<?php echo htmlspecialchars($investmentToEdit['user_id']); ?>"
               disabled>

        <label for="montant_dt">Montant de l'investissement (en DT) :</label>
        <input type="number" id="montant_dt" name="montant_dt"
               value="<?php echo htmlspecialchars($investmentToEdit['montant_investissement']); ?>"
                step="0.01" oninput="convertToEUR()">

        <label for="montant_eur">Montant Converti (en EUR) :</label>
        <input type="text" id="montant_eur" name="montant_eur"
               value="<?php echo number_format($investmentToEdit['montant_investissement'] * 0.30, 2); ?>"
               readonly>

        <label for="date">Date de début :</label>
        <input type="date" id="date" name="date"
               value="<?php echo htmlspecialchars($investmentToEdit['date']); ?>"
               >

        <label for="date_fin">Date de fin :</label>
        <input type="date" id="date_fin" name="date_fin"
               value="<?php echo htmlspecialchars($investmentToEdit['date_fin']); ?>">

        <label for="id_startups">ID Startup :</label>
        <input type="text" id="id_startups" name="id_startups"
               value="<?php echo htmlspecialchars($investmentToEdit['id_startups']); ?>"
               >

        <!-- ─────── Champ Type ─────── -->
        <label for="type">Type d'investissement :</label>
        <select id="type" name="type">
          <option value="carte"
            <?php echo ($investmentToEdit['type_investissement'] ?? '') === 'carte' ? 'selected' : ''; ?>>
            Carte bancaire
          </option>
          <option value="autre"
            <?php echo ($investmentToEdit['type_investissement'] ?? '') === 'autre' ? 'selected' : ''; ?>>
            Autre ressource
          </option>
        </select>

        <!-- ─────── Bloc dynamique “autre ressource” ─────── -->
        <div id="bloc-autre" style="display:none; margin-top:10px;">
          <label for="type_ressource">Ressource :</label>
          <select id="type_ressource" name="type_ressource">
            <option value="voiture"
              <?php echo ($investmentToEdit['type_ressource'] ?? '') === 'voiture' ? 'selected' : ''; ?>>
              Voiture
            </option>
            <option value="maison"
              <?php echo ($investmentToEdit['type_ressource'] ?? '') === 'maison' ? 'selected' : ''; ?>>
              Maison
            </option>
          </select>

          <label for="caracteristique">Caractéristique :</label>
          <input type="text" id="caracteristique" name="caracteristique"
                 value="<?php echo htmlspecialchars($investmentToEdit['caracteristique'] ?? ''); ?>"
                 placeholder="Ex: Renault Clio, 2018">
        </div>

        <input type="submit" name="update" value="Mettre à jour l'investissement">
    </form>
    <?php endif; ?>

    <div class="section-header" style="text-align: center;">
    <h2 class="animated-title">Liste des Investissements</h2>
    <img src="logo-liste.png" alt="Logo Liste" class="logo-liste">
</div>


<div class="section-container">
  <h2>⏰ Échéances dans les 15 prochains jours</h2>

  <?php if (empty($comingDue)): ?>
    <p>Aucune échéance proche.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($comingDue as $inv): ?>
        <li>
          <strong>ID #<?php echo $inv['id_investissement']; ?></strong> — 
          <?php echo number_format($inv['montant_investissement'],2); ?> DT  
          (fin le <?php echo htmlspecialchars($inv['date_fin']); ?>)
          <a href="relancer.php?id=<?php echo $inv['id_investissement']; ?>" class="button">🚀 Relancer</a>
          


          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<div style="text-align:center; margin:20px 0;">
  <a href="generate_pdf.php" class="button">📄 Télécharger la liste PDF</a>
</div>



<div class="section-container small-form" style="display:flex; justify-content:center; gap:10px; flex-wrap:wrap;">
  <a href="index.php?sort=montant_asc"   class="button">Montant ↑</a>
  <a href="index.php?sort=montant_desc"  class="button">Montant ↓</a>
  <a href="index.php?sort=date_asc"      class="button">Date ↑</a>
  <a href="index.php?sort=date_desc"     class="button">Date ↓</a>
</div>
<form method="get" action="" style="text-align:center; margin-bottom:1rem;">
  <select name="search_champ">
    <option value="type_investissement">Type</option>
    <option value="user_id">User ID</option>
    <option value="id_startups">ID Startup</option>
  </select>
  <input type="text" name="search_val" placeholder="Mot-clé…">
  <button type="submit" class="button">🔍 Rechercher</button>
</form>





<?php 
if ($listeInvestissements) {
    foreach ($listeInvestissements as $invest) { ?>
        <div class="invest">
            <p><strong>ID :</strong> <?php echo htmlspecialchars($invest['id_investissement']); ?></p>
            <p><strong>User ID :</strong> <?php echo htmlspecialchars($invest['user_id']); ?></p>
            <p><strong>Montant :</strong> <?php echo htmlspecialchars(number_format($invest['montant_investissement'], 2)); ?> DT</p>
            <p><strong>Date :</strong> <?php echo htmlspecialchars($invest['date']); ?></p>
            <p><strong>ID Startup :</strong> <?php echo htmlspecialchars($invest['id_startups']); ?></p>
            <p><strong>Type :</strong> <?php echo htmlspecialchars($invest['type_investissement']); ?></p>

            <!-- Lien pour modifier -->
            <a class="edit-link" href="index.php?edit=<?php echo htmlspecialchars($invest['id_investissement']); ?>">Modifier</a>
            <a class="delete-link" href="index.php?delete=<?php echo htmlspecialchars($invest['id_investissement']); ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet investissement ?');">Supprimer</a>

            <!-- Nouveau bouton pour gérer les ressources -->
            <a class="button" 
               href="autreRessource.php?id_investissement=<?php echo htmlspecialchars($invest['id_investissement']); ?>">
              🛠 Gérer les ressources
            </a>
            <form action="investissement_stats.php" method="get">
  <button type="submit" class="mon-bouton">
    Voir les statistiques
  </button>
</form>
            
        </div>
<?php 
    }
} else {
    echo "<p>Aucun investissement trouvé.</p>";
}
?>

    <script>
    // Validation JS avant soumission
    const forms = document.querySelectorAll('form[action=""]');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const montant = parseFloat(form.montant_dt.value);
            const dateDebut = new Date(form.date.value);
            const dateFin = form.date_fin.value ? new Date(form.date_fin.value) : null;
            const now = new Date();
            const yearNow = now.getFullYear();

            if (isNaN(montant) || montant < 100) {
                alert("Le montant doit comporter au moins 3 chiffres (>= 100 DT).");
                e.preventDefault();
                return;
            }

            if (dateDebut.getFullYear() < yearNow - 2) {
                alert("La date de début est trop ancienne. Elle ne peut pas être antérieure à " + (yearNow - 2));
                e.preventDefault();
                return;
            }

            if (dateFin && dateFin <= dateDebut) {
                alert("La date de fin doit être postérieure à la date de début.");
                e.preventDefault();
                return;
            }
        });
    });

    // Conversion DT → EUR
    const conversionRate = 0.30; // Ajustez selon votre taux réel
    function convertToEUR() {
        const montantDT = parseFloat(document.getElementById("montant_dt").value);
        if (!isNaN(montantDT)) {
            const montantEUR = montantDT * conversionRate;
            document.getElementById("montant_eur").value = montantEUR.toFixed(2);
        } else {
            document.getElementById("montant_eur").value = '';
        }
    }
    </script>
    <script>
  // 1. Récupère les éléments
  const selectType = document.getElementById('type');
  const blocAutre  = document.getElementById('bloc-autre');

  // 2. Fonction d’affichage au chargement (utile en mode “modifier”)
  function toggleBlocAutre() {
    blocAutre.style.display = (selectType.value === 'autre') ? 'block' : 'none';
  }

  // 3. Lance au démarrage et sur chaque changement
  document.addEventListener('DOMContentLoaded', toggleBlocAutre);
  selectType.addEventListener('change', toggleBlocAutre);
</script>
<script>
        const toggleBtn = document.getElementById("toggle-dark");

        // Charger l'état du thème si déjà enregistré
        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark-mode");
        }

        toggleBtn.addEventListener("click", function () {
            document.body.classList.toggle("dark-mode");

            // Sauvegarder le choix
            if (document.body.classList.contains("dark-mode")) {
                localStorage.setItem("theme", "dark");
            } else {
                localStorage.setItem("theme", "light");
            }
        });
    </script>
    <script>
  document.getElementById('type').addEventListener('change', function(){
    if (this.value === 'autre') {
      // si on est en édition, on récupère l'id existant
      const idInv = document.querySelector('input[name="id_investissement"]')?.value;
      const url = 'autreRessource.php' + (idInv ? '?id_investissement=' + idInv : '');
      window.location.href = url;
    }
  });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const sound = document.getElementById('notif-sound');
  const flashes = document.querySelectorAll('.flash');

  if (flashes.length) {
    // joue le son
    sound.play().catch(()=>console.warn('Son bloqué'));
  }

  // pour chaque notification, supprime-la au bout de 25 s
  flashes.forEach(flash => {
    setTimeout(() => {
      // effet de fondu (optionnel)
      flash.style.transition = 'opacity 0.5s';
      flash.style.opacity = '0';
      // on supprime après le fade-out
      setTimeout(() => flash.remove(), 500);
    }, 25000);
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const sound = document.getElementById('notif-sound');
  if (document.querySelector('.flash')) {
    sound.play().catch(()=>console.warn('Son bloqué'));
  }
});
</script>


<audio id="notif-sound" src="assets/sounds/notification.wav" preload="auto"></audio>


</body>
</html>
