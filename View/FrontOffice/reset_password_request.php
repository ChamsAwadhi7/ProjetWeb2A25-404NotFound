<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../BackOffice/Mail/PHPMailer/PHPMailerAutoload.php';

$pdo = Database::getInstance()->getConnection();

if (!isset($_POST['email'])) {
    $_SESSION['message'] = "Veuillez entrer un email.";
    header("Location: login_register.php");
    exit();
}

$email = trim($_POST['email']);

// Vérifie si un utilisateur actif existe
$stmt = $pdo->prepare("SELECT id FROM utilisateur WHERE email = ? AND status = 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['message'] = "Aucun utilisateur actif trouvé avec cet email.";
    header("Location: login_register.php");
    exit();
}

// Générer un token et stocker temporairement
$token = bin2hex(random_bytes(32));
$expire = time() + 120; // 2 minutes

$_SESSION['reset_token'] = $token;
$_SESSION['reset_email'] = $email;
$_SESSION['reset_expire'] = $expire;

// Lien de réinitialisation
$reset_link = "http://localhost/NextStep/view/FrontOffice/reset_password.php?token=$token";

// Envoi du mail
$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'elidmansour0@gmail.com';
$mail->Password = 'jygi emyw uxdq qkym'; // mot de passe d’application
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];

$mail->setFrom('elidmansour0@gmail.com', 'NextStep Support');
$mail->addAddress($email);
$mail->Subject = 'Réinitialisation de votre mot de passe';
$mail->Body = "Bonjour,\n\nCliquez ici pour réinitialiser votre mot de passe :\n$reset_link\n\nCe lien expire dans 2 minutes.\n\nCordialement,\nL’équipe NextStep";

if ($mail->send()) {
    $_SESSION['message'] = "Un lien de réinitialisation a été envoyé à votre email.";
} else {
    $_SESSION['message'] = "Erreur lors de l'envoi du mail : " . $mail->ErrorInfo;
}

header("Location: login_register.php");
exit();
