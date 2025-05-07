<?php
class config {
    public static function getConnexion() {
        $host = 'localhost';
        $dbname = 'nextstep';
        $username = 'root'; 
        $password = '';  

        try {
            $conn = new PDO("mysql:host=$host;dbname=" . urlencode($dbname), $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die('Database Connection Failure ' . $e->getMessage());
        }
    }
}

?>