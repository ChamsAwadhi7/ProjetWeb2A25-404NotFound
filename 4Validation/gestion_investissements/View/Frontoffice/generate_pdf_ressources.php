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
    <div class="subtitle">Liste des Ressources</div>
    <div class="report-card">
        <table>
            <thead>
                <tr>
                <th>ID Ressource</th>
                <th>Type</th>
                <th>Caractéristique</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ressources as $r): ?>
                <tr>
                <td>#<?= htmlspecialchars($r['id_ressource'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($r['type_ressource'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= nl2br(htmlspecialchars($r['caracteristique'], ENT_QUOTES, 'UTF-8')) ?></td>
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
