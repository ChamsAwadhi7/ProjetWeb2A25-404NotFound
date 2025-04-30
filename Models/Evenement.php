<?php
class Evenement {
    public static function countUpcomingEvents($pdo) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM événements WHERE date >= CURDATE()");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>