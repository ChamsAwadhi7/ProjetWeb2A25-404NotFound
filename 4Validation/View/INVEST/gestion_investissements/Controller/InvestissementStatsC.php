<?php
include_once __DIR__ . '/../auth/config.php';

/**
 * Classe de service pour fournir des statistiques sur les investissements.
 */
class InvestissementStatsC {
    /** @var PDO */
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    /**
     * Retourne le nombre total d'investissements.
     *
     * @return int
     */
    public function countAll(): int {
        $sql = "SELECT COUNT(*) AS total FROM investissements";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $row['total'];
    }

    /**
     * Retourne le montant total investi.
     *
     * @return float
     */
    public function sumMontant(): float {
        $sql = "SELECT COALESCE(SUM(montant_investissement),0) AS total FROM investissements";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) $row['total'];
    }

    /**
     * Retourne la moyenne des montants investis.
     *
     * @return float
     */
    public function avgMontant(): float {
        $sql = "SELECT COALESCE(AVG(montant_investissement),0) AS moyenne FROM investissements";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) $row['moyenne'];
    }

    /**
     * Retourne la répartition des investissements par type (nombre et pourcentage).
     *
     * @return array<array>
     */
    public function repartitionParType(): array {
        $sql = <<<SQL
SELECT
    type_investissement AS type,
    COUNT(*)            AS count,
    ROUND(100 * COUNT(*) / (SELECT COUNT(*) FROM investissements), 2) AS pct
FROM investissements
GROUP BY type_investissement;
SQL;
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne le top N des utilisateurs par montant total investi.
     *
     * @param int $limit
     * @return array<array>
     */
    public function topInvestisseurs(int $limit = 5): array {
        $sql = <<<SQL
SELECT
    u.nom                           AS utilisateur,
    SUM(i.montant_investissement)   AS total
FROM investissements i
JOIN utilisateurs u ON i.user_id = u.id_utilisateur
GROUP BY i.user_id
ORDER BY total DESC
LIMIT :limit;
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    /**
     * Retourne les investissements dont la date de fin est dans les prochains jours.
     *
     * @param int $days
     * @return array<array>
     */
    public function prochesEcheances(int $days = 15): array {
        $sql = <<<SQL
SELECT *
FROM investissements
WHERE date_fin BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :days DAY)
ORDER BY date_fin ASC;
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function statsParStartup(): array {
        $sql = "
            SELECT 
                s.nom AS startup,
                COUNT(i.id_investissement) AS nb_investissements,
                SUM(i.montant_investissement) AS total_investi,
                AVG(i.montant_investissement) AS moyenne_investie
            FROM investissements i
            JOIN startups s ON s.id_startup = i.id_startups
            GROUP BY s.nom
            ORDER BY total_investi DESC
        ";
    
        try {
            $db = config::getConnexion();
            return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    public function getStatistics() {
        $sql = "
            SELECT 
                id_startups AS startup_id,
                COUNT(*) AS count,
                SUM(montant_investissement) AS total
            FROM investissements
            GROUP BY id_startups
        ";
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // Gestion d'erreur
            echo 'Erreur statistiques : ' . $e->getMessage();
            return [];
        }
    }
    
}
?>
