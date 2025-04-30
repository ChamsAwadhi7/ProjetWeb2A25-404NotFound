<?php
// Inclure le fichier config.php pour établir la connexion PDO
require_once('../config.php'); // Assurez-vous que le chemin est correct

// Inclure le modèle CoursContenu (le modèle de la base de données)
require_once('../Model/CoursContenu.php');

// Création de la classe CoursContenuC
class CoursContenuC {
    private $pdo;

    // Constructeur pour initialiser la connexion PDO
    public function __construct() {
        global $pdo; // Utiliser la variable PDO définie dans config.php
        if ($pdo) {
            $this->pdo = $pdo;
        } else {
            die("Erreur de connexion à la base de données.");
        }
    }

    // Méthode pour récupérer l'id d'un cours à partir de son nom
    public function recupererCoursIdParNom($cours_nom) {
        // Requête SQL pour récupérer l'id du cours à partir du nom
        $sql = "SELECT id FROM cours WHERE nomCours = :cours_nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':cours_nom', $cours_nom, PDO::PARAM_STR);
        $stmt->execute();

        // Vérifier si le cours existe
        if ($stmt->rowCount() > 0) {
            // Récupérer l'id du cours
            $cours = $stmt->fetch();
            return $cours['id'];
        } else {
            // Retourner null si aucun cours n'a été trouvé
            return null;
        }
    }

    // Méthode pour récupérer les contenus d'un cours à partir de l'id du cours
    public function recupererContenusCours($cours_id) {
        // Requête SQL pour récupérer tous les contenus d'un cours donné
        $sql = "SELECT * FROM contenucours WHERE cours_id = :cours_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':cours_id', $cours_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Récupérer tous les résultats sous forme de tableau
        return $stmt->fetchAll();
    }
}
?>
