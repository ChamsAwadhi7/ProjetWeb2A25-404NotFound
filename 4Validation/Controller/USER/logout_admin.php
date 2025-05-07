<?php
session_start();

// Supprimer toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Effacer le cookie de session
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');  // Expire immédiatement
}

// Régénérer l'ID de session pour des raisons de sécurité
session_regenerate_id(true);

// Rediriger l'utilisateur vers la page de connexion
header('Location: ../view/BackOffice/login.php');
exit();
?>
