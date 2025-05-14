<?php
$message = "";

require_once '../../config.php';
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    header('Location: ../login_register.php');
    exit;
}

// On récupère l'ID utilisateur depuis la session correctement
$id_user = $_SESSION['utilisateur']['id']; // ✅ CORRECTION

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $montant = $_POST['balance'];
    $etat = "En attente";

    if (isset($_FILES["recu"]) && $_FILES["recu"]["error"] == 0) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = basename($_FILES["recu"]["name"]);
        $targetFile = $uploadDir . time() . "_" . $filename;

        if (move_uploaded_file($_FILES["recu"]["tmp_name"], $targetFile)) {
            $stmt = $pdo->prepare("INSERT INTO demandefinance (id_user, Montant, pdf, etat) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_user, $montant, $targetFile, $etat]);

            $message = "<div class='notification success'>✅ Paiement enregistré avec succès.</div>";
        } else {
            $message = "<div class='notification error'>❌ Erreur lors de l'envoi du fichier.</div>";
        }
    } else {
        $message = "<div class='notification error'>❌ Veuillez ajouter une image du reçu.</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement NEXT STEP</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        header, footer {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        label {
            font-weight: 600;
            display: block;
            margin-top: 15px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        input[type="file"] {
            border: none;
        }
        button {
            background-color: #007acc;
            color: white;
            border: none;
            padding: 12px 20px;
            margin-top: 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #005fa3;
        }
        .notification {
            margin-top: 20px;
            padding: 10px;
            border-radius: 8px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<header>
    <h1>NEXT STEP</h1>
    <p>Adresse : 2081 Rue Paris, Ariana Sghoraa, Tunis</p>
    <p><strong>RIB :</strong> 08-707-00030220109920-22 | <strong>IBAN :</strong> TN59-0870-7000-3020-1099-2022</p>
</header>

<div class="container">
    <h2>Formulaire de Paiement</h2>

    <?= $message ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="balance">Montant (DT)</label>
        <input type="number" name="balance" id="balance" required>

        <label for="recu">Image du reçu de virement</label>
        <input type="file" name="recu" id="recu" accept="image/*" required>

        <button type="submit">Envoyer le paiement</button>
    </form>
</div>

<footer>
    <p>Contactez-nous : contact@nextstep.com | Tél : +216 50 077 187 / +216 20 786 941</p>
    <p>&copy; 2025 NEXT STEP - Tous droits réservés</p>
</footer>

</body>
</html>
