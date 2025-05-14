<?php
session_start();  // Démarrer la session

// Charger l'autoload de Composer et les dépendances
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../Model/Investissement.php';
require __DIR__ . '/../../Controller/InvestissementC.php';
require __DIR__ . '/../../utils/flash.php';
//Import des classes 2FA
use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\Providers\Qr\QRServerProvider;
//Initialisation des variables
$investC = new InvestissementC();
$erreur  = "";

if (isset($_POST['submit'])) {
    // 1) Récupération des données du formulaire
    $user_id     = (int) ($_POST['user_id']      ?? 0);
    $montant_dt  = (float) trim($_POST['montant_dt'] ?? '0');
    $date        = $_POST['date']         ?? date('Y-m-d');
    $date_fin    = $_POST['date_fin']     ?? null;
    $id_startups = (int) ($_POST['id_startups'] ?? 0);
    $type        = $_POST['type']         ?? 'carte';
    $otp         = trim($_POST['otp']     ?? '');

    // 2) Vérification TOTP via la session
    if (empty($_SESSION['2fa_secret'])) {
        $erreur = "La clé 2FA n'est pas définie. Activez d'abord la 2FA.";
    } else {
        $secret = $_SESSION['2fa_secret'];

        // Instanciation de TwoFactorAuth avec QRServerProvider
        $tfa = new TwoFactorAuth(
            new QRServerProvider(),  
            issuer: 'GestionInvest'  
        );

        if (!$tfa->verifyCode($secret, $otp, 2)) {
            $erreur = "Code Google Authenticator invalide. Merci de réessayer.";
        }
    }
    // la tolérance en intervalles 

    // 3) Validation métier si pas d'erreur TOTP
    if (empty($erreur)) {
        if ($montant_dt < 100) {
            $erreur = "Le montant doit être ≥ 100 DT.";
        } elseif ((int) date('Y', strtotime($date)) < date('Y') - 2) {
            $erreur = "La date de début ne peut pas être antérieure à " . (date('Y') - 2) . ".";
        } else {
            // Conversion DT → EUR
            $taux        = 3.4;
            $montant_eur = round($montant_dt / $taux, 2);

            // Ajout de l'investissement
            $newId = $investC->addInvestissement(
                $user_id,
                $montant_eur,
                $date,
                $id_startups,
                $date_fin,
                $type,
                $ressourceData ?? null
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
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Investissement</title>
    <style>
        /* Styles de base */
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
            color: #333;
        }

        /* Conteneur principal */
        .page-container {
            display: flex;
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Colonne formulaire */
        .form-wrapper {
            flex: 1;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        /* Section GIF */
        .gif-wrapper {
            flex: 1;
            align-self: center;
        }

        .gif-wrapper img {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        /* Éléments du formulaire */
        form {
            display: grid;
            gap: 15px;
            margin-top: 25px;
        }

        label {
            font-weight: 500;
            color: #2c3e50;
        }

        input, select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background: #1976d2;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 15px;
        }

        input[type="submit"]:hover {
            background: #1565c0;
        }

        /* Mode sombre */
        .dark-mode {
            background: #1a1a1a;
            color: #e0e0e0;
        }

        .dark-mode .form-wrapper {
            background: #2d2d2d;
        }

        .dark-mode input, 
        .dark-mode select {
            background: #3a3a3a;
            border-color: #555;
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-container {
                flex-direction: column;
                padding: 10px;
            }
            
            .gif-wrapper {
                order: -1;
                max-width: 500px;
                margin: 0 auto;
            }
            .conversion-info {
    background: #e3f2fd;
    padding: 12px;
    border-radius: 6px;
    margin: 15px 0;
}

.conversion-info label {
    font-weight: 500;
    color: #1976d2;
    display: block;
    margin-bottom: 5px;
}

#montant_eur {
    font-size: 1.1em;
    color: #2c3e50;
    font-weight: bold;
}

.dark-mode .conversion-info {
    background: #2d4052;
}
        }
    </style>
</head>
<body>
    <button onclick="document.body.classList.toggle('dark-mode')" 
            style="position: fixed; top: 20px; right: 20px; padding: 8px 15px; border-radius: 6px; cursor: pointer;">
        🌓 Mode sombre
    </button>

    <div class="page-container">
        <!-- Colonne Formulaire -->
        <div class="form-wrapper">
            <h1 style="margin: 0 0 25px 0; color: #1976d2;">Ajouter un nouvel investissement</h1>
            
            <?php if (!empty($erreur)): ?>
                <div style="color: #dc3545; padding: 10px; background: #ffe6e6; border-radius: 6px; margin-bottom: 20px;">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div>
                    <label for="user_id">ID Utilisateur</label>
                    <input type="number" id="user_id" name="user_id" required>
                </div>

                <div>
                    <label for="montant_dt">Montant (DT)</label>
                    <input type="number" id="montant_dt" name="montant_dt" step="0.01" required>
                </div>
                
                <div>
                    <label for="date">Date de début</label>
                    <input type="date" id="date" name="date" required>
                </div>

                <div>
                    <label for="date_fin">Date de fin (optionnel)</label>
                    <input type="date" id="date_fin" name="date_fin">
                </div>

                <div>
                    <label for="id_startups">ID Startup</label>
                    <input type="number" id="id_startups" name="id_startups" required>
                </div>
                <label for="otp">Code Google Authenticator (6 chiffres) :</label>
<input
  type="text"
  id="otp"
  name="otp"
  pattern="\d{6}"
  maxlength="6"
  required
/>


                

                <input type="submit" name="submit" value="Ajouter">
            </form>
        </div>

        <!-- Colonne GIF -->
        <div class="gif-wrapper">
            <img src="/gestion_investissements/assets/modern.gif" 
                 alt="Visualisation interactive d'investissement">
        </div>
    </div>

    <script>
        // Affichage dynamique des champs
        document.getElementById('type').addEventListener('change', function() {
            document.getElementById('autre-ressource').style.display = 
                this.value === 'autre' ? 'grid' : 'none';
        });

        // Conversion devise
        document.getElementById('montant_dt').addEventListener('input', function(e) {
    const taux = 3.4;
    const montantEUR = e.target.value / taux;
    document.getElementById('montant_eur').textContent = 
        montantEUR > 0 ? `${montantEUR.toFixed(2)} €` : "0.00 €";
});

    </script>
    
</body>
</html>