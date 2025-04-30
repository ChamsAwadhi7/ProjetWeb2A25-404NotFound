<?php
#[AllowDynamicProperties]
class User {
    protected $id;
    protected $nom;
    protected $prenom;
    protected $email;
    protected $role;
    protected $dateInscription;
    // Constructor for setting the database connection
    public function __construct($db) {
        $this->db = $db;
    }
    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }
    public function getDateInscription() { return $this->dateInscription; }

    // Setters
    public function setNom($nom) { $this->nom = $nom; }
    public function setPrenom($prenom) { $this->prenom = $prenom; }
    public function setEmail($email) { $this->email = $email; }
    public function setRole($role) { $this->role = $role; }

    // Méthode pour récupérer tous les utilisateurs
    public static function getAllUsers($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $users = [];
        foreach ($usersData as $userData) {
            $users[] = new UserModel($userData);
        }
        
        return $users;
    }
    
    // Méthode pour mettre à jour le rôle d'un utilisateur
    public function updateRole($pdo, $newRole) {
        $stmt = $pdo->prepare("UPDATE utilisateur SET role = ? WHERE id = ?");
        return $stmt->execute([$newRole, $this->id]);
    }

    // Méthode pour compter le nombre d'utilisateurs actifs
    public static function countActiveUsers($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateur");
        return $stmt->fetchColumn();
    }

    // Recherche d'un utilisateur par email
    public static function getUserByEmail($pdo, $email) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchObject('User');
    }

    // Méthode pour mettre à jour le mot de passe
    public function updatePassword($pdo, $newPassword) {
        $stmt = $pdo->prepare("UPDATE utilisateur SET password = ? WHERE id = ?");
        return $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $this->id]);
    }
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO utilisateur (nom, prénom, email, password, role) VALUES (:nom, :prenom, :email, :password, :role)"
        );
        return $stmt->execute([
            'nom'      => $data['nom'],
            'prenom'   => $data['prenom'],
            'email'    => $data['email'],
            'password' => $data['password'], // mot de passe non haché
            'role'     => $data['role']
        ]);
        

    }
}

class AdminUser extends User {
    private $telephone;

    // Getter et Setter pour téléphone
    public function getTelephone() { return $this->telephone; }
    public function setTelephone($telephone) { $this->telephone = $telephone; }

    // Création d'un administrateur
    public static function createAdmin($pdo, $userData) {
        try {
            $pdo->beginTransaction();

            // Création de l'utilisateur
            $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, 'admin')");
            $stmt->execute([
                $userData['nom'],
                $userData['prenom'],
                $userData['email'],
                password_hash($userData['password'], PASSWORD_DEFAULT)
            ]);

            // Récupère l'id du dernier utilisateur inséré
            $lastId = $pdo->lastInsertId();

            // Création de l'entrée dans la table admin
            $stmt = $pdo->prepare("INSERT INTO admin (id, tel) VALUES (?, ?)");
            $stmt->execute([$lastId, $userData['tel']]);

            // Commit de la transaction
            $pdo->commit();
            return true;
        } catch (Exception $e) {
            // Si une erreur survient, rollback de la transaction
            $pdo->rollBack();
            return false;
        }
    }
    
}

?>
