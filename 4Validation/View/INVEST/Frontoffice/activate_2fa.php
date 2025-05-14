<?php
session_start();

require_once __DIR__ . '/../../vendor/autoload.php';

use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\Providers\Qr\QRServerProvider;

// Instanciation du service 2FA avec un fournisseur QR externe
$tfa = new TwoFactorAuth(
    new QRServerProvider(),
    issuer: 'GestionInvest'
);

// Génération et stockage du secret TOTP
$secret = $tfa->createSecret();
$_SESSION['2fa_secret'] = $secret;

// Génération de l'URL du QR Code
$qrCodeUrl = $tfa->getQRCodeImageAsDataUri('Investisseur', $secret);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activation de la 2FA</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f4f8;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .container {
      background: #ffffff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      text-align: center;
      animation: fadeIn 0.6s ease-out;
    }
    h2 {
      color: #1976d2;
      margin-bottom: 1rem;
    }
    p {
      margin: 0.5rem 0;
      color: #333;
    }
    .qr-code {
      margin: 1rem 0;
      border: 1px solid #ddd;
      padding: 0.5rem;
      border-radius: 8px;
      background: #fafafa;
    }
    .secret {
      word-break: break-all;
      font-family: monospace;
      background: #e3f2fd;
      padding: 0.5rem;
      border-radius: 6px;
      margin-bottom: 1rem;
    }
    .btn {
      display: inline-block;
      margin-top: 1rem;
      padding: 0.75rem 1.5rem;
      background: #1976d2;
      color: #fff;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      transition: background 0.3s;
    }
    .btn:hover {
      background: #115293;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Activation de la 2FA</h2>
    <p>1) Ouvre l'application Google Authenticator sur ton iPhone ou Android.</p>
    <p>2) Clique sur “+” puis “Scanner un code-barres”.</p>
    <div class="qr-code">
      <img src="<?= $qrCodeUrl ?>" alt="QR Code 2FA">
    </div>
    <p>Si tu ne peux pas scanner, copie manuellement cette clé :</p>
    <div class="secret"><?= htmlspecialchars($secret) ?></div>
    <a href="form.php" class="btn">→ Tester l'ajout d'investissement</a>
  </div>
</body>
</html>
