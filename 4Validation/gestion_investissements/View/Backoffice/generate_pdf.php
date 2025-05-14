<?php
require_once __DIR__ . '/../../dompdf/autoload.inc.php';
require_once __DIR__ . '/../../Controller/InvestissementC.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Récupération des investissements
$investC = new InvestissementC();
$investissements = $investC->listInvestissements();

// Capture du HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport d'Investissements</title>
    <style>
        /* CSS Paged Media pour numérotation moderne */
        @page {
            margin: 0;
            @bottom-center { content: ""; }
        }
        html, body { margin: 0; padding: 0; width: 100%; height: 100%; }
        body { counter-reset: page; }

        :root {
            --primary-color: #2A5C82;
            --secondary-color: #F5F7FA;
            --accent-color: #E4A11B;
            --text-dark: #1A1A1A;
            --text-light: #FFFFFF;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-dark);
            margin: 0;
        }
        .header {
            display: flex;
            align-items: center;
            padding: 20px;
            background: var(--primary-color);
            color: var(--text-light);
        }
        .logo { height: 40px; margin-right: 10px; }
        .title { font-size: 1.5rem; font-weight: 600; margin: 0; }
        .subtitle {
            padding: 10px 20px;
            font-size: 1.25rem;
            color: #333;
        }
        .report-card {
            background: var(--text-light);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin: 0 20px 20px;
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: var(--primary-color);
        }
        thead th {
            padding: 12px 16px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-light);
            text-align: center;
        }
        tbody tr:nth-child(even) { background: var(--secondary-color); }
        tbody tr:nth-child(odd)  { background: var(--text-light); }
        tbody td {
            padding: 12px 16px;
            font-size: 0.85rem;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }
        .montant {
            color: #2E7D32;
            font-weight: 500;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 8px 20px;
            background: var(--primary-color);
            color: var(--text-light);
            font-size: 0.8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer .pagination::before {
            content: "Page " counter(page) " / " counter(pages);
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="<?= realpath(__DIR__ . '/../Frontoffice/logo.png') ?>" alt="NEXT STEP" class="logo">
        <h1 class="title">NEXT STEP</h1>
    </div>
    <div class="subtitle">Rapport des Investissements</div>

    <div class="report-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Montant</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($investissements as $inv): ?>
                <tr>
                    <td><?= htmlspecialchars($inv['id_investissement'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>#<?= htmlspecialchars($inv['user_id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="montant"><?= number_format($inv['montant_investissement'], 2, ',', ' ') ?> DT</td>
                    <td><?= date('d/m/Y', strtotime($inv['date'])) ?></td>
                    <td><?= isset($inv['date_fin']) ? date('d/m/Y', strtotime($inv['date_fin'])) : '—' ?></td>
                    <td><?= ucfirst(htmlspecialchars($inv['type_investissement'], ENT_QUOTES, 'UTF-8')) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div>Généré le <?= date('d/m/Y \à H\hi') ?> | © <?= date('Y') ?> NEXT STEP.</div>
        <div class="pagination"></div>
    </div>
</body>
</html>





<?php
// Récupération du HTML
$html = ob_get_clean();

// Configuration de Dompdf
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

// Forcer le téléchargement automatique
$dompdf->stream('rapport-investissements.pdf', [
    'Attachment' => 1  // 1 = téléchargement, 0 = affichage inline
]);

exit;
