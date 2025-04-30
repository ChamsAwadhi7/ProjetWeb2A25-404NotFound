<?php
class Projet {
    public static function countIncubatedStartups($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM projets WHERE statut IN ('en_developpement', 'termine')");
        return $stmt->fetchColumn();
    }
}
?>