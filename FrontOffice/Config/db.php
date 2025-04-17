<?php
//FRONTOFFICE
class config {
    public static function getConnexion() {
        $host = 'localhost';
        $dbname = 'formationsparticipations';  // Updated database name
        $username = 'root';  // Keep this if it is correct, otherwise update it
        $password = '';  // Set your password if needed

        try {
            // Escape special characters in the database name for the connection string
            $conn = new PDO("mysql:host=$host;dbname=" . urlencode($dbname), $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die('Database Connection Failure ' . $e->getMessage());
        }
    }
}

?>