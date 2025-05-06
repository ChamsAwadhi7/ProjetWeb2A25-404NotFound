<?php
session_start();
require 'C:/xampp/htdocs/gestion_investissements/Model/Investissement.php';
require 'C:/xampp/htdocs/gestion_investissements/Controller/InvestissementC.php';
require 'C:/xampp/htdocs/gestion_investissements/utils/flash.php';



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
// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));}

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
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            color: #333;
        }

        /* Header */
        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-title {
            font-size: 1.8rem;
            color: #1976d2;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        /* Contrôles de tri et recherche */
        .controls-container {
            max-width: 1400px;
            margin: 1rem auto;
            padding: 0 2rem;
        }

        .sorting-controls {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .sort-btn {
            background: #e3f2fd;
            color: #1976d2;
            border: none;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .sort-btn:hover {
            background: #bbdefb;
        }

        .search-form {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
            margin: 1rem 0;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        /* Grille d'investissements */
        .invest-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .invest-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            transition: transform 0.3s ease;
        }

        .invest-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .investment-id {
            font-weight: 600;
            color: #1976d2;
        }

        .investment-dates small {
            color: #666;
            display: block;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin: 0.8rem 0;
        }

        .detail-label {
            color: #666;
        }

        .detail-value {
            font-weight: 500;
        }

        .investment-actions {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
        }

        .action-btn {
            padding: 0.6rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .edit-btn { background: #e3f2fd; color: #1976d2; }
        .delete-btn { background: #fce4ec; color: #d81b60; }
        .resource-btn { background: #e8f5e9; color: #2e7d32; }
        .stats-btn { background: #f3e5f5; color: #9c27b0; }

        /* Dark Mode */
        .dark-mode {
            background: #1a1a1a;
            color: #fff;
        }

        .dark-mode .invest-card {
            background: #2d2d2d;
            border-color: #404040;
        }

        .dark-mode .detail-label {
            color: #ccc;
        }

        .dark-mode .sort-btn {
            background: #2d4059;
            color: #fff;
        }

        .dark-mode .search-input {
            background: #333;
            border-color: #555;
            color: #fff;
        }

        #toggle-dark {
            background: #333;
            color: white;
            border: none;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
        }

        .pdf-button {
            background: #28a745;
            color: white;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
        }

        /* Formulaire de modification modernisé */
        .modern-form-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 3rem;
            border-radius: 30px;
            background: #f0f5f9;
            box-shadow: 12px 12px 24px #d1d9e6, 
                        -12px -12px 24px #ffffff;
        }

        .form-header {
            margin-bottom: 3rem;
            text-align: center;
        }

        .form-title {
            color: #2d3436;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #6c5ce7, #0984e3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .input-group {
            position: relative;
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        .input-group:hover {
            transform: translateY(-3px);
        }

        .input-field {
            width: 100%;
            padding: 1.5rem;
            border: none;
            border-radius: 15px;
            background: #f0f5f9;
            box-shadow: inset 5px 5px 10px #d1d9e6, 
                        inset -5px -5px 10px #ffffff;
            font-size: 1.1rem;
            color: #2d3436;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            box-shadow: inset 2px 2px 5px #d1d9e6, 
                        inset -2px -2px 5px #ffffff;
            outline: none;
            animation: input-focus 0.4s ease;
        }

        .currency-display {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            border-radius: 15px;
            background: #f0f5f9;
            box-shadow: 5px 5px 10px #d1d9e6, 
                       -5px -5px 10px #ffffff;
        }

        .currency-amount {
            font-size: 1.8rem;
            font-weight: 700;
            color: #6c5ce7;
        }

        .currency-symbol {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: linear-gradient(145deg, #ffffff, #d1d9e6);
            box-shadow: 3px 3px 6px #d1d9e6, 
                       -3px -3px 6px #ffffff;
        }

        .date-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin: 2rem 0;
        }

        .action-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: flex-end;
            margin-top: 3rem;
        }

        .btn {
            padding: 1.2rem 2.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #6c5ce7, #0984e3);
            color: white;
            box-shadow: 5px 5px 10px #d1d9e6, 
                       -5px -5px 10px #ffffff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 8px 8px 15px #d1d9e6, 
                       -8px -8px 15px #ffffff;
        }

        .btn-secondary {
            background: #f0f5f9;
            color: #2d3436;
            box-shadow: 5px 5px 10px #d1d9e6, 
                       -5px -5px 10px #ffffff;
        }

        @keyframes input-focus {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .invest-grid {
                padding: 1rem;
                grid-template-columns: 1fr;
            }
            
            .sorting-controls {
                justify-content: center;
            }

            .modern-form-container {
                padding: 2rem;
                margin: 1rem;
                border-radius: 25px;
            }
            
            .date-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- En-tête -->
    <div class="main-header">
        <h1 class="header-title">
            📋 Liste des Investissements
        </h1>
        <div class="header-actions">
        <a href="stats.php" class="pdf-button">📊 Statistiques</a> <!-- Nouveau bouton -->
            <a href="generate_pdf.php" class="pdf-button">📄 Générer PDF</a>
            <button id="toggle-dark">🌙 Mode Sombre</button>
        </div>
    </div>

        <!-- Formulaire de modification -->
        <?php if ($investmentToEdit): ?>
<div class="modern-form-container">
    <div class="form-header">
        <h2 class="form-title">
            <i class="fas fa-edit"></i>
            Modification Investissement #<?= htmlspecialchars($investmentToEdit['id_investissement']) ?>
        </h2>
        <div class="form-subtitle">Veuillez modifier les informations ci-dessous</div>
    </div>

    <form method="post" action="" class="modern-edit-form">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="id_investissement" value="<?= htmlspecialchars($investmentToEdit['id_investissement']) ?>">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($investmentToEdit['user_id']) ?>">

        <div class="form-grid">
            <!-- Montant -->
            <div class="input-group amount-group">
                <div class="input-prefix">DT</div>
                <input type="number" id="montant_dt" name="montant_dt" 
                    value="<?= htmlspecialchars($investmentToEdit['montant_investissement']) ?>" 
                    step="0.01" required
                    oninput="convertToEUR()">
                <label for="montant_dt">Montant en dinars</label>
            </div>

            <div class="input-group">
                <input type="text" id="montant_eur" name="montant_eur" 
                    value="<?= number_format($investmentToEdit['montant_investissement'] * 0.30, 2) ?>" 
                    readonly class="eur-input">
                <label for="montant_eur">Équivalent en euros</label>
                <div class="currency-badge">€</div>
            </div>

            <!-- Dates -->
            <div class="input-group date-input">
                <input type="date" id="date" name="date" 
                    value="<?= htmlspecialchars($investmentToEdit['date']) ?>" 
                    required>
                <label for="date">Date de début</label>
                <i class="fas fa-calendar-alt"></i>
            </div>

            <div class="input-group date-input">
                <input type="date" id="date_fin" name="date_fin" 
                    value="<?= htmlspecialchars($investmentToEdit['date_fin']) ?>">
                <label for="date_fin">Date de fin (optionnel)</label>
                <i class="fas fa-calendar-alt"></i>
            </div>

            <!-- Startup -->
            <div class="input-group">
                <input type="number" id="id_startups" name="id_startups" 
                    value="<?= htmlspecialchars($investmentToEdit['id_startups']) ?>" 
                    required>
                <label for="id_startups">ID Startup</label>
                <i class="fas fa-building"></i>
            </div>

            <!-- Type -->
            <div class="input-group select-group">
                <select id="type" name="type" required>
                    <option value="carte" <?= ($investmentToEdit['type'] ?? '') === 'carte' ? 'selected' : '' ?>>Carte Bancaire</option>
                    <option value="cheque" <?= ($investmentToEdit['type'] ?? '') === 'cheque' ? 'selected' : '' ?>>Chèque</option>
                    <option value="autre" <?= ($investmentToEdit['type'] ?? '') === 'autre' ? 'selected' : '' ?>>Autre Moyen</option>
                </select>
                <label for="type">Type de paiement</label>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="update" class="modern-btn save-btn">
                <i class="fas fa-save"></i>
                Enregistrer les modifications
            </button>
            <a href="index.php" class="modern-btn cancel-btn">
                <i class="fas fa-times"></i>
                Annuler
            </a>
        </div>
    </form>
</div>
<?php endif; ?>

    

    <!-- Contrôles de tri et recherche -->
    <div class="controls-container">
        <div class="sorting-controls">
            <a href="index.php?sort=montant_asc" class="sort-btn">
                ▲ Montant Croissant
            </a>
            <a href="index.php?sort=montant_desc" class="sort-btn">
                ▼ Montant Décroissant
            </a>
            <a href="index.php?sort=date_asc" class="sort-btn">
                ▲ Date Croissante
            </a>
            <a href="index.php?sort=date_desc" class="sort-btn">
                ▼ Date Décroissante
            </a>
        </div>

        <form method="get" action="" class="search-form">
            <select name="search_champ" class="search-input">
                <option value="type_investissement">Type</option>
                <option value="user_id">User ID</option>
                <option value="id_startups">ID Startup</option>
            </select>
            <input type="text" 
                   name="search_val" 
                   placeholder="Rechercher..." 
                   class="search-input">
            <button type="submit" class="action-btn stats-btn">
                🔍 Rechercher
            </button>
        </form>
    </div>

    <!-- Grille d'investissements -->
    <div class="invest-grid">
        <?php if ($listeInvestissements): ?>
            <?php foreach ($listeInvestissements as $invest): ?>
                <div class="invest-card">
                    <div class="card-header">
                        <div class="investment-id">ID #<?= htmlspecialchars($invest['id_investissement']) ?></div>
                        <div class="investment-dates">
                            <small>Début: <?= htmlspecialchars($invest['date']) ?></small>
                            <small>Fin: <?= htmlspecialchars($invest['date_fin']) ?></small>
                        </div>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Utilisateur</span>
                        <span class="detail-value"><?= htmlspecialchars($invest['user_id']) ?></span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Montant</span>
                        <span class="detail-value">
                            <?= number_format($invest['montant_investissement'], 2) ?> DT
                            <small>(≈ <?= number_format($invest['montant_investissement'] * 0.3, 2) ?> EUR)</small>
                        </span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Startup</span>
                        <span class="detail-value"><?= htmlspecialchars($invest['id_startups']) ?></span>
                    </div>
                    

                    <div class="investment-actions">
                        <a href="index.php?edit=<?= $invest['id_investissement'] ?>" class="action-btn edit-btn">✏️ Modifier</a>
                        <a href="index.php?delete=<?= $invest['id_investissement'] ?>" class="action-btn delete-btn" onclick="return confirm('Supprimer cet investissement ?')">🗑️ Supprimer</a>
                        <a href="autreRessource.php?id_investissement=<?= $invest['id_investissement'] ?>" class="action-btn resource-btn">🛠 Ressources</a>
                        <form action="investissement_stats.php" method="get" style="display: contents;">
                            <button type="submit" class="action-btn stats-btn">📈 Statistiques</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-results">Aucun investissement trouvé</p>
        <?php endif; ?>
    </div>


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
  




