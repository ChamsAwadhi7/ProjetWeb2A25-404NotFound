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
    <title>Rapport d'Investissements</title>
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
            font-size: 28px;
            color: var(--primary-color);
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 14px 16px;
            text-align: center;
        }
        thead {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
        }
        th {
            font-size: 13px;
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
        .montant {
            color: #2E7D32;
            font-weight: 500;
        }
        .footer {
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
        <img src="<?= realpath(__DIR__ . '/../Frontoffice/logo.png') ?>" class="logo" alt="Logo Entreprise">
        <h1 class="title">Rapport des Investissements</h1>
    </div>

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
                <td><?= $inv['id_investissement'] ?></td>
                <td>#<?= $inv['user_id'] ?></td>
                <td class="montant"><?= number_format($inv['montant_investissement'], 2) ?> DT</td>
                <td><?= date('d/m/Y', strtotime($inv['date'])) ?></td>
                <td><?= isset($inv['date_fin']) ? date('d/m/Y', strtotime($inv['date_fin'])) : '—' ?></td>
                <td><?= ucfirst($inv['type_investissement']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Généré le <?= date('d/m/Y \à H\hi') ?> | © <?= date('Y') ?> Votre Entreprise. Tous droits réservés.
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
