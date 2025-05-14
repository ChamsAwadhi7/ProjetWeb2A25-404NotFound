<?php
require_once __DIR__ . '/../../../Controller/eventController.php';
require_once __DIR__ . '/../../../Controller/rejoindreController.php';
require_once __DIR__ . '/../../../config.php';

session_start();
if (!isset($_SESSION['utilisateur'])) {
    header('Location: login_register.php');
    exit;
}

$user = $_SESSION['utilisateur'];
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
$stmt->execute([$user['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Utilisateur introuvable.";
    exit();
}

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
  <title>Participants | NextStep</title>
  <link rel="website icon" type="PNG" href="../../../image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <style>
    :root {
      --primary: #1c355e;            /* Dark navy-blue from the globe background */
      --primary-light: #3f6cc9;      /* Medium blue, close to the arrow gradient base */
      --primary-dark: #0b2545;       /* Deep dark-blue, good for borders */

      --secondary: #5f7eae;          /* Muted blue-gray from the globe's side shading */
      --success: #50a8e0;            /* Light blue accent (used for dot accents or bars) */
      --danger: #f75c3c;             /* Slight reddish-orange variant, if needed */
      --warning: #f8961e;            /* Orange from the arrow tip */
      --info: #4cc9f0;               /* Bright blue (optional lighter detail color) */

      --light: #f8f9fa;
      --dark: #1a1a1a;               /* Very dark outline from the icon's stroke */
      --white: #ffffff;
      --gray: #adb5bd;               /* Light gray from the inner bars */
      --gray-light: #e9ecef;
      --gray-dark: #343a40;

      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-image: url('https://img.freepik.com/free-vector/blue-curve-abstract-background_53876-99568.jpg?t=st=1746964243~exp=1746967843~hmac=d297af522052c37e25caf8b5c8b7323d92dceebb7d5ab1735dfbc98beed942cc&w=826');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center center;
    }

    .container {
      display: grid;
      width: 96%;
      margin: 0 auto;
      gap: 1.8rem;
      grid-template-columns: 14rem auto 23rem;
    }

    /* ========== SIDEBAR ========== */
    aside {
      height: 110vh;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      border-radius: 15px;
      box-shadow: var(--card-shadow);
      position: relative;
      overflow: hidden;
    }

    aside .top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 1.4rem;
      padding: 0 1rem;
    }

    aside .logo {
      display: flex;
      align-items: center;
      gap: 0.8rem;
    }

    aside .logo img {
      width: 2.5rem;
      height: 2.5rem;
      transition: var(--transition);
    }

    aside .logo:hover img {
      transform: rotate(15deg);
    }

    aside .logo h2 {
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--white);
    }

    aside .logo h2 span {
      color: var(--danger);
    }

    aside .close {
      display: none;
    }

    /* ========== SIDEBAR MENU ========== */
    aside .sidebar {
      display: flex;
      flex-direction: column;
      height: 86vh;
      position: relative;
      top: 3rem;
    }

    aside .sidebar a {
      display: flex;
      align-items: center;
      color: var(--white);
      margin-left: 1rem;
      gap: 1rem;
      height: 3.7rem;
      position: relative;
      transition: var(--transition);
      text-decoration: none;
      opacity: 0.8;
    }

    aside .sidebar a span {
      font-size: 1.6rem;
      transition: var(--transition);
    }

    aside .sidebar a:hover {
      opacity: 1;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 10px 0 0 10px;
      margin-left: 0;
    }

    aside .sidebar a:hover span {
      margin-left: 0.5rem;
    }

    aside .sidebar a.active {
      opacity: 1;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 10px 0 0 10px;
      margin-left: 0;
    }

    aside .sidebar a.active::before {
      content: '';
      width: 6px;
      height: 100%;
      background: var(--danger);
      position: absolute;
      left: 0;
    }

    /* ========== MAIN ========== */
    main {
      margin-top: 1.4rem;
    }

    main h1 {
      color: var(--primary);
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    main .date {
      display: inline-block;
      background: var(--white);
      border-radius: 10px;
      margin-top: 1rem;
      padding: 0.5rem 1rem;
      box-shadow: var(--card-shadow);
    }

    main .date input {
      background: transparent;
      color: var(--dark);
      border: none;
      outline: none;
      font-weight: 500;
    }

    /* ========== INSIGHTS ========== */
    main .insights {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.6rem;
      margin-top: 1rem;
    }

    main .insights > div {
      background: var(--white);
      padding: 1.8rem;
      border-radius: 15px;
      box-shadow: var(--card-shadow);
      transition: var(--transition);
    }

    main .insights > div:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    main .insights > div span {
      background: var(--primary);
      color: var(--white);
      padding: 0.5rem;
      border-radius: 50%;
      font-size: 2rem;
    }

    main .insights > div.sales span {
      background: linear-gradient(135deg, var(--primary), var(--primary-light));
    }

    main .insights > div.expenses span {
      background: linear-gradient(135deg, var(--info), var(--success));
    }

    main .insights > div.income span {
      background: linear-gradient(135deg, var(--danger), var(--warning));
    }

    main .insights > div .middle {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    main .insights > div .left h3 {
      margin: 1rem 0 0.6rem;
      font-size: 1rem;
      color: var(--gray);
    }

    main .insights > div .left h1 {
      font-size: 1.8rem;
      color: var(--dark);
      margin: 0;
    }

    main .insights .progress {
      position: relative;
      width: 92px;
      height: 92px;
      border-radius: 50%;
    }

    main .insights svg {
      width: 7rem;
      height: 7rem;
    }

    main .insights svg circle {
      fill: none;
      stroke: var(--primary-light);
      stroke-width: 10;
      stroke-linecap: round;
      transform: translate(5px, 5px);
      stroke-dasharray: 190;
      stroke-dashoffset: 50;
    }

    main .insights .sales svg circle {
      stroke-dashoffset: 30;
      stroke: var(--primary);
    }

    main .insights .expenses svg circle {
      stroke-dashoffset: 20;
      stroke: var(--info);
    }

    main .insights .income svg circle {
      stroke-dashoffset: 35;
      stroke: var(--danger);
    }

    main .insights .progress .number {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-weight: 600;
      color: var(--primary);
    }

    main .insights small {
      display: block;
      margin-top: 1.3rem;
      color: var(--gray);
    }

    /* ========== RECENT ORDERS ========== */
    main .recent_order {
      margin-top: 2rem;
      background: var(--white);
      border-radius: 15px;
      padding: 1.8rem;
      box-shadow: var(--card-shadow);
    }

    main .recent_order h2 {
      color: var(--primary);
      margin-bottom: 1rem;
    }

    main .recent_order table {
      width: 100%;
      border-collapse: collapse;
    }

    main .recent_order table thead tr {
      background: var(--primary-light);
      color: var(--white);
    }

    main .recent_order table thead th {
      padding: 0.8rem;
      text-align: left;
    }

    main .recent_order table tbody tr {
      border-bottom: 1px solid var(--gray-light);
      transition: var(--transition);
    }

    main .recent_order table tbody tr:hover {
      background: rgba(67, 97, 238, 0.1);
    }

    main .recent_order table tbody td {
      padding: 0.8rem;
    }

    main .recent_order table tbody td.warning {
      color: var(--warning);
    }

    main .recent_order table tbody td.primary {
      color: var(--primary);
      font-weight: 500;
      cursor: pointer;
    }

    main .recent_order a {
      display: inline-block;
      margin-top: 1rem;
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
    }

    main .recent_order a:hover {
      text-decoration: underline;
    }

    /* ========== RIGHT ========== */
    .right {
      margin-top: 1.4rem;
    }

    .right .top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: var(--white);
      padding: 1rem;
      border-radius: 15px;
      box-shadow: var(--card-shadow);
      margin-bottom: 1rem;
    }

    .right .top button {
      display: none;
    }

    .right .theme-toggler {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      background: var(--gray-light);
      padding: 0.3rem 0.5rem;
      border-radius: 20px;
      cursor: pointer;
    }

    .right .theme-toggler span {
      font-size: 1.2rem;
      width: 1.5rem;
      height: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: var(--transition);
    }

    .right .theme-toggler span.active {
      background: var(--primary);
      color: var(--white);
    }

    .right .profile {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .right .profile .info {
      text-align: right;
    }

    .right .profile .info p {
      font-weight: 500;
      color: var(--dark);
    }

    .right .profile .info small {
      color: var(--gray);
    }

    .right .profile-photo {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      overflow: hidden;
    }

    .right .profile-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* ========== RECENT UPDATES ========== */
    .right .recent_updates {
      background: var(--white);
      border-radius: 15px;
      padding: 1.8rem;
      box-shadow: var(--card-shadow);
      margin-bottom: 1rem;
    }

    .right .recent_updates h2 {
      color: var(--primary);
      margin-bottom: 1rem;
      font-size: 1.2rem;
    }

    .right .updates {
      max-height: 300px;
      overflow-y: auto;
    }

    .right .updates::-webkit-scrollbar {
      width: 5px;
    }

    .right .updates::-webkit-scrollbar-track {
      background: var(--gray-light);
    }

    .right .updates::-webkit-scrollbar-thumb {
      background: var(--primary);
      border-radius: 10px;
    }

    .right .update {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--gray-light);
    }

    .right .update:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }

    .right .update .profile-photo {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      overflow: hidden;
      flex-shrink: 0;
    }

    .right .update .profile-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .right .update .message p {
      margin-bottom: 0.3rem;
      color: var(--dark);
    }

    .right .update .message p em {
      color: var(--gray);
      font-size: 0.9rem;
    }

    /* ========== SALES ANALYTICS ========== */
    .right .sales-analytics {
      background: var(--white);
      border-radius: 15px;
      padding: 1.8rem;
      box-shadow: var(--card-shadow);
    }

    .right .sales-analytics h2 {
      color: var(--primary);
      margin-bottom: 1rem;
      font-size: 1.2rem;
    }

    .right .sales-analytics .item {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
      padding: 1rem;
      border-radius: 10px;
      background: var(--gray-light);
      transition: var(--transition);
    }

    .right .sales-analytics .item:hover {
      background: rgba(67, 97, 238, 0.1);
    }

    .right .sales-analytics .item .icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      background: var(--primary);
      color: var(--white);
      font-size: 1.2rem;
    }

    .right .sales-analytics .item .right_text {
      flex: 1;
    }

    .right .sales-analytics .item .right_text .info h3 {
      font-size: 1rem;
      color: var(--dark);
    }

    .right .sales-analytics .item .right_text .info small {
      color: var(--gray);
      font-size: 0.8rem;
    }

    .right .sales-analytics .item .right_text h5 {
      font-size: 0.9rem;
      margin-top: 0.3rem;
    }

    .right .sales-analytics .item .right_text h5.danger {
      color: var(--danger);
    }

    .right .sales-analytics .item .right_text h5.success {
      color: var(--success);
    }

    .right .sales-analytics .item .right_text h3 {
      font-size: 1.2rem;
      color: var(--dark);
    }

    .right .sales-analytics .item.add_product {
      background: transparent;
      border: 2px dashed var(--primary);
      color: var(--primary);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: var(--transition);
    }

    .right .sales-analytics .item.add_product:hover {
      background: rgba(67, 97, 238, 0.1);
    }

    .right .sales-analytics .item.add_product div {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .right .sales-analytics .item.add_product span {
      font-size: 1.5rem;
    }

    /* ========== MEDIA QUERIES ========== */
    @media screen and (max-width: 1200px) {
      .container {
        width: 94%;
        grid-template-columns: 7rem auto 23rem;
      }

      aside .logo h2 {
        display: none;
      }

      aside .sidebar h3 {
        display: none;
      }

      aside .sidebar a {
        width: 5.6rem;
      }

      main .insights {
        grid-template-columns: 1fr;
        gap: 0;
      }

      main .recent_order {
        width: 94%;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        margin: 2rem 0 0 8.8rem;
      }
    }

    @media screen and (max-width: 768px) {
      .container {
        width: 100%;
        grid-template-columns: 1fr;
      }

      aside {
        position: fixed;
        left: -100%;
        width: 18rem;
        z-index: 3;
        height: 100vh;
        transition: all 0.3s ease;
      }

      aside .logo h2 {
        display: inline;
      }

      aside .sidebar h3 {
        display: inline;
      }

      aside .sidebar a {
        width: 100%;
        height: 3.4rem;
      }

      aside .close {
        display: inline-block;
        cursor: pointer;
      }

      main {
        margin-top: 8rem;
        padding: 0 1rem;
      }

      main .recent_order {
        position: relative;
        margin: 3rem 0 0 0;
        width: 100%;
      }

      .right {
        width: 94%;
        margin: 0 auto 4rem;
      }

      .right .top {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        padding: 1rem;
        margin: 0;
        z-index: 2;
        border-radius: 0;
      }

      .right .top button {
        display: inline-block;
        background: transparent;
        color: var(--dark);
        cursor: pointer;
        position: absolute;
        left: 1rem;
      }

      .right .profile .info {
        display: none;
      }
    }

    /* Animation for the update blocks */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .recent_updates {
      animation: fadeIn 0.5s ease-out;
    }

    /* Custom styles for startup management */
    .items-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }
    
    .item-card {
      background: var(--white);
      border-radius: 8px;
      padding: 20px;
      box-shadow: var(--card-shadow);
      transition: var(--transition);
    }
    
    .item-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .item-card img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
      margin-bottom: 15px;
    }
    
    .item-actions {
      display: flex;
      gap: 10px;
      margin-top: 15px;
    }
    
    .btn {
      padding: 8px 12px;
      border-radius: 6px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: var(--transition);
      border: none;
      font-weight: 500;
    }
    
    .btn-edit {
      background: var(--primary);
      color: white;
    }
    
    .btn-edit:hover {
      background: var(--primary-dark);
    }
    
    .btn-delete {
      background: var(--danger);
      color: white;
    }
    
    .btn-delete:hover {
      background: #e04b2a;
    }
    
    .btn-add {
      background: var(--success);
      color: white;
      margin-bottom: 20px;
    }
    
    .btn-add:hover {
      background: #3e9ccf;
    }
    
    .btn-invest {
      background: var(--warning);
      color: white;
    }
    
    .btn-invest:hover {
      background: #e0871a;
    }
    
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 8px;
    }
    
    .alert.success {
      background-color: #dff0d8;
      color: #3c763d;
      border-left: 5px solid #3c763d;
    }
    
    .alert.error {
      background-color: #f2dede;
      color: #a94442;
      border-left: 5px solid #a94442;
    }
    
    .form {
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: var(--card-shadow);
      margin-top: 20px;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
      color: var(--primary);
    }
    
    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-family: inherit;
    }
    
    .form-group textarea {
      min-height: 100px;
      resize: vertical;
    }
    
    .controls {
      background: var(--white);
      padding: 15px;
      border-radius: 15px;
      box-shadow: var(--card-shadow);
      margin-bottom: 20px;
    }
    
    .search-box input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
    }
    
    .sort-dropdown select {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      background: white;
      cursor: pointer;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .items-container {
        grid-template-columns: 1fr;
      }
      
      .controls {
        flex-direction: column;
        gap: 10px;
      }
      
      .search-box, .sort-dropdown {
        width: 100%;
      }
    }
  </style>
</head>
<body>
   <?php if (isset($_SESSION['message'])): ?>
     <div class="alert success"><?= htmlspecialchars($_SESSION['message']) ?></div>
     <?php unset($_SESSION['message']); ?>
   <?php endif; ?>
   
   <?php if (isset($_SESSION['error'])): ?>
     <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
     <?php unset($_SESSION['error']); ?>
   <?php endif; ?>
   
   <div class="container">
    <!-- === Sidebar === -->
    <aside> 
      <div class="top">
        <div class="logo">
          <h2>
            <a href="http://localhost/4Validation/View/index.php" style="text-decoration: none; color: inherit;">
              <img style="width: 60px; height: 60px;" src="../../image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png" alt="">
              <span class="logo-text">NextStep</span>
              <style>
                .logo-text {
                  color: #FF6B35; /* Vibrant orange */
                  font-weight: 700;
                  font-size: 1.5rem;
                  position: relative;
                  display: inline-block;
                  text-transform: uppercase;
                  letter-spacing: 1px;
                  padding-bottom: 5px;
                  background: linear-gradient(90deg, #FF6B35 0%, #004E89 50%, #FFFFFF 75%, #7F7F7F 100%);
                  -webkit-background-clip: text;
                  background-clip: text;
                  -webkit-text-fill-color: transparent;
                  transition: all 0.3s ease;
                }

                .logo-text::after {
                  content: '';
                  position: absolute;
                  bottom: 0;
                  left: 0;
                  width: 100%;
                  height: 3px;
                  background: linear-gradient(90deg, #FF6B35 0%, #004E89 50%, #FFFFFF 75%, #7F7F7F 100%);
                  transform: scaleX(0);
                  transform-origin: right;
                  transition: transform 0.4s ease;
                  border-radius: 3px;
                }

                .logo-text:hover::after {
                  transform: scaleX(1);
                  transform-origin: left;
                }

                .logo-text:hover {
                  text-shadow: 0 2px 8px rgba(255, 107, 53, 0.3);
                  transform: translateY(-2px);
                }
              </style>
            </a>
          </h2>
        </div>
        <div class="close" id="close_btn">
          <span class="material-symbols-sharp">close</span>
        </div>
      </div>
      <!-- end top -->
      <div class="sidebar">
        <a href="http://localhost/4Validation/View/Back.php" >
          <span class="material-symbols-sharp">grid_view</span>
          <h3>Home</h3>
        </a>
        <a href="http://localhost/4Validation/View/USER/BackOffice/administration.php">
          <span class="material-symbols-sharp">person_outline</span>
          <h3>Customers</h3>
        </a>
        <a href="http://localhost/4Validation/View/FinANCE/FinanceB.php">
          <span class="material-symbols-sharp">credit_card</span>
          <h3>Finance</h3>
        </a>
        <a href="http://localhost/4Validation/View/STARTUP/BackOffice/startup.php">
          <span class="material-symbols-sharp">business</span>
          <h3>Startups</h3>
        </a>
        <a href="http://localhost/4Validation/View/STARTUP/BackOffice/incubator.php">
          <span class="material-symbols-sharp">rocket_launch</span>
          <h3>Incubators</h3>
        </a>
        <a href="http://localhost/4Validation/View/COURS/cours.php">
          <span class="material-symbols-sharp">menu_book</span>
          <h3>Courses</h3>
        </a>
        <a href="http://localhost/4Validation/View/EVENT/BackOffice/Event.php">
          <span class="material-symbols-sharp">receipt_long</span>
          <h3>Events</h3>
        </a>
        <a href="http://localhost/4Validation/View/EVENT/BackOffice/participants.php"class="active">
          <span class="material-symbols-sharp">rocket_launch</span>
          <h3>Participants</h3>
        </a>
        <a href="http://localhost/4Validation/View/Formation/BackOffice/formations.php">
          <span class="material-symbols-sharp">co_present</span>
          <h3>Formation</h3>
        </a>
        <a href="http://localhost/4Validation/View/Formation/BackOffice/participations.php">
          <span class="material-symbols-sharp">rocket_launch</span>
          <h3>Participation</h3>
        </a>
        <a href="../settings.php">
          <span class="material-symbols-sharp">settings</span>
          <h3>Settings</h3>
        </a>
        <a href="#">
          <span class="material-symbols-sharp">logout</span>
          <h3>Logout</h3>
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
  Participation Statistics by Event
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
  Management of Participants at Events
</h1>
    
    <form method="POST" style="text-align: right; margin-bottom: 20px;">
        <center>
        <button type="submit" name="export_pdf" class="boton btn-confirm">Export PDF</button>
        </center>
    </form>
    <style>
        .boton.btn-confirm {
  background-color: #003366; /* Bleu foncé */
  color: white; /* Couleur du texte */
  padding: 15px 30px; /* Augmente la taille du bouton */
  font-size: 18px; /* Taille de la police */
  border: none; /* Supprime la bordure */
  border-radius: 5px; /* Coins arrondis */
  cursor: pointer; /* Curseur pointer au survol */
  transition: background-color 0.3s ease; /* Animation lors du survol */
}

.boton.btn-confirm:hover {
  background-color: #002244; /* Bleu encore plus foncé au survol */
}
    </style>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'status_updated'): ?>
        <div class="alertt alert-success">✅ Statut de participation mis à jour avec succès.</div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alertt alert-danger">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($participations)): ?>
        <p class="no-data">No participation recorded at the moment.</p>
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
     <!-- HTML avec données dynamiques -->
<div class="profile">
   <div class="info">
       <p><b><?= htmlspecialchars($user['prénom']) ?></b></p>
       <p>Admin</p>
       <small class="text-muted"></small>
   </div>
   <div class="profile-photo">
     <img src="<?= !empty($user['face_image_path']) ? htmlspecialchars($user['face_image_path']) : 'images/default-profile.jpg' ?>" alt=""/>

   </div>
</div>
</div>

<br><br>
<div class="recent_updates">
<center>
  <div class="section-title" style="display: flex; align-items: center; gap: 0.5rem; justify-content: center; color: var(--clr-dark); margin-bottom: 1rem;">
    <span class="material-symbols-sharp" style="background-color: var(--clr-primary); color: #fff; padding: 0.4rem; border-radius: 50%; font-size: 1.5rem;">history</span>
    <h2 style="margin: 0;">History of Participation</h2>
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