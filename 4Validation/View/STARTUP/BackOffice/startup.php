<?php


// Database connection
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../Model/startup.php';
require_once __DIR__ . '/../../../Model/incubator.php';
require_once __DIR__ . '/../../../Controller/startupC.php';
require_once __DIR__ . '/../../../Controller/incubatorC.php';

// Enable error reporting
//error_reporting(E_ALL);
////ini_set('display_errors', 1);
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

// Initialize controller
$startupC = new startupC();

// Get all startups
$startups = $startupC->liststartup();

// Search functionality
if (isset($_GET['search'])) {
  $searchTerm = htmlspecialchars($_GET['search']);
  $startups = array_filter($startups, function($startup) use ($searchTerm) {
      return stripos($startup['nom_startup'], $searchTerm) !== false || 
             stripos((string)$startup['utilisateur_id'], $searchTerm) !== false;
  });
}



// Sorting functionality
if (isset($_GET['sort'])) {
  usort($startups, function($a, $b) {
      $order = $_GET['sort'] === 'asc' ? 1 : -1;
      return $order * strcmp($a['nom_startup'], $b['nom_startup']);
  });
}
// Process form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  try {
      // Startup submission
      if (isset($_POST['submit_startup'])) {
          $uploadPath = null;
          if (isset($_FILES['img_startup']) && $_FILES['img_startup']['error'] === 0) {
              $uploads_dir = __DIR__ . '/img_startups/';
              if (!is_dir($uploads_dir)) {
                  mkdir($uploads_dir, 0777, true);
              }
              $fileName = basename($_FILES['img_startup']['name']);
              $uploadPath = '/4Validation/View/STARTUP/BackOffice/img_startups/' . $fileName;
              move_uploaded_file($_FILES['img_startup']['tmp_name'], $uploads_dir . $fileName);
          }

          $startup = new startup_id(
              $_POST['startup_id_id'] ?? null,
              htmlspecialchars($_POST['nom_startup']),
              intval($_POST['utilisateur_id']),
              htmlspecialchars($_POST['but_startup']),
              htmlspecialchars($_POST['desc_startup']),
              htmlspecialchars($_POST['date_startup']),
              $uploadPath
          );

          if (!empty($_POST['startup_id_id'])) {
              $startupC->updatestartup($startup, $_POST['startup_id_id']);
              $_SESSION['message'] = "Startup updated successfully!";
          } else {
              $startupC->addstartup($startup);
              $_SESSION['message'] = "Startup added successfully!";
          }
      }
        
        // Delete action
        elseif (isset($_POST['delete_startup'])) {
            $startupC->deletestartup($_POST['startup_id_id']);
            $_SESSION['message'] = "Startup deleted successfully!";
        }
        
        header("Location: startup.php");
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: startup.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Startups | NextStep</title>
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
        <a href="http://localhost/4Validation/View/STARTUP/BackOffice/startup.php"class="active">
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
        <a href="http://localhost/4Validation/View/EVENT/BackOffice/participants.php">
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

      <main id="main-content">
        <div class="incubator-content">
          <center>
          <h1>Startups Management</h1>
          </center>
          <div class="controls" style="margin-bottom: 20px; display: flex; gap: 15px; align-items: center;">
    <!-- Search Box -->
    <div class="search-box" style="flex-grow: 1;">
        <form method="GET" action="startup.php" style="display: flex; gap: 10px;">
            <input type="text" name="search" placeholder="Search startups..." 
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" 
                   style="padding: 8px; border-radius: 4px; border: 1px solid #ddd; flex-grow: 1;">
            <button type="submit" class="btn" style="padding: 8px 15px;">
                <span class="material-symbols-sharp">search</span> Search
            </button>
            <?php if (isset($_GET['search'])): ?>
                <a href="startup.php" class="btn" style="padding: 8px 15px;">
                    <span class="material-symbols-sharp">clear</span> Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Sort Dropdown -->
    <div class="sort-dropdown">
        <form method="GET" action="startup.php" style="display: flex; gap: 10px;">
            <select name="sort" onchange="this.form.submit()" style="padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                <option value="">Sort by</option>
                <option value="asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'asc' ? 'selected' : '' ?>>A-Z</option>
                <option value="desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'desc' ? 'selected' : '' ?>>Z-A</option>
            </select>
            <?php if (isset($_GET['search'])): ?>
                <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
            <?php endif; ?>
        </form>
    </div>
</div>
<style>
  .controls {
    background: var(--clr-white);
    padding: 15px;
    border-radius: var(--border-radius-2);
    box-shadow: var(--box-shadow);
    margin-bottom: 20px;
}

.search-box input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: var(--border-radius-1);
}

.sort-dropdown select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: var(--border-radius-1);
    background: white;
    cursor: pointer;
}
</style>
          
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Optional: Debounce function for search input
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let timer;
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }
});
</script>
          <!-- Startups Section -->
          <section>
            <h2>Startups
              <button class="btn btn-add" onclick="showForm('startup-form')">
                <span class="material-symbols-sharp">add</span> Add Startup
              </button>
            </h2>
            <div class="items-container">
              <?php foreach ($startups as $startup): ?>
                <div class="item-card">
                  <?php if ($startup['img_startup']): ?>
                    <img src="../frontOffice/<?= $startup['img_startup']?>" alt="<?= htmlspecialchars($startup['img_startup']) ?>">
                  <?php endif; ?>
                  <h3><?= htmlspecialchars($startup['nom_startup']) ?></h3>
                  <p><strong>Purpose:</strong> <?= htmlspecialchars($startup['but_startup']) ?></p>
                  <p><strong>Launch Date:</strong> <?= htmlspecialchars($startup['date_startup']) ?></p>
                  <div class="item-actions">
                    <button class="btn btn-edit" onclick="editStartup(<?= htmlspecialchars(json_encode($startup)) ?>);">
                      <span class="material-symbols-sharp">edit</span> Edit
                    </button>
                    <form method="post" style="display:inline;">
                      <input type="hidden" name="startup_id_id" value="<?= $startup['startup_id_id'] ?>">
                      <button type="submit" name="delete_startup" class="btn btn-delete">
                        <span class="material-symbols-sharp">delete</span> Delete
                      </button>
                    </form>
                    
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </section>
          
          <!-- Startup Form (initially hidden) -->
          <div id="forms-container" style="margin-top: 40px; display: none;">
            <form id="startup-form" class="form" method="post" enctype="multipart/form-data">
              <input type="hidden" name="startup_id_id" value="">
              <h3>Startup Details</h3>
              <div class="form-group">
                <label>Startup Name:</label>
                <input type="text" name="nom_startup" required>
              </div>
              
              <div class="form-group">
                <label>Purpose:</label>
                <input type="text" name="but_startup" required>
              </div>
              <div class="form-group">
                <label>Description:</label>
                <textarea name="desc_startup" required></textarea>
              </div>
              <div class="form-group">
                <label>Launch Date:</label>
                <input type="date" name="date_startup" required>
              </div>
              <div class="form-group">
                <label>Image:</label>
                <input type="file" name="img_startup">
              </div>
              <button type="submit" name="submit_startup" class="btn">Save</button>
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
       document.getElementById(formId).reset();
     }
     
     function hideForms() {
       document.getElementById('forms-container').style.display = 'none';
       document.querySelectorAll('.form').forEach(f => f.reset());
     }
     
     // Edit function for startups
     function editStartup(startup) {
       showForm('startup-form');
       const form = document.getElementById('startup-form');
       form.startup_id_id.value = startup.startup_id_id;
       form.nom_startup.value = startup.nom_startup;
       
       form.but_startup.value = startup.but_startup;
       form.desc_startup.value = startup.desc_startup;
       form.date_startup.value = startup.date_startup;
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
   </script>
   
   <script>
   // Form validation
   document.getElementById('startup-form').addEventListener('submit', function(e) {
       const startupName = this.nom_startup.value.trim();
       const purpose = this.but_startup.value.trim();
       const description = this.desc_startup.value.trim();
       let isValid = true;
       
       // Check if Startup Name starts with uppercase
       if (!/^[A-Z]/.test(startupName)) {
           alert('Startup name must start with an uppercase letter.');
           isValid = false;
       }
       
       
       
       // Check if Purpose contains no letters (only numbers or symbols)
       if (/[a-zA-Z]/.test(purpose)) {
           alert('Purpose must not contain letters.');
           isValid = false;
       }
       
       // Check if Description starts with uppercase
       if (!/^[A-Z]/.test(description)) {
           alert('Description must start with an uppercase letter.');
           isValid = false;
       }
       
       if (!isValid) {
           e.preventDefault();
       }
   });
   </script>
</body>
</html>