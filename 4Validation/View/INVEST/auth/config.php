<?php
class config {
    private static $db = null;

    public static function getConnexion() {
        if (self::$db === null) {
            $host ='127.0.0.1';           // Adresse du serveur de base de données
            $dbname = 'gestion_investissements'; // Nom de la base de données créée
            $username = 'root';            // Nom d'utilisateur (à adapter)
            $password = '';                // Mot de passe (à adapter)

            try {
                self::$db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Erreur de connexion : ' . $e->getMessage());
            }
        }
        return self::$db;
    }
   
}
?>
