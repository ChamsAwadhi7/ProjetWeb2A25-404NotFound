<?php
// 1) Charger l’autoloader de Dompdf
require_once __DIR__ . '/../../dompdf/autoload.inc.php';
// 2) Charger votre config BDD
require_once __DIR__ . '/../../auth/config.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// 3) Connexion à la BDD
$db = config::getConnexion();

// 4) Récupération de l’ID de l’investissement
$idInv = isset($_GET['id_investissement'])
    ? (int) $_GET['id_investissement']
    : 0;

// 5) Récupérer les ressources liées
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

// 6) Construire le HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ressources Investissement #<?= htmlspecialchars($idInv) ?></title>
  <style>
    :root {
      --primary-color: #2A5C82;
      --secondary-color: #F5F7FA;
      --accent-color: #E4A11B;
    }
    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      margin: 40px;
      color: #1A1A1A;
    }
    .header {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 3px solid var(--primary-color);
    }
    .logo {
      height: 80px;
      margin-right: 25px;
    }
    .title {
      font-size: 24px;
      color: var(--primary-color);
      font-weight: 600;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      border-radius: 8px;
      overflow: hidden;
      margin-top: 20px;
    }
    th, td {
      padding: 12px 14px;
      text-align: left;
    }
    thead {
      background: var(--primary-color);
      color: white;
      font-weight: 600;
    }
    th {
      font-size: 12px;
      text-transform: uppercase;
    }
    tbody tr {
      border-bottom: 1px solid #EEEEEE;
    }
    tbody tr:nth-child(even) {
      background-color: var(--secondary-color);
    }
    tbody tr:hover {
      background-color: #E8F0FE;
    }
    footer {
      margin-top: 30px;
      padding-top: 15px;
      text-align: center;
      font-size: 11px;
      color: #666666;
      border-top: 2px solid var(--primary-color);
    }
  </style>
</head>
<body>
  <div class="header">
    <img src="http://localhost/gestion_investissements/View/Backoffice/logo.png"
         class="logo" alt="Logo Entreprise">
    <h1 class="title">Ressources de l’investissement #<?= htmlspecialchars($idInv) ?></h1>
  </div>

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

  <footer>
    Généré le <?= date('d/m/Y \à H\hi') ?> | © <?= date('Y') ?> Votre Entreprise. Tous droits réservés.
  </footer>
</body>
</html>
<?php
$html = ob_get_clean();

// 7) Configuration de Dompdf
$options = new Options();
$options->set([
    'isRemoteEnabled' => true,
    'chroot'          => realpath(__DIR__ . '/../../'),
    'tempDir'         => __DIR__ . '/../../tmp',
    'defaultFont'     => 'helvetica',
]);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 8) Forcer le téléchargement du PDF
$filename = sprintf(
    'ressources_inv_%d_%s.pdf',
    $idInv,
    date('Y-m-d')
);
$dompdf->stream($filename, ['Attachment' => 1]);
exit;
