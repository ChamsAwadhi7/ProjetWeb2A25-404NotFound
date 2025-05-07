<?php
require_once __DIR__ . '/../../Controller/eventController.php';
require_once __DIR__ . '/../../Controller/rejoindreController.php';
require_once __DIR__ . '/../../config.php';

$RejoindreController = new RejoindreController();
$participations = $RejoindreController->listParticipations();

// Traitement du changement de statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'confirmer') {
            $RejoindreController->updateStatus($_POST['id_participation'], 'confirmé');
            $RejoindreController->sendConfirmationEmailForConfirmedParticipation($_POST['id_participation']);
        } elseif ($_POST['action'] === 'annuler') {
            $RejoindreController->deleteParticipation($_POST['id_participation']);
        }
        header("Location: participants.php?success=updated");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
// Traitement export PDF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_pdf'])) {
    require_once __DIR__ . '/../../fpdf/fpdf.php';

    $events = [];
    foreach ($participations as $participation) {
        $events[$participation['nom_event']][] = $participation;
    }

    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Ajout du logo
    $logoPath = '../FrontOffice/View/image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png';

    if (file_exists($logoPath)) {
        $logoWidth = 30; // Largeur du logo
        $logoHeight = 0; // Hauteur auto (0 = proportionnel)
        $title = "Liste des Participants";
    
        // Calculer la largeur du titre
        $pdf->SetFont('Arial', 'B', 18);
        $titleWidth = $pdf->GetStringWidth($title) + 6; // Largeur du texte + marges
    
        // Positionner tout au centre de la page
        $pageWidth = $pdf->GetPageWidth();
        $totalWidth = $logoWidth + 5 + $titleWidth; // espace de 5px entre logo et titre
        $x = ($pageWidth - $totalWidth) / 2;
    
        // Positionner le logo
        $pdf->Image($logoPath, $x, 10, $logoWidth);
    
        // Positionner le titre
        $pdf->SetXY($x + $logoWidth + 5, 15); // Après le logo + petit espace
        $pdf->Cell($titleWidth, 10, $title, 0, 1, 'L'); // Aligné à gauche par rapport au bloc
    } else {
        die('Logo introuvable à : ' . $logoPath);
    }
    
    // Espace après le header
    $pdf->Ln(20);

    foreach ($events as $eventName => $participantsList) {
        // Titre de l'événement
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(33, 37, 41); // Gris foncé
        $pdf->Cell(0, 10, mb_convert_encoding("Pour l'Événement : " . $eventName, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

        $pdf->Ln(5);

        // En-têtes du tableau
        $pdf->SetFillColor(52, 152, 219); // Bleu clair
        $pdf->SetTextColor(255, 255, 255); // Blanc
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10,'Nom Participant', 1, 0, 'C', true);
        $pdf->Cell(60, 10,'Date Participation', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Statut', 1, 1, 'C', true);

        // Contenu des lignes
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0); // Retour au noir
        foreach ($participantsList as $participant) {
            $fullName = $participant['prenom_user'] . ' ' . $participant['nom_user'];
            $dateParticipation = date('d/m/Y H:i', strtotime($participant['date_participation']));
            $statut = ucfirst($participant['statut_participation']);

            $pdf->Cell(60, 10, utf8_decode($fullName), 1, 0, 'C');
            $pdf->Cell(60, 10, utf8_decode($dateParticipation), 1, 0, 'C');
            $pdf->Cell(60, 10, utf8_decode($statut), 1, 1, 'C');
        }

        $pdf->Ln(15); // Espace entre événements
    }

    // Sauvegarde dans le dossier "pdf"
    if (!file_exists('pdf')) {
        mkdir('pdf', 0777, true);
    }

    $pdfFilePath = 'pdf/participants_par_evenement1.pdf';
    $pdf->Output('F', $pdfFilePath);

    // Rediriger directement vers le PDF
    header('Location: ' . $pdfFilePath);
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Participations - NextStep</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="style.css">
  <style>

.con {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}



.boton {
    padding: 10px 20px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
}

.boton:hover {
    background: #45a049;
}

.alertt {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 10px;
    font-weight: bold;
}

.alert-success {
    background: #d4edda;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
}

.no-data {
    text-align: center;
    font-size: 18px;
    color: #888;
    margin-top: 20px;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.modern-table thead {
    background: #f1f1f1;
}

.modern-table th, .modern-table td {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

.modern-table tbody tr:hover {
    background: #f9f9f9;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    margin: 2px;
}

.btn-confirm {
    background-color: #4CAF50;
    color: white;
}

.btn-confirm:hover {
    background-color: #45a049;
}

.btn-cancel {
    background-color: #f44336;
    color: white;
}

.btn-cancel:hover {
    background-color: #e53935;
}

.status-confirmé {
    color: green;
    font-weight: bold;
}

.status-annulé {
    color: red;
    font-weight: bold;
}

.status-enattente {
    color: orange;
    font-weight: bold;
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 8px;
}

        
        .alertt {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .insightss {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    padding: 30px;
    margin-top: 40px;
}

.saless {
    background: #fff;
    border-radius: 25px; /* smaller rounding */
    padding: 15px;       /* reduced padding */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5); 
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}


.saless:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.saless span.material-symbols-sharp {
    font-size: 2.5rem;
    color: #4CAF50;
    background: #e8f5e9;
    border-radius: 50%;
    padding: 10px;
    margin-bottom: 15px;
}

.saless .middle {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}

.saless .left {
    text-align: left;
}

.saless .left h3 {
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 8px;
}

.saless .left h1 {
    font-size: 1.8rem;
    font-weight: bold;
    color: #111;
}

.saless .progress {
    position: relative;
    width: 80px;
    height: 80px;
}

.saless .progress svg {
    position: absolute;
    top: 0;
    left: 0;
}

.saless .progress circle:nth-child(1) {
    stroke: #eee;
}

.saless .progress circle:nth-child(2) {
    stroke: #4CAF50;
    transition: stroke-dasharray 0.6s ease;
}

.saless .number {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: bold;
    color: #333;
    font-size: 1rem;
}

.saless small {
    display: block;
    margin-top: 20px;
    color: #777;
    font-size: 0.9rem;
}



/* Status Colors */
.confirmé {
    color: green;
    font-weight: bold;
}

.annulé {
    color: red;
    font-weight: bold;
}

.enattente {
    color: orange;
    font-weight: bold;
}

.right-side {
  margin-top: 1.4rem;
}

.right-side h2 {
  color: var(--clr-dark);
}

.right-side .header {
  display: flex;
  justify-content: start;
  gap: 2rem;
}

.right-side .header button {
  display: none;
}

.right-side .theme-switcher {
  background: var(--clr-white);
  display: flex;
  justify-content: space-between;
  height: 1.6rem;
  width: 4.2rem;
  cursor: pointer;
  border-radius: var(--border-radius-1);
}

.right-side .theme-switcher span {
  font-size: 1.2rem;
  width: 50%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.right-side .theme-switcher span.active {
  background: var(--clr-primary);
  color: #fff;
}

.right-side .theme-switcher span.activee {
  background: var(--clr-primary);
  color: #fff;
}

.right-side .header .user-profile {
  display: flex;
  gap: 2rem;
  text-align: right;
}

.right-side .info h3 {
  color: var(--clr-dark);
}

.right-side .item h3 {
  color: var(--clr-dark);
}

/* participations history */

.right-side .participations-history {
  margin-top: 1rem;
  margin-left: -20px;
}

.right-side .participations-history .history-list {
  background-color: var(--clr-white);
  padding: var(--card-padding);
  border-radius: var(--card-border-radius);
  box-shadow: var(--box-shadow);
  transition: all .3s ease;
}

.right-side .participations-history .history-list:hover {
  box-shadow: none;
}

.right-side .participations-history .history-entry {
  display: grid;
  grid-template-columns: 2.6rem auto;
  gap: 1rem;
  margin-bottom: 1rem;
}


    </style>
</head>
<body>
   <div class="container">
      <aside>
           
         <div class="top">
           <div class="logo">
             <h2>C <span class="danger">NextStep</span> </h2>
           </div>
           <div class="close" id="close_btn">
            <span class="material-symbols-sharp">
              close
              </span>
           </div>
         </div>
         <!-- end top -->
          <div class="sidebar">
            <a href="#">
              <span class="material-symbols-sharp">grid_view </span>
              <h3>Dashbord</h3>
           </a>
           <a href="index.html">
              <span class="material-symbols-sharp">person_outline </span>
              <h3>custumers</h3>
           </a>
           <a href="Event.php" >
              <span class="material-symbols-sharp">receipt_long </span>
              <h3>Events</h3>
           </a>
           <a href="participants.php" class="active">
              <span class="material-symbols-sharp">group</span>
              <h3>Participants</h3>
           </a>
           <a href="#">
              <span class="material-symbols-sharp">logout </span>
              <h3>logout</h3>
           </a>
            <a href="#">
                <span class="material-symbols-sharp">settings</span>
                <h3>Settings</h3>
            </a>
          </div>
      </aside>
      <!-- --------------
        end asid
      -------------------- -->

      <!-- --------------
        start main part
      --------------- -->
      <?php
    $totalParticipants = count($participations);
    ?>
      <main>
      <?php
// Calcul des participations par événement
$eventParticipationCounts = [];

foreach ($participations as $participation) {
    $eventName = $participation['nom_event'];
    $participationDate = strtotime($participation['date_participation']); // Assure-toi que tu as la date de participation !

    // Vérifier que la participation est dans les dernières 24 heures
    if ($participationDate >= strtotime('-24 hours')) {
        if (!isset($eventParticipationCounts[$eventName])) {
            $eventParticipationCounts[$eventName] = 0;
        }
        $eventParticipationCounts[$eventName]++;
    }
}

// Calcul du total pour les pourcentages
$totalParticipationLast24h = array_sum($eventParticipationCounts);
?>
<br>
<h1>
  <span class="material-symbols-sharp" style="vertical-align: middle; font-size: 36px; color: #4CAF50;">stacked_line_chart</span>
  Statistiques de Participation par Événement
</h1>

<div class="insightss">
    <?php foreach ($eventParticipationCounts as $eventName => $count): 
        // Calcul du pourcentage
        $percentage = ($totalParticipationLast24h > 0) ? round(($count / $totalParticipationLast24h) * 100) : 0;
    ?>
    <div class="saless">
        <span class="material-symbols-sharp">trending_up</span> <!-- ou autre icône selon ton design -->
        <div class="middle">
            <div class="left">
                <h3><?= htmlspecialchars($eventName) ?></h3>
                <h1><?= htmlspecialchars($count) ?> Participants</h1>
            </div>
            <div class="progress">
                <svg width="80" height="80">
                    <circle r="30" cy="40" cx="40" style="fill: none; stroke: #eee; stroke-width: 5;"></circle>
                    <circle r="30" cy="40" cx="40" style="fill: none; stroke: #0f0; stroke-width: 5; stroke-dasharray: <?= (188 * $percentage / 100) ?> 188; stroke-dashoffset: 0; transform: rotate(-90deg); transform-origin: center;"></circle>
                </svg>
                <div class="number">
                    <p><?= $percentage ?>%</p>
                </div>
            </div>
        </div>
        <small>Last 24 Hours</small>
    </div>
    <?php endforeach; ?>
    <br><br>
    <hr>
</div>




<br><br>
<div class="con">
<h1 style="text-align: center;">
  <span class="material-symbols-sharp" style="vertical-align: middle; font-size: 36px; color: #4CAF50;">groups</span>
  Gestion des Participants aux Événements
</h1>
    
    <form method="POST" style="text-align: right; margin-bottom: 20px;">
        <button type="submit" name="export_pdf" class="boton btn-confirm">Exporter en PDF</button>
    </form>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'status_updated'): ?>
        <div class="alertt alert-success">✅ Statut de participation mis à jour avec succès.</div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alertt alert-danger">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($participations)): ?>
        <p class="no-data">Aucune participation enregistrée pour le moment.</p>
    <?php else: ?>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Événement</th>
                    <th>Participant</th>
                    <th>Date de participation</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($participations as $participation): ?>
                    <tr>
                        <td><?= htmlspecialchars($participation['nom_event']) ?></td>
                        <td><?= htmlspecialchars($participation['prenom_user'] . ' ' . $participation['nom_user']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($participation['date_participation'])) ?></td>
                        <td class="status-<?= str_replace(' ', '', $participation['statut_participation']) ?>">
                            <?= htmlspecialchars($participation['statut_participation']) ?>
                        </td>
                        <td class="action-buttons">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id_participation" value="<?= $participation['id_participation'] ?>">
                                <button type="submit" name="action" value="confirmer" class="btn btn-confirm" <?= $participation['statut_participation'] === 'confirmé' ? 'disabled' : '' ?>>
                                    Confirmer
                                </button>
                            </form>

                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id_participation" value="<?= $participation['id_participation'] ?>">
                                <button type="submit" name="action" value="annuler" class="btn btn-cancel"
                                    onclick="return confirm('Êtes-vous sûr d\'annuler cette participation ?')"
                                    <?= $participation['statut_participation'] === 'annulé' ? 'disabled' : '' ?>>
                                    Annuler
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

    
    <?php


// Regrouper les participations par événement
$eventParticipationCounts = [];

foreach ($participations as $participation) {
    $eventName = $participation['nom_event'];
    if (!isset($eventParticipationCounts[$eventName])) {
        $eventParticipationCounts[$eventName] = 0;
    }
    $eventParticipationCounts[$eventName]++;
}
?>


      </main>
      
      <div class="right">
      <div class="top">
   <button id="menu_bar">
     <span class="material-symbols-sharp">menu</span>
   </button>

   <div class="theme-toggler">
     <span class="material-symbols-sharp active">light_mode</span>
     <span class="material-symbols-sharp">dark_mode</span>
   </div>
    <div class="profile">
       <div class="info">
           <p><b>Ghribi Med</b></p>
           <p>Admin</p>
           <small class="text-muted"></small>
       </div>
       <div class="profile-photo">
         <img src="./images/profile-1.jpg" alt=""/>
       </div>
    </div>
</div>
<br><br>
<div class="recent_updates">
<center>
  <div class="section-title" style="display: flex; align-items: center; gap: 0.5rem; justify-content: center; color: var(--clr-dark); margin-bottom: 1rem;">
    <span class="material-symbols-sharp" style="background-color: var(--clr-primary); color: #fff; padding: 0.4rem; border-radius: 50%; font-size: 1.5rem;">history</span>
    <h2 style="margin: 0;">Historique des Participations</h2>
  </div>
</center>


  <div class="updates" id="updates">

    <?php foreach (array_slice($participations, 0, 3) as $participation): ?>
      <div class="update">
         <div class="profile-photo">
           <img src="./images/profile-1.jpg" alt="Profile" />
         </div>
         <div class="message">
           <p><b><?= htmlspecialchars($participation['prenom_user'] . ' ' . $participation['nom_user']) ?></b> a participé à <b><?= htmlspecialchars($participation['nom_event']) ?></b> le <?= date('d/m/Y H:i', strtotime($participation['date_participation'])) ?>.</p>
         </div>
      </div>
    <?php endforeach; ?>



  </div>

 
</div>
</div>


   <script src="script.js"></script>
</body>
</html>