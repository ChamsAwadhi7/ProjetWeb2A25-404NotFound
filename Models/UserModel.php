<?php
class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function countUsers() {
        return $this->db->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
    }

    public function getPaginatedUsers($page, $perPage) {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare("
            SELECT u.*, a.tel 
            FROM utilisateur u
            LEFT JOIN admin a ON u.id = a.id
            ORDER BY u.id DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentUsers($limit) {
        $stmt = $this->db->prepare("
            SELECT * FROM utilisateur 
            ORDER BY date_inscription DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createAdmin($data) {
        $this->db->beginTransaction();

        try {
            // Création utilisateur
            $stmt = $this->db->prepare("
                INSERT INTO utilisateur (nom, prénom, email, password, role) 
                VALUES (:nom, :prenom, :email, :password, 'admin')
            ");
            $stmt->execute([
                ':nom' => $data['nom'],
                ':prenom' => $data['prenom'],
                ':email' => $data['email'],
                ':password' => $data['password']
            ]);

            $userId = $this->db->lastInsertId();

            // Ajout dans la table admin
            $stmt = $this->db->prepare("INSERT INTO admin (id, tel) VALUES (:id, :tel)");
            $stmt->execute([
                ':id' => $userId,
                ':tel' => $data['tel']
            ]);

            $this->db->commit();
            return $userId;

        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Erreur lors de la création de l'administrateur: " . $e->getMessage());
        }
    }

    public function updateUserRole($userId, $newRole) {
        $stmt = $this->db->prepare("UPDATE utilisateur SET role = :role WHERE id = :id");
        return $stmt->execute([':role' => $newRole, ':id' => $userId]);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    
}
?>