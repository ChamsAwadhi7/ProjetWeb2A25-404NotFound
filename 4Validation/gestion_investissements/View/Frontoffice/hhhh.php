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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ressources Investissement #<?= htmlspecialchars($idInv) ?></title>
  <style>
    /* Reset et Paged Media */
    @page { margin: 0; }
    *, *::before, *::after { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; width: 100%; height: 100%; font-family: 'Inter', sans-serif; }

    :root {
      --primary: #2A5C82;
      --secondary: #F5F7FA;
      --white:     #FFFFFF;
      --text:      #1A1A1A;
      --border:    #e0e0e0;
      --highlight: #2E7D32;
    }

    body {
      background: var(--secondary);
      color: var(--text);
      padding: 20px;
    }

    /* Header full-bleed */
    .header {
      display: flex;
      align-items: center;
      background: var(--primary);
      color: var(--white);
      padding: 20px;
      width: 100vw;
      margin-left: calc(-50vw + 50%);
    }
    .header .logo { height: 40px; margin-right: 10px; }
    .header .title { font-size: 1.6rem; margin: 0; }

    .subtitle {
      margin: 20px 0 10px;
      font-size: 1.25rem;
      color: var(--primary);
      padding-bottom: 5px;
      border-bottom: 2px solid var(--primary);
    }

    /* Conteneur pour gérer arrondis et ombre */
    .table-container {
      background: var(--white);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      margin-top: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 0;
    }
    thead {
      background: var(--primary);
    }
    thead th {
      padding: 12px 16px;
      font-size: 0.9rem;
      text-transform: uppercase;
      color: var(--white);
      text-align: left;
    }
    tbody tr:nth-child(even) {
      background: var(--secondary);
    }
    tbody td {
      padding: 12px 16px;
      font-size: 0.85rem;
      border-top: 1px solid var(--border);
      vertical-align: top;
    }
    .montant {
      color: var(--highlight);
      font-weight: 500;
    }

    footer {
      margin-top: 20px;
      text-align: center;
      font-size: 0.8rem;
      color: #555;
    }
  </style>
</head>
<body>

  <div class="header">
    <img src="<?= realpath(__DIR__ . '/../Frontoffice/logo.png') ?>" class="logo" alt="Logo">
    <h1 class="title">Ressources de l’investissement #<?= htmlspecialchars($idInv) ?></h1>
  </div>

  <div class="subtitle">Liste des Ressources</div>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>ID Ressource</th>
          <th>Type</th>
          <th>Caractéristique</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($ressources)): ?>
          <tr>
            <td colspan="3" style="text-align:center; padding: 20px;">Aucune ressource</td>
          </tr>
        <?php else: foreach ($ressources as $r): ?>
          <tr>
            <td>#<?= htmlspecialchars($r['id_ressource'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($r['type_ressource'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= nl2br(htmlspecialchars($r['caracteristique'], ENT_QUOTES, 'UTF-8')) ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <footer>
    Généré le <?= date('d/m/Y \à H\hi') ?> | © <?= date('Y') ?> NEXT STEP. Tous droits réservés.
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
