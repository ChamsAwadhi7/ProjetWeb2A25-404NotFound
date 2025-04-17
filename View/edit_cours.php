<?php
require_once '../config.php';

if (!isset($_GET['id'])) {
    die("ID manquant.");
}

$id = intval($_GET['id']);

// Charger les données du cours
try {
    $stmt = $pdo->prepare("SELECT * FROM cours WHERE id = ?");
    $stmt->execute([$id]);
    $course = $stmt->fetch();
    if (!$course) {
        die("Cours introuvable.");
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Mise à jour
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = $_POST['courseName'] ?? '';
    $description = $_POST['courseDescription'] ?? '';
    $prix = $_POST['coursePrix'] ?? 0;

    try {
        $stmt = $pdo->prepare("UPDATE cours SET Titre = ?, Notes = ?, Prix = ? WHERE id = ?");
        $stmt->execute([$titre, $description, $prix, $id]);
        $message = "✅ Cours mis à jour avec succès.";
        header("Location: cours.php"); // retour vers liste
        exit();
    } catch (PDOException $e) {
        $message = "❌ Erreur de mise à jour : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditer le cours</title>
</head>
<body>
    <h1>Éditer le cours</h1>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="courseName">Nom :</label>
        <input type="text" name="courseName" id="courseName" value="<?= htmlspecialchars($course['Titre']) ?>" required>

        <label for="courseDescription">Description :</label>
        <textarea name="courseDescription" id="courseDescription" required><?= htmlspecialchars($course['Notes']) ?></textarea>

        <label for="coursePrix">Prix (dt) :</label>
        <input type="number" name="coursePrix" id="coursePrix" min="0" step="20" value="<?= htmlspecialchars($course['Prix']) ?>">

        <button type="submit">Mettre à jour</button>
    </form>

    <p><a href="cours.php">⬅ Retour à la liste</a></p>
</body>
</html>
