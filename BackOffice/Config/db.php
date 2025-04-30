<?php
//BACKOFFICE
class config {
    public static function getConnexion() {
        $host = 'localhost';
        $dbname = 'nextstep';
        $username = 'root'; 
        $password = '';  

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