<?php
// 1) Autoloader de Dompdf
require_once __DIR__ . '/dompdf/autoload.inc.php';

// 2) Connexion et récupération des données
include_once __DIR__ . '/auth/config.php';
$db = config::getConnexion();

// Récupération de l’ID de l’investissement
$idInv = isset($_GET['id_investissement']) ? (int)$_GET['id_investissement'] : 0;

// Requête : ressources liées à cet investissement
$stmt = $db->prepare("
  SELECT r.id_ressource, r.type_ressource, r.caracteristique
  FROM ressource r
  JOIN investissement_ressource ir 
    ON r.id_ressource = ir.id_ressource
  WHERE ir.id_investissement = :inv
  ORDER BY r.id_ressource
");
$stmt->execute([':inv' => $idInv]);
$ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3) Générer le HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: sans-serif; font-size:12px; margin:20px; }
    h1 { color:#1976d2; text-align:center; margin-bottom:20px; }
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #ccc; padding:8px; text-align:left; }
    th { background:#1976d2; color:#fff; }
    tr:nth-child(odd) td { background:#f9f9f9; }
    footer { text-align:center; font-size:10px; margin-top:30px; color:#666; }
  </style>
</head>
<body>
  <h1>Ressources de l’investissement #<?= htmlspecialchars($idInv) ?></h1>
  <table>
    <thead>
      <tr>
        <th>ID ressource</th>
        <th>Type</th>
        <th>Caractéristique</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($ressources)): ?>
        <tr><td colspan="3" style="text-align:center">Aucune ressource</td></tr>
      <?php else: foreach ($ressources as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['id_ressource']) ?></td>
          <td><?= htmlspecialchars($r['type_ressource']) ?></td>
          <td><?= htmlspecialchars($r['caracteristique']) ?></td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
  <footer>Généré le <?= date('d/m/Y H:i') ?></footer>
</body>
</html>
<?php
$html = ob_get_clean();

// 4) Générer le PDF
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 5) Envoyer au navigateur
$filename = "ressources_inv_{$idInv}_" . date('Y-m-d') . ".pdf";
$dompdf->stream($filename, ['Attachment' => true]);
exit;
