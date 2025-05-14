<?php
require_once __DIR__ . '/../../Controller/InvestissementStatsC.php';  // <-- point-virgule ajouté

// 2) Instancier la classe
$stats = new InvestissementStatsC();

// 3) Appeler une méthode pour récupérer les stats
$dataStats = $stats->getStatistics();


// 3) Récupérer les données
$total      = $stats->countAll();
$sum        = $stats->sumMontant();
$moyenne    = $stats->avgMontant();
$repart     = $stats->repartitionParType();
$top5       = $stats->topInvestisseurs(5);
$echeances  = $stats->prochesEcheances(15);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de bord des investissements</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    /* ===== Polices & couleurs ===== */
    body {
      font-family: 'Poppins', sans-serif;
      background: #f4f7fa;
      color: #333;
    }
    h1, h3, h5 {
      color: #2c3e50;
      font-weight: 600;
    }

    /* ===== Cartes statistiques ===== */
    .stat-card {
      background: #fff;
      border: none;
      border-radius: 1rem;
      box-shadow: 0 6px 18px rgba(0,0,0,0.07);
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0,0,0,0.10);
    }
    .stat-card h5 {
      font-size: 1rem;
      margin-bottom: .5rem;
    }
    .stat-card .h2 {
      font-size: 2.5rem;
      font-weight: 600;
      color: #007bff;
    }

    /* ===== Tableaux ===== */
    .table thead {
      background: #007bff;
      color: #fff;
      border: none;
    }
    .table-striped tbody tr:nth-of-type(odd) {
      background: rgba(0,123,255,0.05);
    }
    .table td, .table th {
      vertical-align: middle;
    }

    /* ===== Listes ===== */
    .list-group-item {
      border: none;
      border-radius: .5rem;
      margin-bottom: .5rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      transition: background 0.3s;
    }
    .list-group-item:hover {
      background: rgba(0,123,255,0.05);
    }

    /* ===== Espacements ===== */
    .section {
      margin-top: 3rem;
    }
  </style>
</head>
<body class="py-5">
  <div class="container">

    <h1 class="text-center mb-5">Tableau de bord des investissements</h1>

    <!-- Cartes résumé -->
    <div class="row gy-4">
      <div class="col-md-4">
        <div class="stat-card p-4 text-center">
          <h5>Total d’investissements</h5>
          <p class="h2"><?= $total ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card p-4 text-center">
          <h5>Montant total investi</h5>
          <p class="h2"><?= number_format($sum, 2, ',', ' ') ?> DT</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card p-4 text-center">
          <h5>Moyenne par investissement</h5>
          <p class="h2"><?= number_format($moyenne, 2, ',', ' ') ?> DT</p>
        </div>
      </div>
    </div>

    <!-- Répartition par type -->
    <div class="section">
      <h3 class="mb-4">Répartition par type</h3>
      <table class="table table-striped shadow-sm">
        <thead>
          <tr><th>Type</th><th>Nombre</th><th>%</th></tr>
        </thead>
        <tbody>
          <?php foreach ($repart as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['type']) ?></td>
              <td><?= $r['count'] ?></td>
              <td><?= $r['pct'] ?>%</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Top investisseurs -->
    <div class="section">
      <h3 class="mb-4">Top 5 investisseurs</h3>
      <ol class="list-group list-group-numbered shadow-sm">
        <?php foreach ($top5 as $t): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><?= htmlspecialchars($t['utilisateur']) ?></span>
            <span class="fw-bold"><?= number_format($t['total'], 2, ',', ' ') ?> DT</span>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>

    <!-- Prochaines échéances -->
    <div class="section">
      <h3 class="mb-4">Investissements proches échéance (15 jours)</h3>
      <ul class="list-group shadow-sm">
        <?php if (empty($echeances)): ?>
          <li class="list-group-item text-center">Aucune échéance à venir.</li>
        <?php else: ?>
          <?php foreach ($echeances as $e): ?>
            <li class="list-group-item d-flex justify-content-between">
              <div>
                <strong>ID <?= $e['id_investissement'] ?></strong><br>
                <?= htmlspecialchars($e['montant_investissement']) ?> DT
              </div>
              <span class="badge bg-primary rounded-pill">
                <?= date('d/m/Y', strtotime($e['date_fin'])) ?>
              </span>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
    <div class="mt-5 text-center">
  <a href="index.php" class="btn btn-info btn-lg">
    &larr; Retour au formulaire principal
  </a>
</div>


  </div>
</body>
</html>
