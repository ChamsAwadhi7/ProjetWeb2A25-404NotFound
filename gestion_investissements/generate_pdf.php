<?php
// 1) On inclut l’autoloader de Dompdf
require_once __DIR__ . '/dompdf/autoload.inc.php';

// 2) On inclut ta classe de gestion
require_once __DIR__ . '/InvestissementC.php';

use Dompdf\Dompdf;

// 3) Récupère les investissements
$investC = new InvestissementC();
$investissements = $investC->listInvestissements();

// 4) Génère le HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 12px;
      margin: 20px;
      color: #333;
    }
    h1 {
      text-align: center;
      font-size: 24px;
      color: #1976d2;
      margin-bottom: 30px;
      border-bottom: 2px solid #1976d2;
      padding-bottom: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #1976d2;
      color: white;
      text-transform: uppercase;
      font-size: 12px;
    }
    tbody tr:nth-child(odd) {
      background-color: #f9f9f9;
    }
    tbody tr:hover {
      background-color: #e3f2fd;
    }
    footer {
      margin-top: 40px;
      font-size: 10px;
      text-align: center;
      color: #aaa;
    }
  </style>
</head>
<body>

  <h1>Liste des Investissements</h1>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Montant (DT)</th>
        <th>Date Début</th>
        <th>Date Fin</th>
        <th>Type</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($investissements as $inv): ?>
      <tr>
        <td><?= htmlspecialchars($inv['id_investissement']) ?></td>
        <td><?= htmlspecialchars($inv['user_id']) ?></td>
        <td><?= number_format($inv['montant_investissement'], 2) ?></td>
        <td><?= htmlspecialchars($inv['date']) ?></td>
        <td><?= htmlspecialchars($inv['date_fin'] ?? '—') ?></td>
        <td><?= htmlspecialchars($inv['type_investissement']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <footer>
    Généré automatiquement le <?= date('d/m/Y H:i') ?>.
  </footer>

</body>
</html>

<?php
$html = ob_get_clean();

// 5) Configure et génère le PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 6) Envoie le PDF au navigateur (téléchargement)
$dompdf->stream(
    'investissements_' . date('Y-m-d') . '.pdf',
    ['Attachment' => true]
);
exit;
