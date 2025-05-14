<?php
include_once "../config.php";
//require_once '../Controller/CoursC.php';
//require_once '../Model/Cours.php'; 
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
// Utiliser l’ID de session
if (!isset($_SESSION['utilisateur']['id'])) {
    header('Location: login_register.php');
    exit;
}
$userId = $_SESSION['utilisateur']['id']; // Use the ID from 'utilisateur'

//comments 

// Méthode pour récupérer les derniers commentaires
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

//Numbers 
  //Total des cours 
  $stmtCours = $pdo->query("SELECT COUNT(*) AS total FROM cours");
  $statistiques = $stmtCours->fetch(PDO::FETCH_ASSOC);
  $stmtPopulaire = $pdo->query("SELECT Titre, NbrVu FROM cours ORDER BY NbrVu DESC LIMIT 1");
  $coursPopulaire = $stmtPopulaire->fetch(PDO::FETCH_ASSOC);
  $totalCours = $statistiques['total'];
  // Appel de la fonction pour récupérer les derniers commentaires
   $commentaires = getDerniersCommentaires(5);

   // Total des startups
  $stmtStartups = $pdo->query("SELECT COUNT(*) AS total FROM startup");
  $statStartups = $stmtStartups->fetch(PDO::FETCH_ASSOC);
  $totalStartups = $statStartups['total'];

    // Total Events
    $stmtEvents = $pdo->query("SELECT COUNT(*) AS total FROM events");
    $statEvents = $stmtEvents->fetch(PDO::FETCH_ASSOC);
    $totalEvents = $statEvents['total'];
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | NextStep</title>
  <link rel="website icon" type="PNG" href="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png">
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
      background-image: url('https://img.freepik.com/free-vector/blue-curve-abstract-background_53876-99568.jpg?t=st=1746964243~exp=1746967843~hmac=d297af522052c37e25caf8b5c8b7323d92dceebb7d5ab1735dfbc98beed942cc&w=826'); /* Path to your image */
  background-size: cover;                  /* Make it cover the whole screen */
  background-repeat: no-repeat;           /* Prevent tiling */
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
  </style>
</head>
<body>
   <div class="container">
    <!-- === Sidebar === -->
      <aside> 
         <div class="top">
           <div class="logo">
           <h2>
  <a href="http://localhost/4Validation/View/index.php" style="text-decoration: none; color: inherit;">
    <img style="width: 60px; height: 60px;" src="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png" alt="">
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
            <span class="material-symbols-sharp">
              close
            </span>
           </div>
         </div>
         <!-- end top -->
         <div class="sidebar">
            <a href="Back.php" class="active">
              <span class="material-symbols-sharp">grid_view</span>
              <h3>Home</h3>
           </a>
           <a href="http://localhost/4Validation/View/USER/BackOffice/administration.php">
              <span class="material-symbols-sharp">person_outline </span>
              <h3>Customers</h3>
           </a>
           <a href="http://localhost/4Validation/View/FinANCE/FinanceB.php">
              <span class="material-symbols-sharp">credit_card</span>
              <h3>Finance</h3>
           </a>
           <a href="http://localhost/4Validation/View/STARTUP/BackOffice/startup.php" >
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
      <!-- --------------
        end asid
      -------------------- -->

      <!-- --------------
        start main part
      --------------- -->

      <main>
        <center>
           <h1>404NotFound</h1>
           </center>
           <div class="date">
             <input type="date" >
           </div>

        <div class="insights">

           <!-- Numbers 01 Startups -->
            <div class="sales">
               <span class="material-symbols-sharp">trending_up</span>
               <div class="middle">

                 <div class="left">
                   <h3>Startups</h3>
                   <h1>Total : <?php echo $totalStartups; ?></h1>
                 </div>
                  

               </div>
               <small></small>
            </div>
           <!-- end -->
              <!-- Numbers 02 Events -->
              <div class="expenses">
                <span class="material-symbols-sharp">local_mall</span>
                <div class="middle">
 
                  <div class="left">
                    <h3>Events</h3>
                    <h1>Total : <?php echo $totalEvents; ?></h1>
                  </div>
 
                </div>
                <small></small>
             </div>
            <!-- end seling -->

               <!-- Numbers 03 Cours -->
               <div class="income">
                <span class="material-symbols-sharp">book</span>
                <div class="middle">
                  <div class="left">
                    <h3>Courses</h3>
                    <h1>Total : <?php echo $totalCours; ?></h1>
                  </div>
 
                </div>
                <small>Most popular course : <?= htmlspecialchars($coursPopulaire['Titre']) ?> (<?= intval($coursPopulaire['NbrVu']) ?> views)</small>
             </div>
            <!-- end seling -->
             <!-- Numbers 04 Formation -->
             <?php
               // Requête pour compter le nombre total de formations
               $stmt = $pdo->prepare("SELECT COUNT(*) FROM formation");
               $stmt->execute();
               $totalFormations = $stmt->fetchColumn();
              ?>
            <div class="sales">
               <span class="material-symbols-sharp">school</span>
               <div class="middle">

                 <div class="left">
                   <h3>Formations</h3>
                   <h1>Total : <?php echo $totalFormations; ?></h1>
                 </div>
               </div>
               <small></small>
            </div>
           <!-- end -->

           <!-- Numbers 05 Workshop -->
           <?php
               // Requête pour compter le nombre total de formations
               $stmt = $pdo->prepare("SELECT COUNT(*) FROM workshop");
               $stmt->execute();
               $totalWS = $stmt->fetchColumn();
              ?>
            <div class="expenses">
               <span class="material-symbols-sharp">handyman</span>
               <div class="middle">
                 <div class="left">
                   <h3>Workshops</h3>
                   <h1>Total : <?php echo $totalWS; ?></h1>
                 </div>
               </div>
               <small></small>
            </div>
           <!-- end -->

             <!-- Numbers 06 WorkingSpace -->
             <?php
               // Requête pour compter le nombre total de formations
               $stmt = $pdo->prepare("SELECT COUNT(*) FROM workingspace");
               $stmt->execute();
               $totalW = $stmt->fetchColumn();
              ?>
            <div class="income">
               <span class="material-symbols-sharp">apartment</span>
               <div class="middle">
                 <div class="left">
                   <h3>WorkingSpace</h3>
                   <h1>Total : <?php echo $totalW; ?></h1>
                 </div>
               </div>
               <small></small>
            </div>
           <!-- end -->

        </div>
       <!-- end insights -->
       <?php
// Database connection
try {
    

    // Fetch the data from the database
    $stmt = $pdo->query("SELECT id_workshop, nom_workshop, date_workshop, lieu_workshop FROM workshop");

    // Check if the query returned any results
    if ($stmt) {
        $workshops = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $workshops = [];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $workshops = [];
}
?>

<div class="recent_order">
  <h2>Recent Workshops</h2>
  <table class="min-w-full border border-gray-200 shadow-md rounded-lg overflow-hidden backdrop-blur-md" style="background-color: rgba(255, 255, 255, 0.3);">
    <thead class="bg-gray-100 text-gray-700 text-left">
      <tr>
        <th class="py-3 px-6">Name</th>
        <th class="py-3 px-6">Date</th>
        <th class="py-3 px-6">Place</th>
        
      </tr>
    </thead>
    <tbody class="text-gray-700">
      <?php if (!empty($workshops)): ?>
        <?php foreach ($workshops as $workshop): ?>
          <tr class="border-t">
            <td class="py-3 px-6"><?= htmlspecialchars($workshop['nom_workshop']) ?></td>
            <td class="py-3 px-6"><?= htmlspecialchars($workshop['date_workshop']) ?></td>
            <td class="py-3 px-6"><?= htmlspecialchars($workshop['lieu_workshop']) ?></td>
            <td class="py-3 px-6 text-center">
              
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="4" class="py-3 px-6 text-center">No workshops found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
  <a href="#">Show All</a>
</div>



      </main>
      <!------------------
         end main
        ------------------->

      <!----------------
        start right main 
      ---------------------->
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

<div class="recent_updates" id="updateBlock1">

     <h2>Recent Update</h2>
   <div class="updates">
      <div class="update">
         <div class="profile-photo">
            <img src="./images/profile-4.jpg" alt=""/>
         </div>
        <div class="message">
           <p><b></b> Recived his order of USB</p>
        </div>
      </div>
      <div class="update">
        <div class="profile-photo">
        <img src="./images/profile-3.jpg" alt=""/>
        </div>
       <div class="message">
          <p><b>Ali</b> Recived his order of USB</p>
       </div>
     </div>
     <div class="update">
      <div class="profile-photo">
         <img src="./images/profile-2.jpg" alt=""/>
      </div>
     <div class="message">
        <p><b>Ramzan</b> Recived his order of USB</p>
     </div>
   </div>
  </div>
  </div>


  <div class="recent_updates" id="updateBlock2">

    <h2>🗨️ Latest Comments</h2>
    <div class="updates">

        <?php if (!empty($commentaires)): ?>
            <?php foreach ($commentaires as $commentaire): ?>
                <div class="update">
                    <div class="profile-photo">
                        
                    </div>
                    <div class="message">
                        <p>
                            <strong>Courses ID :</strong> <?= htmlspecialchars($commentaire['cours_id']) ?> |
                            <strong>User :</strong> <?= htmlspecialchars($commentaire['idUser']) ?>
                        </p>
                        <p><em><?= nl2br(htmlspecialchars($commentaire['commentaire'])) ?></em></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="update">
                <div class="message">
                    <p>Aucun commentaire pour le moment.</p>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
  const block1 = document.getElementById("updateBlock1");
  const block2 = document.getElementById("updateBlock2");

  let visible = true;

  setInterval(() => {
    if (visible) {
      block1.style.display = "none";
      block2.style.display = "block";
    } else {
      block1.style.display = "block";
      block2.style.display = "none";
    }
    visible = !visible;
  }, 5000); // Change every 5 seconds
</script>




   <div class="sales-analytics">
     <h2>Notifications</h2>

     <div class="item onlion">
    <div class="icon">
        <span class="material-symbols-sharp">money</span>
    </div>
    <div class="right_text">
        <div class="info">
            <h3>Finance</h3>
            <small class="text-muted">Requests</small>
        </div>

        <!-- Display the number of pending finance requests -->
        <?php
        // Count the number of pending requests (etat = 0)
        $stmt = $pdo->query("SELECT COUNT(*) AS pending_requests FROM demandefinance WHERE etat = 0");
        $pendingRequest = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <h5 class="danger"><?php echo $pendingRequest['pending_requests']; ?> Pending</h5>
      
    </div>
</div>

<div class="item onlion">
    <div class="icon">
        <span class="material-symbols-sharp">event</span>
    </div>
    <div class="right_text">
        <div class="info">
            <h3>Events</h3>
            <small class="text-muted">Last seen 2 Hours</small>
        </div>
        <?php
        // Count the number of pending requests
        $stmt = $pdo->query("SELECT COUNT(*) AS pending_requestsF FROM rejoindre WHERE statut_participation = 'en attente'");
        $pendingRequestF = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <h5 class="danger"><?php echo $pendingRequestF['pending_requestsF']; ?> Pending</h5>
    </div>
</div>


    


   </div>

   <script src="script.js"></script>
</body>


</html>