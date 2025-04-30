    <?php
    // Fichier : updateRole.php

    // Inclure la base de données et modèle
    require_once '../config/Database.php';
    require_once '../Models/UserModel.php';

    // Connexion à la base
    $pdo = Database::getInstance()->getConnection();

    // Vérifier si la méthode est POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Méthode non autorisée
        echo "Méthode non autorisée.";
        exit;
    }

    // Vérifier que les données existent
    if (!isset($_POST['user_id'], $_POST['new_role'])) {
        http_response_code(400); // Mauvaise requête
        echo "Paramètres manquants.";
        exit;
    }

    $userId = intval($_POST['user_id']); // Sécuriser user_id
    $newRole = trim($_POST['new_role']); // Nettoyer new_role

   // Vérifier que le rôle est valide
$roles_valides = ['user', 'admin', 'investisseur', 'entrepreneur'];
if (!in_array($newRole, $roles_valides)) {
    http_response_code(400);
    echo "Rôle invalide.";
    exit;
}


    // Read telephone if provided
    $newTel = null;
    if (isset($_POST['tel'])) {
        $newTel = trim($_POST['tel']);
        // Optional: validate format, e.g. digits and “+”
        if ($newTel !== '' && !preg_match('/^[0-9+\-\s]+$/', $newTel)) {
            http_response_code(400);
            echo "Numéro de téléphone invalide.";
            exit;
        }
    }

    try {
        // 1) update the user role
        $stmt = $pdo->prepare(
        "UPDATE utilisateur SET role = :role WHERE id = :id"
        );
        $stmt->bindParam(':role', $newRole, PDO::PARAM_STR);
        $stmt->bindParam(':id',   $userId,  PDO::PARAM_INT);
        $stmt->execute();

        // 2) if admin and tel given, update admin.tel
        if ($newRole === 'admin' && $newTel !== null) {
            $stmt2 = $pdo->prepare(
            "INSERT INTO admin (id, tel) VALUES (:id, :tel)"      
            );
            $stmt2->bindParam(':tel', $newTel, PDO::PARAM_STR);
            $stmt2->bindParam(':id',  $userId, PDO::PARAM_INT);
            $stmt2->execute();
        }

       // 3) Si le rôle devient "user", "investisseur" ou "entrepreneur", supprimer le téléphone de la table admin
if (in_array($newRole, ['user', 'investisseur', 'entrepreneur'])) {
    // Supprimer l'enregistrement du téléphone dans la table admin
    $stmtDeleteTel = $pdo->prepare("DELETE FROM admin WHERE id = :id");
    $stmtDeleteTel->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmtDeleteTel->execute();
    }

        echo "Mise à jour effectuée avec succès.";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Erreur base de données : " . $e->getMessage();
    }
        