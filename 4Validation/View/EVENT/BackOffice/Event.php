<?php
require_once __DIR__ . '/../../../Controller/eventController.php';
require_once __DIR__ . '/../../../Model/eventModel.php';
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

// Create database connection using your config
//$db = new PDO($dsn, $user, $pass, $options);
$eventC = new EventC($db);

// Initialisation des variables
$error = '';
$success = '';
$eventToEdit = null;
$events = [];

// Traitement des actions CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $event = new Event();
        $event->setNomEvent($_POST['nom'] ?? '');
        $event->setDateEvent($_POST['date'] ?? '');
        $event->setDescEvent($_POST['description'] ?? '');
        $event->setLieuEvent($_POST['lieu'] ?? '');
        
        // Gestion de l'image
        if (!empty($_FILES['image']['name'])) {
            // Vérification du type de fichier
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $_FILES['image']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Seuls les fichiers JPEG, PNG et GIF sont autorisés");
            }
            
            // Vérification de la taille du fichier (max 2MB)
            if ($_FILES['image']['size'] > 2097152) {
                throw new Exception("La taille de l'image ne doit pas dépasser 2MB");
            }
            
            // Nouveau code pour l'upload du fichier
            $uploadDir = 'uploads/events/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Crée le dossier avec permissions
            }
            
            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $event->setImgEvent($uploadPath); // Stocke le chemin relatif
            } else {
                throw new Exception("Erreur lors de l'enregistrement de l'image");
            }
        } elseif (isset($_POST['id']) && empty($_FILES['image']['name'])) {
            // Garder l'image existante lors de la mise à jour
            $existingEvent = $eventC->getEventById($_POST['id']);
            $event->setImgEvent($existingEvent['img_event']);
        } else {
            // Image par défaut si aucune image n'est fournie
            $event->setImgEvent('assets/default-event.jpg');
        }
        
        if ($_POST['action'] === 'update') {
            $event->setIdEvent($_POST['id']);
            $rowsAffected = $eventC->updateEvent($event, $_POST['id']);
            if ($rowsAffected > 0) {
                header("Location: Event.php?success=update");
                exit();
            } else {
                throw new Exception("Aucune modification effectuée");
            }
        } else {
            $event->setIdEvent(uniqid());
            $eventC->addEvent($event);
            header("Location: Event.php?success=add");
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Traitement des actions GET
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;
$searchTerm = $_GET['search'] ?? null;

if ($action === 'delete' && $id) {
    try {
        $rowsAffected = $eventC->deleteEvent($id);
        if ($rowsAffected > 0) {
            header("Location: Event.php?success=delete");
            exit();
        } else {
            throw new Exception("Événement non trouvé ou déjà supprimé");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Récupérer un événement pour modification
if ($action === 'edit' && $id) {
    $eventToEdit = $eventC->getEventById($id);
    if (!$eventToEdit) {
        $error = "Événement non trouvé";
    }
}

// Récupérer les événements (avec recherche si applicable)
$sortOrder = $_GET['sort'] ?? 'desc';  // Par défaut, tri DESC

try {
    if ($searchTerm) {
        if ($sortOrder === 'asc') {
            $events = $eventC->searchEventsAsc($searchTerm); // Tri ASC
        } else {
            $events = $eventC->searchEventsDesc($searchTerm); // Tri DESC
        }
    } else {
        if ($sortOrder === 'asc') {
            $events = $eventC->listEventsAsc(); // Tri ASC
        } else {
            $events = $eventC->listEvents(); // Tri DESC
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Message de succès
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'add': $success = "Événement ajouté avec succès"; break;
        case 'update': $success = "Événement mis à jour avec succès"; break;
        case 'delete': $success = "Événement supprimé avec succès"; break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Events | NextStep</title>
  <link rel="website icon" type="PNG" href="../../image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png">
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

    /* Custom styles for incubator management */
    .items-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }
    
    .item-card {
      background: var(--white);
      border-radius: var(--border-radius-2);
      padding: 20px;
      box-shadow: var(--box-shadow);
    }
    
    .item-actions {
      display: flex;
      gap: 10px;
      margin-top: 15px;
    }
    
    .btn {
      padding: 8px 12px;
      border-radius: var(--border-radius-1);
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    .btn-edit {
      background: var(--clr-primary);
      color: white;
    }
    
    .btn-delete {
      background: var(--clr-danger);
      color: white;
    }
    
    .btn-add {
      background: var(--clr-success);
      color: white;
      margin-bottom: 20px;
    }
    
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }
    
    .alert.success {
      background-color: #dff0d8;
      color: #3c763d;
    }
    
    .alert.error {
      background-color: #f2dede;
      color: #a94442;
    }
    
    .form {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      margin-top: 20px;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 5px;
    }
    
    .form-group input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    
    /* New styles for search, sort and stats */
    .search-sort-container {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      align-items: center;
    }
    
    .search-box {
      padding: 8px 15px;
      border-radius: 20px;
      border: 1px solid #ddd;
      width: 250px;
    }
    
    .sort-dropdown {
      padding: 8px;
      border-radius: 4px;
      border: 1px solid #ddd;
    }
    
    .stats-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }
    
    .stat-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      text-align: center;
    }
    
    .stat-value {
      font-size: 2rem;
      font-weight: bold;
      color: var(--clr-primary);
      margin: 10px 0;
    }
    
    .stat-label {
      color: var(--clr-dark-light);
    }
    
    .no-results {
      text-align: center;
      padding: 20px;
      color: var(--clr-dark-light);
      grid-column: 1 / -1;
    }
    
    .form-group {
      position: relative;
      margin-bottom: 20px;
    }
    
    .error {
      color: red;
      font-size: 0.8em;
      margin-top: 5px;
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
        <a href="http://localhost/4Validation/View/STARTUP/BackOffice/incubator.php" >
          <span class="material-symbols-sharp">rocket_launch</span>
          <h3>Incubators</h3>
        </a>
        <a href="http://localhost/4Validation/View/COURS/cours.php">
          <span class="material-symbols-sharp">menu_book</span>
          <h3>Courses</h3>
        </a>
        <a href="http://localhost/4Validation/View/EVENT/BackOffice/Event.php"class="active">
          <span class="material-symbols-sharp">receipt_long</span>
          <h3>Events</h3>
        </a>
        <a href="http://localhost/4Validation/View/EVENT/BackOffice/participants.php">
          <span class="material-symbols-sharp">rocket_launch</span>
          <h3>Participants</h3>
        </a>
        <a href="http://localhost/4Validation/View/Formation/BackOffice/formations.php">
          <span class="material-symbols-sharp">co_present</span>
          <h3>Formation</h3>
        </a>
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
      </aside>
      <main>
        
      <center>
  <div class="section-title" style="display: flex; align-items: center; justify-content: center; gap: 10px; color: var(--clr-dark);">
    <span class="material-symbols-sharp" style="background-color: var(--clr-primary); color: #fff; padding: 0.6rem; border-radius: 50%; font-size: 2rem;">event</span>
    <h1 style="margin: 0; font-size: 2.5rem; font-weight: bold;">Admin Dashboard - Events</h1>
  </div>
</center>
<style>.floating-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 350px;
    background: linear-gradient(135deg, #3a7bd5, #00d2ff);
    color: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    transform: translateX(400px);
    transition: transform 0.5s ease;
    z-index: 9999;
    max-height: 400px;
    overflow-y: auto;
}

.floating-notification.active {
    transform: translateX(0);
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.notification-header h3 {
    margin: 0;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-header h3 i {
    font-size: 18px;
}

.close-notification {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.3s;
}

.close-notification:hover {
    opacity: 1;
}

.notification-body {
    padding: 15px;
}

.event-item {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 10px;
    animation: fadeIn 0.5s ease;
}

.event-item:last-child {
    margin-bottom: 0;
}

.event-title {
    font-weight: 600;
    font-size: 16px;
    margin: 0 0 8px 0;
}

.event-info {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
    font-size: 14px;
}

.event-info i {
    width: 20px;
    margin-right: 8px;
}

.view-button {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 20px;
    margin-top: 10px;
    cursor: pointer;
    transition: background 0.3s;
    text-decoration: none;
    font-size: 14px;
}

.view-button:hover {
    background: rgba(255, 255, 255, 0.3);
}

.no-events {
    text-align: center;
    padding: 15px;
    font-style: italic;
    opacity: 0.8;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Badge de notification */
.notification-badge {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #4a6bff;
    color: white;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    z-index: 9998;
    transition: all 0.3s ease;
}

.notification-badge.hidden {
    display: none;
}

.notification-badge:hover {
    background: #3a5bef;
    transform: scale(1.1);
}

.notification-badge i {
    font-size: 20px;
}

.notification-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4a4a;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
}</style>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div id="form-container">
            <h2 id="form-title"><?= isset($eventToEdit) ? 'Modifier un événement' : 'Create an event' ?></h2>
            <form method="POST" enctype="multipart/form-data" id="event-form">
                <?php if (isset($eventToEdit)): ?>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($eventToEdit['id_event']) ?>">
                    <input type="hidden" name="keep_image" value="1">
                <?php else: ?>
                    <input type="hidden" name="action" value="add">
                <?php endif; ?>

                <label for="nom">
    <i class="fas fa-calendar-day"></i> Event name:
</label>
<input type="text" id="nom" name="nom" value="<?= isset($eventToEdit) ? htmlspecialchars($eventToEdit['nom_event']) : '' ?>" >
<span class="error" id="error-nom"></span><br />

<label for="date">
    <i class="fas fa-calendar-alt"></i> Date:
</label>
<input type="date" id="date" name="date" value="<?= isset($eventToEdit) ? htmlspecialchars($eventToEdit['date_event']) : '' ?>">
<span class="error" id="error-date"></span><br />

<label for="desc">
    <i class="fas fa-pencil-alt"></i> Description:
</label>
<textarea name="description" id="desc"><?= isset($eventToEdit) ? htmlspecialchars($eventToEdit['desc_event']) : '' ?></textarea>
<span class="error" id="error-desc"></span><br />

<label for="lieu">
    <i class="fas fa-map-marker-alt"></i> Location:
</label>
<input type="text" id="lieu" name="lieu" value="<?= isset($eventToEdit) ? htmlspecialchars($eventToEdit['lieu']) : '' ?>">
<span class="error" id="error-lieu"></span><br />

<label for="img">
    <i class="fas fa-image"></i> Image:
</label>
<input type="file" id="img" name="image" accept="image/*">
<?php if (isset($eventToEdit) && !empty($eventToEdit['img_event'])): ?>
    <div class="current-image">
        <img src="<?= htmlspecialchars($eventToEdit['img_event']) ?>" alt="Image actuelle" style="width: 80px; height: 80px; object-fit: cover;">
        <small>Image actuelle</small>
    </div>
<?php endif; ?>
<span class="error" id="error-img"></span><br />

<button type="submit"><i class="fas fa-save"></i> <?= isset($eventToEdit) ? 'Mettre à jour' : 'SAVE' ?></button>
<style>
  button[type="submit"] {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #007BFF; /* Bleu moderne */
  color: #fff;
  padding: 0.7rem 1.4rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease, transform 0.2s ease;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

button[type="submit"] i {
  font-size: 1rem;
}

button[type="submit"]:hover {
  background-color: #0056b3;
  transform: translateY(-2px);
}

</style>
<?php if (isset($eventToEdit)): ?>
    <a href="Event.php" class="btn btn-cancel">Annuler</a>
<?php endif; ?>
</form>
</div>
        <hr />

        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; color: var(--clr-dark);">
    <span class="material-symbols-sharp" style="background-color: var(--clr-primary); color: #fff; padding: 0.5rem; border-radius: 50%; font-size: 1.8rem;">
        
    </span>
    <style>
      #form-container {
  background-color: #ffffff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  max-width: 600px;
  margin: 2rem auto;
  font-family: 'Segoe UI', sans-serif;
}

#form-title {
  text-align: center;
  margin-bottom: 1.5rem;
  color: #003366;
}

form label {
  display: block;
  margin-top: 1rem;
  font-weight: 600;
  color: #333;
}

form input[type="text"],
form input[type="date"],
form input[type="file"],
form textarea {
  width: 100%;
  padding: 0.7rem;
  margin-top: 0.3rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 1rem;
  box-sizing: border-box;
}

form textarea {
  height: 100px;
  resize: vertical;
}

.current-image {
  margin-top: 0.5rem;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.error {
  color: #d9534f;
  font-size: 0.9rem;
}

button[type="submit"] {
  margin-top: 1.5rem;
  padding: 0.7rem 1.5rem;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

button[type="submit"]:hover {
  background-color: #218838;
}

.btn-cancel {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.6rem 1.2rem;
  background-color: #6c757d;
  color: white;
  border-radius: 6px;
  text-decoration: none;
  font-size: 0.95rem;
  transition: background-color 0.2s ease;
}

.btn-cancel:hover {
  background-color: #5a6268;
}

    </style>
    <h2 style="margin: 0; font-size: 1.8rem;">List of events</h2>
</div>

        <div class="search-container">
          <i class="fas fa-search"></i>
          <input type="text" id="search-box" placeholder="SEARCH FOR EVENTS..." value="<?= isset($searchTerm) ? htmlspecialchars($searchTerm) : '' ?>">
          <select id="sort-select" class="sort-select">
            <option value="desc">SORT BY date DESC</option>
            <option value="asc">SORT BY date ASC</option>
          </select>
        </div>
        
        <?php if (empty($events)): ?>
            <p>NO EVENTS FOUND !</p>
        <?php else: ?>
            <table id="event-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Date</th>
                  <th>Description</th>
                  <th>Location</th>
                  <th>Image</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($events as $event): ?>
                  <tr>
                    <td><?= htmlspecialchars($event['nom_event']) ?></td>
                    <td><?= date('d/m/Y', strtotime($event['date_event'])) ?></td>
                    <td><?= htmlspecialchars(substr($event['desc_event'], 0, 50)) ?><?= strlen($event['desc_event']) > 50 ? '...' : '' ?></td>
                    <td><?= htmlspecialchars($event['lieu']) ?></td>
                    <td>
  <?php if (!empty($event['img_event']) && file_exists($event['img_event'])): ?>
    <img src="<?= htmlspecialchars($event['img_event']) ?>" alt="Image événement" style="width: 80px; height: 80px; object-fit: cover;" />
  <?php else: ?>
    <img src="assets/default-event.jpg" alt="Image par défaut" style="width: 80px; height: 80px; object-fit: cover;" />
  <?php endif; ?>
</td>
                    <td>
                        <a href="Event.php?id=<?= $event['id_event'] ?>&action=edit" class="btn btn-primary">EDIT</a>
                        <a href="Event.php?id=<?= $event['id_event'] ?>&action=delete" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">DELETE</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
        <?php endif; ?>
      </main>
      




      <!-- === Right Section  === -->
    <div class="right">
      <div class="top">
        <button id="menu_bar"><span class="material-symbols-sharp">menu</span></button>
        <div class="theme-toggler">
          <span class="material-symbols-sharp active">light_mode</span>
          <span class="material-symbols-sharp">dark_mode</span>
        </div>
        <script>
document.addEventListener("DOMContentLoaded", function () {
  const themeToggler = document.getElementById("themeToggler");
  const lightModeIcon = document.getElementById("lightMode");
  const darkModeIcon = document.getElementById("darkMode");

  // Check saved theme
  if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-theme");
    lightModeIcon.classList.remove("active");
    darkModeIcon.classList.add("active");
  }

  themeToggler.addEventListener("click", function () {
    document.body.classList.toggle("dark-theme");
    const isDark = document.body.classList.contains("dark-theme");

    lightModeIcon.classList.toggle("active", !isDark);
    darkModeIcon.classList.toggle("active", isDark);

    // Save preference
    localStorage.setItem("theme", isDark ? "dark" : "light");
  });
});
</script>

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

    <form method="GET" class="Derniers Commentaires">
    <div class="Derniers Commentaires">
    <div class="Derniers Commentaires">
        <h2>PARTICIPATIONS</h2>
        <br><br>
        <a href="http://localhost/4Validation/View/EVENT/BackOffice/participants.php" class="btn">Show Invitations</a>
        <style>
          /* Bouton "Show Invitations" */
.btn {
  display: inline-block;
  padding: 0.8rem 1.6rem;
  border-radius: 8px;
  background-color:rgb(33, 10, 162); /* Vert */
  color: white;
  font-weight: bold;
  text-decoration: none;
  font-size: 1rem;
  text-align: center;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn:hover {
  background-color:rgb(33, 10, 162); /* Vert foncé */
  transform: translateY(-2px); /* Légère élévation au survol */
}

.btn:active {
  background-color: rgb(33, 10, 162); /* Vert encore plus foncé lors du clic */
  transform: translateY(0); /* Retour à la position d'origine lors du clic */
}

        </style>

        
    </div>
  </div>
  </form>




   </div>
   <style>
    /* Titre */
h2 {
  margin: 1rem 0;
  font-size: 1.8rem;
  color: #333;
}

/* Conteneur de recherche */
.search-container {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin: 1rem 0;
  padding: 0.5rem;
  background: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.search-container i {
  font-size: 1.2rem;
  color: #888;
}

#search-box {
  flex: 1;
  padding: 0.6rem 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
}

.sort-select {
  padding: 0.5rem 0.8rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  background-color: #fff;
}

/* Tableau des événements */
#event-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
  background-color: #fff;
  box-shadow: 0 3px 6px rgba(0,0,0,0.05);
  border-radius: 8px;
  overflow: hidden;
}

#event-table thead {
  background-color: #007BFF;
  color: #fff;
}

#event-table th,
#event-table td {
  padding: 0.9rem;
  text-align: left;
  border-bottom: 1px solid #eee;
}

#event-table tbody tr:hover {
  background-color: #f5f5f5;
}

/* Boutons */
.btn {
  padding: 0.5rem 1rem;
  border-radius: 6px;
  text-decoration: none;
  color: white;
  font-weight: bold;
  transition: background 0.2s ease;
}

.btn-primary {
  background-color: #007BFF;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.btn-danger {
  background-color: #dc3545;
}

.btn-danger:hover {
  background-color: #a71d2a;
}

   </style>
  </main>
  
   <script>
      document.getElementById('sort-select').addEventListener('change', function() {
    var sortOrder = this.value; // 'asc' ou 'desc'
    var searchTerm = document.getElementById('search-input').value; // Assurez-vous d'avoir un champ de recherche

    // Par exemple, utiliser AJAX pour envoyer le sortOrder et le searchTerm au serveur
    var url = 'path_to_your_php_handler.php';
    var data = new FormData();
    data.append('searchTerm', searchTerm);
    data.append('sortOrder', sortOrder);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Traitez les résultats reçus du serveur et mettez à jour l'interface utilisateur
            console.log(xhr.responseText);
        }
    };
    xhr.send(data);
});

       // Gestion de la recherche
       document.getElementById('search-box').addEventListener('keyup', function(e) {
           if (e.key === 'Enter') {
               const searchTerm = this.value.trim();
               if (searchTerm) {
                   window.location.href = `Event.php?search=${encodeURIComponent(searchTerm)}`;
               } else {
                   window.location.href = 'Event.php';
               }
           }
       });
       
       // Gestion du tri
       document.getElementById('sort-select').addEventListener('change', function() {
           const sortOrder = this.value;
           window.location.href = `Event.php?sort=${sortOrder}`;
       });
       
       // Confirmation avant suppression
       document.querySelectorAll('.btn-danger').forEach(btn => {
           btn.addEventListener('click', function(e) {
               if (!confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')) {
                   e.preventDefault();
               }
           });
       });
       
       // Initialisation du sélecteur de tri
       const urlParams = new URLSearchParams(window.location.search);
       const sortParam = urlParams.get('sort');
       if (sortParam) {
           document.getElementById('sort-select').value = sortParam;
       }
    document.getElementById('event-form').addEventListener('submit', function (e) {
    const nom = document.getElementById('nom').value.trim();
    const date = document.getElementById('date').value;
    const description = document.getElementById('desc').value.trim();
    const lieu = document.getElementById('lieu').value.trim();
    const image = document.getElementById('img').value;

    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    let isValid = true;

    if (!nom) {
        document.getElementById('error-nom').textContent = "Le nom ne doit pas être vide.";
        isValid = false;
    } else {
        if (!/^[A-Z]/.test(nom)) {
            document.getElementById('error-nom').textContent = "Le nom doit commencer par une majuscule.";
            isValid = false;
        } else if (nom.length > 50) {
            document.getElementById('error-nom').textContent = "Le nom ne doit pas dépasser 12 caractères.";
            isValid = false;
        }
    }


    if (!date) {
        document.getElementById('error-date').textContent = "La date ne doit pas être vide.";
        isValid = false;
    } else {
        const today = new Date();
        const selectedDate = new Date(date);
        today.setHours(0, 0, 0, 0);
        if (selectedDate < today) {
            document.getElementById('error-date').textContent = "La date doit être aujourd'hui ou plus tard.";
            isValid = false;
        }
    }

    if (!description) {
        document.getElementById('error-desc').textContent = "La description ne doit pas être vide.";
        isValid = false;
    } else {
        if (!/^[A-Z]/.test(description[0]) && !/\.\s*[A-Z]/.test(description)) {
            document.getElementById('error-desc').textContent = "La description doit commencer par une majuscule ou en contenir après un point.";
            isValid = false;
        } else if (description.length > 900) {
            document.getElementById('error-desc').textContent = "La description ne doit pas dépasser 900 caractères.";
            isValid = false;
        }
    }

    if (!lieu) {
        document.getElementById('error-lieu').textContent = "Le lieu ne doit pas être vide.";
        isValid = false;
    } else {
        if (!/^[A-Z]/.test(lieu)) {
            document.getElementById('error-lieu').textContent = "Le lieu doit commencer par une majuscule.";
            isValid = false;
        } else if (!/,/.test(lieu)) {
            document.getElementById('error-lieu').textContent = "Le lieu doit contenir une virgule.";
            isValid = false;
        } else if (!/\d/.test(lieu)) {
            document.getElementById('error-lieu').textContent = "Le lieu doit contenir des chiffres.";
            isValid = false;
        }
    }

    if (!image) {
        document.getElementById('error-img').textContent = "Veuillez sélectionner une image.";
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
    }
});

   </script>
</body>
</html>