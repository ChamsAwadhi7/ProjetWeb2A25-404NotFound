<?php
session_start();
require_once __DIR__ . '/../../config.php'; // Connexion PDO à la BDD
require_once __DIR__ . '/../../Model/users.php'; // Classe User

$action = $_REQUEST['action'] ?? null;

try {
    // Supprimer un utilisateur
  function deleteUser(PDO $pdo, int $id): bool {
    try {
        // Démarrer la transaction
        $pdo->beginTransaction();

        // Préparer la requête
        $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = :id");

        // Exécuter avec paramètre nommé
        $success = $stmt->execute([':id' => $id]);

        // Valider la transaction
        $pdo->commit();

        return $success;
    } catch (Exception $e) {
        // Annuler en cas d'erreur
        $pdo->rollBack();

        // Log l'erreur dans les logs serveurs
        error_log("Erreur suppression utilisateur: " . $e->getMessage());

        return false;
    }
}

function countActiveUsers($pdo) {
    $sql = "SELECT COUNT(*) FROM utilisateur";
    $stmt = $pdo->query($sql);
    return $stmt->fetchColumn();
}
    // Compter les utilisateurs
    function countActiveUsers(PDO $pdo): int {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateur");
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des utilisateurs actifs: " . $e->getMessage());
            return 0;
        }
    }

    switch ($action) {

        case 'add_admin':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Méthode non autorisée');
            }

            $required = ['lastname', 'firstname', 'email', 'phone', 'password'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Le champ $field est requis.");
                }
            }

            $user = new User([
                'nom' => $_POST['lastname'],
                'prénom' => $_POST['firstname'],
                'email' => $_POST['email'],
                'tel' => $_POST['phone'],
                'role' => 'admin'
            ]);
            $user->setPassword($_POST['password']);

            $stmt = $pdo->prepare("INSERT INTO utilisateur 
                (nom, prénom, email, tel, password, role) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $user->getNom(),
                $user->getPrénom(),
                $user->getEmail(),
                $user->getTel(),
                $user->getPassword(),
                $user->getRole()
            ]);

            header('Location: ../BackOffice/administration.php?success=add');
            exit;

        case 'update_admin':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
                throw new Exception('Données manquantes pour mise à jour');
            }

            $fields = [];
            foreach (['nom' => 'lastname', 'prénom' => 'firstname', 'email' => 'email', 'tel' => 'phone', 'password' => 'password', 'role' => 'role'] as $col => $param) {
                if (!empty($_POST[$param])) {
                    $fields[$col] = $_POST[$param];
                }
            }

            if (empty($fields)) {
                throw new Exception('Aucun champ valide à mettre à jour.');
            }

            $setParts = [];
            $values = [];
            foreach ($fields as $col => $val) {
                $setParts[] = "`$col` = ?";
                $values[] = $val;
            }

            $values[] = (int)$_POST['id'];
            $sql = "UPDATE utilisateur SET " . implode(', ', $setParts) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            header('Location: ../BackOffice/administration.php?success=update');
            exit;

        case 'update_info':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
                header('Location: ../FrontOffice/login_register.php');
                exit;
            }

            $user = new User([
                'nom' => $_POST['nom'] ?? '',
                'prénom' => $_POST['prenom'] ?? '',
                'email' => $_POST['email'] ?? '',
                'tel' => $_POST['tel'] ?? ''
            ]);

            $sql = "UPDATE utilisateur SET nom = ?, prénom = ?, email = ?, tel = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute([
                $user->getNom(),
                $user->getPrénom(),
                $user->getEmail(),
                $user->getTel(),
                $_SESSION['user_id']
            ]);

            if ($success) {
                header('Location: profil.php?update=success');
                exit;
            } else {
                throw new Exception("Erreur lors de la mise à jour");
            }

        case 'delete_user':
            if (!isset($_GET['id'])) {
                throw new Exception('ID utilisateur manquant');
            }

            if (deleteUser($pdo, (int)$_GET['id'])) {
                header('Location: ../BackOffice/administration.php?success=delete');
                exit;
            } else {
                throw new Exception("Erreur lors de la suppression de l'utilisateur");
            }

        default:
            throw new Exception("Action non reconnue.");
    }

} catch (Exception $e) {
    error_log("Erreur : " . $e->getMessage());
    header('Location: ../BackOffice/administration.php?error=' . urlencode($e->getMessage()));
    exit;
}
