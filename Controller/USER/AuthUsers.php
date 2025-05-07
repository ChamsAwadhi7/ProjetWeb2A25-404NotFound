<?php
session_start();
require_once __DIR__.'/../config/Database.php';
require_once __DIR__.'/../Models/Users.php';
require_once 'FaceRecognitionService.php';

// Initialisation de la connexion à la base de données
$database = Database::getInstance();
$pdo = $database->getConnection();

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    try {
        // Validation des données
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            throw new Exception("Tous les champs sont requis");
        }

        // Recherche de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            throw new Exception("Identifiants incorrects");
        }

        // Vérification du mot de passe
        if (!password_verify($password, $userData['password'])) {
            throw new Exception("Identifiants incorrects");
        }

        // Création de l'objet User
        $user = new User($userData);
        
        // Mise à jour de la session
        $_SESSION['user'] = serialize($user);
        
        // Redirection
        header("Location: ../view/FrontOffice/dashboard.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['login_error'] = $e->getMessage();
        header("Location: ../View/FrontOffice/login_register.php");
        exit();
    }
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    try {
        // Validation des données
        $requiredFields = ['nom', 'prenom', 'email', 'password',  'tel', 'role'];
        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $errors[] = "Le champ " . ucfirst($field) . " est requis";
            }
        }

        

       

        // Vérification de l'unicité de l'email
        $stmt = $pdo->prepare("SELECT id FROM utilisateur WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        if ($stmt->fetch()) {
            throw new Exception("Un compte existe déjà avec cet email");
        }

        // Création du nouvel utilisateur
        $user = new User([
            'nom' => htmlspecialchars($_POST['nom']),
            'prénom' => htmlspecialchars($_POST['prenom']),
            'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            'tel' => htmlspecialchars($_POST['tel']),
            'role' => $_POST['role']
        ]);

        // Gestion du mot de passe
        $user->setPassword($_POST['password']);

        // Gestion de la reconnaissance faciale
        if (!empty($_POST['photo_data'])) {
            try {
                $faceService = new FaceRecognitionService();
                $descriptor = $faceService->processFace($_POST['photo_data']);
                
                if (!$descriptor) {
                    throw new Exception("Échec de la reconnaissance faciale");
                }
                
                // Formatage pour la classe User
                $user->setFaceDescriptor((string)$descriptor['descriptor']);
                
            } catch (Exception $e) {
                // Gérer l'erreur sans bloquer l'inscription
                error_log("Erreur reconnaissance faciale: " . $e->getMessage());
            }
        }

        // Insertion en base de données
        $stmt = $pdo->prepare("INSERT INTO utilisateur 
            (nom, prénom, email, tel, password, role, date_inscription, face_image_path) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");

        $success = $stmt->execute([
            $user->getNom(),
            $user->getPrénom(),
            $user->getEmail(),
            $user->getTel(),
            $user->getPassword(),
            $user->getRole(),
            $user->getFaceDescriptor() ?? null
        ]);

        if (!$success) {
            throw new Exception("Erreur lors de la création du compte");
        }

        // Mise à jour de l'ID et session
        $user->setId($pdo->lastInsertId());
        $_SESSION['user'] = serialize($user);
        
        // Redirection
        header("Location: ../view/FrontOffice/dashboard.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['register_errors'] = explode("<br>", $e->getMessage());
        header("Location: ../View/FrontOffice/login_register.php");
        exit();
    }
}

// Redirection par défaut si aucune action valide
header("Location: ../View/FrontOffice/login_register.php");
exit();