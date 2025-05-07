<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/Mail/PHPMailer/PHPMailerAutoload.php'; // Corrigé le chemin

$pdo = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Vérifier si utilisateur actif existe
    $stmt = $pdo->prepare("SELECT id FROM utilisateur WHERE email = ? AND status = 1");
    $stmt->execute([$email]);
    if (!$stmt->fetch()) {
        $_SESSION['message'] = "Aucun administrateur actif trouvé avec cet email.";
        header('Location: login.php');
        exit();
    }

    // Générer OTP (6 chiffres)
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $_SESSION['otp_reset']   = $otp;
    $_SESSION['email_reset'] = $email;
    $_SESSION['otp_expire']  = time() + 300; // expire dans 5 min

    // Configuration PHPMailer
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'elidmansour0@gmail.com';
    $mail->Password   = 'jygi emyw uxdq qkym';  // mot de passe d'application
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true,
        ],
    ];

    // Définir l'expéditeur et destinataire
    $mail->setFrom('elidmansour0@gmail.com', 'NextStep Support');
    $mail->addReplyTo('contact@nextstep.com', 'Support NextStep');
    $mail->addAddress($email);
    
    $mail->Subject = 'Code de réinitialisation';
    $mail->Body    = "Bonjour,\n\nVotre code OTP est : $otp\nIl expire dans 5 minutes.\n\nCordialement,\nL'équipe NextStep";

    if ($mail->send()) {
        $_SESSION['message'] = "Un code de vérification a été envoyé à votre adresse email.";
    } else {
        $_SESSION['message'] = "Erreur envoi mail : {$mail->ErrorInfo}";
    }

    header('Location: verify_otp.php');
    exit();
}
