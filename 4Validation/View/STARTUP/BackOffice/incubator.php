<?php
// Enable error reporting
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


// Database connection
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../Model/startup.php';
require_once __DIR__ . '/../../../Model/incubator.php';
require_once __DIR__ . '/../../../Controller/startupC.php';
require_once __DIR__ . '/../../../Controller/incubatorC.php';

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

// Initialize controllers
$nitroC = new nitroC();
$workshopC = new workshopC();
$workingspaceC = new workingspaceC();

// Get all items
$nitros = $nitroC->listnitro();
$workshops = $workshopC->listworkshop();
$workspaces = $workingspaceC->listworkingspace();

// Process form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Workshop submission
        if (isset($_POST['submit_workshop'])) {
            $workshop = new workshop_id(
                $_POST['id_workshop'] ?? null,
                htmlspecialchars($_POST['nom_workshop']),
                htmlspecialchars($_POST['date_workshop']),
                htmlspecialchars($_POST['lieu_workshop']),
                htmlspecialchars($_POST['sujet_workshop']),
                htmlspecialchars($_POST['responsable'])
            );
            
            if (!empty($_POST['id_workshop'])) {
                $workshopC->updateworkshop($workshop, $_POST['id_workshop']);
                $_SESSION['message'] = "Workshop updated successfully!";
            } else {
                $workshopC->addworkshop($workshop);
                $_SESSION['message'] = "Workshop added successfully!";
            }
        }
        
        // Nitro submission
        elseif (isset($_POST['submit_nitro'])) {
            $nitro = new nitro_id(
                $_POST['id_nitro'] ?? null,
                htmlspecialchars($_POST['nitro_name']),
                floatval($_POST['nitro_price']),
                htmlspecialchars($_POST['nitro_period'])
            );
            
            if (!empty($_POST['id_nitro'])) {
                $nitroC->updatenitro($nitro, $_POST['id_nitro']);
                $_SESSION['message'] = "Nitro plan updated successfully!";
            } else {
                $nitroC->addnitro($nitro);
                $_SESSION['message'] = "Nitro plan added successfully!";
            }
        }
        
        // WorkingSpace submission
        elseif (isset($_POST['submit_workspace'])) {
            $workingspace = new workingspace_id(
                $_POST['id_workingspace'] ?? null,
                htmlspecialchars($_POST['nom_workingspace']),
                floatval($_POST['surface']),
                floatval($_POST['prix_workingspace']),
                intval($_POST['capacite_workingspace']),
                htmlspecialchars($_POST['localisation'])
            );
            
            if (!empty($_POST['id_workingspace'])) {
                $workingspaceC->updateworkingspace($workingspace, $_POST['id_workingspace']);
                $_SESSION['message'] = "Workspace updated successfully!";
            } else {
                $workingspaceC->addworkingspace($workingspace);
                $_SESSION['message'] = "Workspace added successfully!";
            }
        }
        
        // Delete actions
        elseif (isset($_POST['delete_nitro'])) {
            $nitroC->deletenitro($_POST['id_nitro']);
            $_SESSION['message'] = "Nitro plan deleted successfully!";
        }
        elseif (isset($_POST['delete_workshop'])) {
            $workshopC->deleteworkshop($_POST['id_workshop']);
            $_SESSION['message'] = "Workshop deleted successfully!";
        }
        elseif (isset($_POST['delete_workspace'])) {
            $workingspaceC->deleteworkingspace($_POST['id_workingspace']);
            $_SESSION['message'] = "Workspace deleted successfully!";
        }
        
        header("Location: incubator.php");
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: incubator.php");
        exit();
    }
}

// Get statistics
$stmtCours = $pdo->query("SELECT COUNT(*) AS total FROM cours");
$statistiques = $stmtCours->fetch(PDO::FETCH_ASSOC);
$stmtPopulaire = $pdo->query("SELECT Titre, NbrVu FROM cours ORDER BY NbrVu DESC LIMIT 1");
$coursPopulaire = $stmtPopulaire->fetch(PDO::FETCH_ASSOC);
$totalCours = $statistiques['total'];

// Total startups
$stmtStartups = $pdo->query("SELECT COUNT(*) AS total FROM startup");
$statStartups = $stmtStartups->fetch(PDO::FETCH_ASSOC);
$totalStartups = $statStartups['total'];

// Total Events
$stmtEvents = $pdo->query("SELECT COUNT(*) AS total FROM events");
$statEvents = $stmtEvents->fetch(PDO::FETCH_ASSOC);
$totalEvents = $statEvents['total'];

// Total Formations
$stmtFormations = $pdo->prepare("SELECT COUNT(*) FROM formation");
$stmtFormations->execute();
$totalFormations = $stmtFormations->fetchColumn();

// Total Workshops
$stmtWS = $pdo->prepare("SELECT COUNT(*) FROM workshop");
$stmtWS->execute();
$totalWS = $stmtWS->fetchColumn();

// Total WorkingSpaces
$stmtW = $pdo->prepare("SELECT COUNT(*) FROM workingspace");
$stmtW->execute();
$totalW = $stmtW->fetchColumn();

// Get latest comments
function getDerniersCommentaires($limit = 5) {
    global $pdo;
    try {
        $query = "SELECT cours_id, idUser, commentaire 
                  FROM commentaires 
                  ORDER BY date DESC 
                  LIMIT ?";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $this->message = "❌ Erreur récupération des commentaires : " . $e->getMessage();
        return [];
    }
}
$commentaires = getDerniersCommentaires(5);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Incubators | NextStep</title>
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
        <a href="http://localhost/4Validation/View/STARTUP/BackOffice/incubator.php" class="active">
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
         </div>
      </aside>

      <main id="main-content">
        <div class="incubator-content">
          <center>
          <h1>Incubators Management</h1>
          </center>
          <!-- Statistics Section -->
          <section class="stats-container">
            <div class="stat-card">
              <div class="stat-value"><?= count($nitros) ?></div>
              <div class="stat-label">Nitro Plans</div>
            </div>
            <div class="stat-card">
              <div class="stat-value"><?= count($workshops) ?></div>
              <div class="stat-label">Workshops</div>
            </div>
            <div class="stat-card">
              <div class="stat-value"><?= count($workspaces) ?></div>
              <div class="stat-label">Working Spaces</div>
            </div>
          </section>
          
          <!-- Nitro Plans Section -->
          <section>
            <div class="search-sort-container">
              <h2>Nitro Plans</h2>
              <div>
                <input type="text" class="search-box" placeholder="Search nitro plans..." data-search="nitro">
                <select class="sort-dropdown" data-sort="nitro">
                  <option value="name_asc">Name (A-Z)</option>
                  <option value="name_desc">Name (Z-A)</option>
                  <option value="price_asc">Price (Low-High)</option>
                  <option value="price_desc">Price (High-Low)</option>
                </select>
              </div>
            </div>
            <button class="btn btn-add" onclick="showForm('nitro-form')">
              <span class="material-symbols-sharp">add</span> Add Nitro Plan
            </button>
            
            <div class="items-container" id="nitro-container">
              <?php foreach ($nitros as $nitro): ?>
                <div class="item-card" data-name="<?= htmlspecialchars(strtolower($nitro['nitro_name'])) ?>" data-price="<?= $nitro['nitro_price'] ?>">
                  <h3><?= htmlspecialchars($nitro['nitro_name']) ?></h3>
                  <p>Price: $<?= number_format($nitro['nitro_price'], 2) ?></p>
                  <p>Period: <?= htmlspecialchars($nitro['nitro_period']) ?></p>
                  <div class="item-actions">
                  <button class="btn btn-edit"
  onclick="editNitro(
    <?= $nitro['id_nitro'] ?>,
    '<?= htmlspecialchars(addslashes($nitro['nitro_name'])) ?>',
    <?= $nitro['nitro_price'] ?>,
    '<?= htmlspecialchars(addslashes($nitro['nitro_period'])) ?>'
  )">
  <span class="material-symbols-sharp">edit</span> Edit
</button>
                    <form method="post" style="display:inline;">
                      <input type="hidden" name="id_nitro" value="<?= $nitro['id_nitro'] ?>">
                      <button type="submit" name="delete_nitro" class="btn btn-delete">
                        <span class="material-symbols-sharp">delete</span> Delete
                      </button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="no-results" style="display: none;">No nitro plans found matching your search.</div>
            </div>
          </section>
          <style>
            .btn.btn-add {
  background-color: #003366; /* Dark blue color */
  color: white; /* Text color */
  border: none; /* Optional: removes the border */
  padding: 10px 20px; /* Adjust the padding as needed */
  font-size: 16px; /* Font size */
  cursor: pointer; /* Makes the cursor a pointer when hovered */
  border-radius: 5px; /* Optional: adds rounded corners */
}

.btn.btn-add:hover {
  background-color: #002244; /* Slightly darker blue on hover */
}

          </style>
          <!-- Workshops Section -->
          <section style="margin-top: 40px;">
            <div class="search-sort-container">
              <h2>Workshops</h2>
              <div>
                <input type="text" class="search-box" placeholder="Search workshops..." data-search="workshop">
                <select class="sort-dropdown" data-sort="workshop">
                  <option value="name_asc">Name (A-Z)</option>
                  <option value="name_desc">Name (Z-A)</option>
                  <option value="date_asc">Date (Oldest)</option>
                  <option value="date_desc">Date (Newest)</option>
                </select>
              </div>
            </div>
            <button class="btn btn-add" onclick="showForm('workshop-form')">
              <span class="material-symbols-sharp">add</span> Add Workshop
            </button>
            
            <div class="items-container" id="workshop-container">
              <?php foreach ($workshops as $workshop): ?>
                <div class="item-card" data-name="<?= htmlspecialchars(strtolower($workshop['nom_workshop'])) ?>" data-date="<?= strtotime($workshop['date_workshop']) ?>">
                  <h3><?= htmlspecialchars($workshop['nom_workshop']) ?></h3>
                  <p>Date: <?= date('M d, Y', strtotime($workshop['date_workshop'])) ?></p>
                  <p>Location: <?= htmlspecialchars($workshop['lieu_workshop']) ?></p>
                  <div class="item-actions">
                  <button class="btn btn-edit"
  onclick="editWorkshop(
    <?= $workshop['id_workshop'] ?>,
    '<?= htmlspecialchars(addslashes($workshop['nom_workshop'])) ?>',
    '<?= $workshop['date_workshop'] ?>',
    '<?= htmlspecialchars(addslashes($workshop['lieu_workshop'])) ?>',
    '<?= htmlspecialchars(addslashes($workshop['sujet_workshop'])) ?>',
    '<?= htmlspecialchars(addslashes($workshop['responsable'])) ?>'
  )">
  <span class="material-symbols-sharp">edit</span> Edit
</button>
                    <form method="post" style="display:inline;">
                      <input type="hidden" name="id_workshop" value="<?= $workshop['id_workshop'] ?>">
                      <button type="submit" name="delete_workshop" class="btn btn-delete">
                        <span class="material-symbols-sharp">delete</span> Delete
                      </button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="no-results" style="display: none;">No workshops found matching your search.</div>
            </div>
          </section>
          
          <!-- Working Spaces Section -->
          <section style="margin-top: 40px;">
            <div class="search-sort-container">
              <h2>Working Spaces</h2>
              <div>
                <input type="text" class="search-box" placeholder="Search workspaces..." data-search="workspace">
                <select class="sort-dropdown" data-sort="workspace">
                  <option value="name_asc">Name (A-Z)</option>
                  <option value="name_desc">Name (Z-A)</option>
                  <option value="price_asc">Price (Low-High)</option>
                  <option value="price_desc">Price (High-Low)</option>
                  <option value="capacity_asc">Capacity (Small-Large)</option>
                  <option value="capacity_desc">Capacity (Large-Small)</option>
                </select>
              </div>
            </div>
            <button class="btn btn-add" onclick="showForm('workspace-form')">
              <span class="material-symbols-sharp">add</span> Add Space
            </button>
            
            <div class="items-container" id="workspace-container">
              <?php foreach ($workspaces as $workspace): ?>
                <div class="item-card" data-name="<?= htmlspecialchars(strtolower($workspace['nom_workingspace'])) ?>" 
                     data-price="<?= $workspace['prix_workingspace'] ?>" 
                     data-capacity="<?= $workspace['capacite_workingspace'] ?>">
                  <h3><?= htmlspecialchars($workspace['nom_workingspace']) ?></h3>
                  <p>Location: <?= htmlspecialchars($workspace['localisation']) ?></p>
                  <p>Capacity: <?= $workspace['capacite_workingspace'] ?> people</p>
                  <div class="item-actions">
                  <button class="btn btn-edit"
  onclick="editWorkspace(
    <?= $workspace['id_workingspace'] ?>,
    '<?= htmlspecialchars(addslashes($workspace['nom_workingspace'])) ?>',
    <?= $workspace['surface'] ?>,
    <?= $workspace['prix_workingspace'] ?>,
    <?= $workspace['capacite_workingspace'] ?>,
    '<?= htmlspecialchars(addslashes($workspace['localisation'])) ?>'
  )">
  <span class="material-symbols-sharp">edit</span> Edit
</button>
<style>
            .btn.btn-edit {
  background-color: #003366; /* Dark blue color */
}

.btn.btn-edit:hover {
  background-color: #002244; /* Slightly darker blue on hover */
}
</style>
<style>
            .btn.btn-delete {
  background-color: #003366; /* Dark blue color */
}

.btn.btn-edit:delete {
  background-color: #002244; /* Slightly darker blue on hover */
}
</style>

                    <form method="post" style="display:inline;">
                      <input type="hidden" name="id_workingspace" value="<?= $workspace['id_workingspace'] ?>">
                      <button type="submit" name="delete_workspace" class="btn btn-delete">
                        <span class="material-symbols-sharp">delete</span> Delete
                      </button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="no-results" style="display: none;">No workspaces found matching your search.</div>
            </div>
          </section>
          
          <!-- Forms (initially hidden) -->
          <div id="forms-container" style="margin-top: 40px; display: none;">
            <!-- Workshop Form -->
            <form id="workshop-form" class="form" method="post">
              <input type="hidden" name="id_workshop" value="">
              <h3>Workshop Details</h3>
              <div class="form-group">
                <label>Name:</label>
                <input type="text" name="nom_workshop" required>
              </div>
              <div class="form-group">
                <label>Date:</label>
                <input type="date" name="date_workshop" required>
              </div>
              <div class="form-group">
                <label>Location:</label>
                <input type="text" name="lieu_workshop" required>
              </div>
              <div class="form-group">
                <label>Subject:</label>
                <input type="text" name="sujet_workshop" required>
              </div>
              <div class="form-group">
                <label>Responsible:</label>
                <input type="text" name="responsable" required>
              </div>
              <button type="submit" name="submit_workshop" class="btn">Save</button>
              <button type="button" onclick="hideForms()" class="btn">Cancel</button>
            </form>
            
            <!-- Nitro Form -->
            <form id="nitro-form" class="form" method="post">
              <input type="hidden" name="id_nitro" value="">
              <h3>Nitro Details</h3>
              <div class="form-group">
                <label>Plan Name:</label>
                <input type="text" name="nitro_name" required>
              </div>
              <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" name="nitro_price" required>
              </div>
              <div class="form-group">
                <label>Period:</label>
                <input type="text" name="nitro_period" required>
              </div>
              <button type="submit" name="submit_nitro" class="btn">Save</button>
              <button type="button" onclick="hideForms()" class="btn">Cancel</button>
            </form>
            
            <!-- Workspace Form -->
            <form id="workspace-form" class="form" method="post">
              <input type="hidden" name="id_workingspace" value="">
              <h3>Workspace Details</h3>
              <div class="form-group">
                <label>Name:</label>
                <input type="text" name="nom_workingspace" required>
              </div>
              <div class="form-group">
                <label>Surface Area (m²):</label>
                <input type="number" step="0.01" name="surface" required>
              </div>
              <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" name="prix_workingspace" required>
              </div>
              <div class="form-group">
                <label>Capacity:</label>
                <input type="number" name="capacite_workingspace" required>
              </div>
              <div class="form-group">
                <label>Location:</label>
                <input type="text" name="localisation" required>
              </div>
              <button type="submit" name="submit_workspace" class="btn">Save</button>
              <button type="button" onclick="hideForms()" class="btn">Cancel</button>
            </form>
          </div>
        </div>
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

   <script>
     // Form handling functions
     function showForm(formId) {
       document.getElementById('forms-container').style.display = 'block';
       document.querySelectorAll('.form').forEach(f => f.style.display = 'none');
       document.getElementById(formId).style.display = 'block';
     }
     
     function hideForms() {
       document.getElementById('forms-container').style.display = 'none';
       document.querySelectorAll('.form').forEach(f => f.reset());
     }
     
     // Edit functions would fetch data and populate forms
     function editNitro(id, name, price, period) {
    showForm('nitro-form');
    const form = document.getElementById('nitro-form');
    form.querySelector('[name="id_nitro"]').value = id;
    form.querySelector('[name="nitro_name"]').value = name;
    form.querySelector('[name="nitro_price"]').value = price;
    form.querySelector('[name="nitro_period"]').value = period;
  }
     
  function editWorkshop(id, name, date, location, subject, responsible) {
    showForm('workshop-form');
    const form = document.getElementById('workshop-form');
    form.querySelector('[name="id_workshop"]').value = id;
    form.querySelector('[name="nom_workshop"]').value = name;
    form.querySelector('[name="date_workshop"]').value = date;
    form.querySelector('[name="lieu_workshop"]').value = location;
    form.querySelector('[name="sujet_workshop"]').value = subject;
    form.querySelector('[name="responsable"]').value = responsible;
  }
     
  function editWorkspace(id, name, surface, price, capacity, location) {
    showForm('workspace-form');
    const form = document.getElementById('workspace-form');
    form.querySelector('[name="id_workingspace"]').value = id;
    form.querySelector('[name="nom_workingspace"]').value = name;
    form.querySelector('[name="surface"]').value = surface;
    form.querySelector('[name="prix_workingspace"]').value = price;
    form.querySelector('[name="capacite_workingspace"]').value = capacity;
    form.querySelector('[name="localisation"]').value = location;
  }

     // Menu toggle functionality
     document.getElementById('menu_bar').addEventListener('click', function() {
       document.querySelector('aside').classList.toggle('active');
     });

     // Theme toggler
     const themeToggler = document.querySelector('.theme-toggler');
     themeToggler.addEventListener('click', () => {
       document.body.classList.toggle('dark-theme');
       themeToggler.querySelector('span:nth-child(1)').classList.toggle('active');
       themeToggler.querySelector('span:nth-child(2)').classList.toggle('active');
     });

     // Search functionality
     document.querySelectorAll('.search-box').forEach(searchBox => {
       searchBox.addEventListener('input', function() {
         const searchTerm = this.value.toLowerCase();
         const containerId = this.getAttribute('data-search') + '-container';
         const items = document.querySelectorAll(`#${containerId} .item-card`);
         let hasResults = false;
         
         items.forEach(item => {
           const name = item.getAttribute('data-name');
           if (name.includes(searchTerm)) {
             item.style.display = 'block';
             hasResults = true;
           } else {
             item.style.display = 'none';
           }
         });
         
         // Show/hide no results message
         const noResults = document.querySelector(`#${containerId} .no-results`);
         noResults.style.display = hasResults ? 'none' : 'block';
       });
     });

     // Sort functionality
     document.querySelectorAll('.sort-dropdown').forEach(dropdown => {
       dropdown.addEventListener('change', function() {
         const sortValue = this.value;
         const containerId = this.getAttribute('data-sort') + '-container';
         const container = document.getElementById(containerId);
         const items = Array.from(container.querySelectorAll('.item-card'));
         
         items.sort((a, b) => {
           if (sortValue === 'name_asc') {
             return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
           } else if (sortValue === 'name_desc') {
             return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
           } else if (sortValue === 'price_asc') {
             return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
           } else if (sortValue === 'price_desc') {
             return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
           } else if (sortValue === 'date_asc') {
             return parseInt(a.getAttribute('data-date')) - parseInt(b.getAttribute('data-date'));
           } else if (sortValue === 'date_desc') {
             return parseInt(b.getAttribute('data-date')) - parseInt(a.getAttribute('data-date'));
           } else if (sortValue === 'capacity_asc') {
             return parseInt(a.getAttribute('data-capacity')) - parseInt(b.getAttribute('data-capacity'));
           } else if (sortValue === 'capacity_desc') {
             return parseInt(b.getAttribute('data-capacity')) - parseInt(a.getAttribute('data-capacity'));
           }
           return 0;
         });
         
         // Re-append sorted items
         items.forEach(item => container.appendChild(item));
       });
     });

     // Form validation
     document.getElementById('nitro-form').addEventListener('submit', function(e) {
         const name = this.nitro_name.value.trim();
         const price = parseFloat(this.nitro_price.value.trim());

         if (!/^[A-Z]/.test(name)) {
             alert('Nitro Plan Name must start with an uppercase letter.');
             e.preventDefault();
             return;
         }
         if (isNaN(price) || price <= 2) {
             alert('Nitro Price must be a number greater than 2.');
             e.preventDefault();
             return;
         }
     });

     document.getElementById('workshop-form').addEventListener('submit', function(e) {
         const name = this.nom_workshop.value.trim();
         const location = this.lieu_workshop.value.trim();
         const subject = this.sujet_workshop.value.trim();
         const responsible = this.responsable.value.trim();

         if (!/^[A-Z]/.test(name) || !/^[A-Za-z\s]+$/.test(name)) {
             alert('Workshop Name must start with an uppercase letter and only contain letters.');
             e.preventDefault();
             return;
         }
         if (!/,/.test(location)) {
             alert('Workshop Location must contain a comma.');
             e.preventDefault();
             return;
         }
         if (!/^[A-Z]/.test(subject) || !/^[A-Za-z\s]+$/.test(subject)) {
             alert('Workshop Subject must start with an uppercase letter and only contain letters.');
             e.preventDefault();
             return;
         }
         if (!/^[A-Z]/.test(responsible) || !/^[A-Za-z\s]+$/.test(responsible)) {
             alert('Responsible must start with an uppercase letter and only contain letters.');
             e.preventDefault();
             return;
         }
     });

     document.getElementById('workspace-form').addEventListener('submit', function(e) {
         const name = this.nom_workingspace.value.trim();
         const surface = this.surface.value.trim();
         const price = this.prix_workingspace.value.trim();
         const capacity = this.capacite_workingspace.value.trim();
         const location = this.localisation.value.trim();

         if (!/^[A-Z]/.test(name)) {
             alert('Workspace Name must start with an uppercase letter.');
             e.preventDefault();
             return;
         }
         if (!/^\d+(\.\d+)?$/.test(surface)) {
             alert('Surface must be a number.');
             e.preventDefault();
             return;
         }
         if (!/^\d+(\.\d+)?$/.test(price)) {
             alert('Price must be a number.');
             e.preventDefault();
             return;
         }
         if (!/^\d+$/.test(capacity)) {
             alert('Capacity must be a number.');
             e.preventDefault();
             return;
         }
         if (!/^[A-Z]/.test(location) || !/,/.test(location)) {
             alert('Location must start with an uppercase letter and contain a comma.');
             e.preventDefault();
             return;
         }
     });
   </script>
</body>
</html>