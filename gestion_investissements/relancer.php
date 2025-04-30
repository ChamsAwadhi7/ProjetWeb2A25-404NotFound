<?php
// 1) Includes basiques
require_once __DIR__ . '/auth/config.php';
require_once __DIR__ . '/InvestissementC.php';

// 2) Récupérer et valider l’ID
$idInv = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (! $idInv) {
    die("ID d’investissement manquant ou invalide");
}

// 3) Récupérer l’investissement
$investC = new InvestissementC();
$inv = $investC->getInvestissement($idInv);
if (! $inv) {
    die("Investissement introuvable (ID #{$idInv})");
}

// 4) Récupérer l’utilisateur
$db    = config::getConnexion();
$stmt  = $db->prepare("SELECT nom, email FROM utilisateurs WHERE id_utilisateur = :uid");
$stmt->bindValue(':uid', $inv['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (! $user) {
    die("Utilisateur introuvable (ID #{$inv['user_id']})");
}

// 5) Préparer et envoyer l’email
$to      = $user['email'];
$subject = "Rappel : échéance de votre investissement #{$inv['id_investissement']}";
$body    = 
    "Bonjour {$user['nom']},\n\n" .
    "Votre investissement n°{$inv['id_investissement']} de " .
    "{$inv['montant_investissement']} DT arrive à échéance le {$inv['date_fin']}.\n\n" .
    "Merci de nous contacter pour prolonger ou récupérer vos fonds.\n\n" .
    "Cordialement,\nNextStep Invest";

$headers = implode("\r\n", [
    'From: no-reply@tonsite.com',
    'Reply-To: no-reply@tonsite.com',
    'X-Mailer: PHP/' . phpversion(),
]);

if (mail($to, $subject, $body, $headers)) {
    $message = "✅ Email de relance envoyé à {$user['email']}.";
} else {
    $message = "❌ Échec de l'envoi de l'email.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Relance effectuée</title>
  <style>
    body { font-family: sans-serif; padding: 2rem; }
    .message { padding: 1rem; background: #f0f0f0; border-radius: 6px; }
    a { display: inline-block; margin-top: 1rem; color: #1976d2; text-decoration: none; }
  </style>
</head>
<body>
  <div class="message">
    <?php echo htmlspecialchars($message); ?>
  </div>
  <a href="index.php">← Retour à la liste</a>
</body>
</html>
