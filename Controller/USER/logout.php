<?php
session_start();
$_SESSION = [];
session_unset();
session_destroy();

// Suppression du cookie de session (optionnel mais recommandé)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

header('Location: ../View/FrontOffice/login_register.php');
exit;
?>
