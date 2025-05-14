
<?php

// === Configuration et Contrôleur ===
require_once '../../config.php';
require_once '../../Controller/CoursC.php';
require_once '../../Model/Cours.php';  

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

$coursController = new CoursController();
$commentaires = $coursController->getDerniersCommentaires();


// === Suppression d’un cours ===
if (isset($_GET['delete'])) {
    $coursController->supprimerCours(intval($_GET['delete']));
}

// === Ajout d’un cours ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['courseName'])) {
    $coursController->ajouterCours($_POST, $_FILES);
}

// === Mise à jour d’un cours ===
if (isset($_POST['update'])) {
  // Récupérer les données du formulaire
  $id = $_POST['id'] ?? null;
  $titre = $_POST['titre'] ?? '';
  $prix = $_POST['prix'] ?? 0;
  $description = $_POST['description'] ?? '';

  // Vérifier que l'ID du cours est valide
  if ($id !== null) {
      // Passer aussi les fichiers au contrôleur pour les traiter
      $coursController->updateCours($id, $titre, $prix, $description, $_FILES); // Passer $_FILES pour les fichiers
      header('Location: cours.php'); // Rediriger vers la liste des cours après mise à jour
      exit; // Terminer le script
  }
}


// === Tri et recherche ===
$tri = $_GET['tri'] ?? 'id';
$search = $_GET['search'] ?? '';
$courses = $coursController->chercherCours($tri, $search);
$message = $coursController->message;

// === Récupérer l'id du cours à mettre à jour ===
if (isset($_GET['update'])) {
  $id = $_GET['update'];
  // Récupérer le cours à partir de la base de données
  $course = $coursController->getCoursById($id);
  if ($course) {
      $titre = $course['Titre'];
      $prix = $course['Prix'];
      $description = $course['Description'];
  }
}

// === Statistiques ===
$statistiques = $coursController->getStatistiques();
$totalCours = $statistiques['totalCours'];
$prixMoyen = $statistiques['prixMoyen'];
$coursPopulaire = $coursController->getCoursLePlusPopulaire();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COURSES | NextStep</title>
  <link rel="website icon" type="PNG" href="../image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="cours.css">
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
              <img style="width: 60px; height: 60px;" src="../image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png" alt="">
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
        <a href="http://localhost/4Validation/View/COURS/cours.php"class="active">
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

    <!-- === Main Content === -->
    <main>
    <center>
      <h1>Course Management</h1>
      </center>
        <!-- Affichage des statistiques -->
    
      <div id="form-container">


        <!-- Formulaire d'ajout -->
        <form id="addCourseForm" action="" method="POST" enctype="multipart/form-data">
          <h2>Add a course</h2>
          <label for="courseName">Title:</label>
          <input type="text" name="courseName" id="courseName" >

          <label for="courseDescription">Description :</label>
          <textarea name="courseDescription" id="courseDescription" ></textarea>

          <label for="coursePrix">Price (dt) :</label>
          <input type="number" name="coursePrix" id="coursePrix" >

          <div class="file-input-group">
  <label for="imgCover">Cover image :</label>
  <input type="file" name="imgCover" id="imgCover" accept="image/*">
</div>

<div class="file-input-group">
  <label for="courseExport">Exported file :</label>
  <input type="file" name="courseExport" id="courseExport" accept=".pdf,.jpg,.png,.mp4">
</div>
<style>
  .file-input-group {
  margin-bottom: 20px;
}

.file-input-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
  color: #003366; /* Bleu foncé */
  font-family: Arial, sans-serif;
}

.file-input-group input[type="file"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #fff;
  font-size: 15px;
  cursor: pointer;
  box-sizing: border-box;
}

</style>
          <button type="submit">ADD</button>
        </form>
        <style>
          form#addCourseForm {
  background-color: #f9f9f9; /* Fond clair */
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 25px 30px;
  max-width: 600px;
  margin: 30px auto;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  font-family: Arial, sans-serif;
}

form#addCourseForm h2 {
  color: #003366;
  margin-bottom: 20px;
  text-align: center;
}

form#addCourseForm label {
  display: block;
  margin: 12px 0 6px;
  color: #003366;
  font-weight: bold;
}

form#addCourseForm input[type="text"],
form#addCourseForm input[type="number"],
form#addCourseForm input[type="file"],
form#addCourseForm textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 15px;
  box-sizing: border-box;
}

form#addCourseForm textarea {
  resize: vertical;
  height: 100px;
}

form#addCourseForm button[type="submit"] {
  background-color: #003366;
  color: white;
  border: none;
  padding: 12px 25px;
  font-size: 16px;
  border-radius: 5px;
  margin-top: 20px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  display: block;
  width: 100%;
}

form#addCourseForm button[type="submit"]:hover {
  background-color: #002244;
}

        </style>


      <!-- Formulaire de mise à jour -->
<!-- Formulaire de mise à jour -->
<?php $id = $id ?? null; ?>
<?php if ($id): ?>
    <form id="addCourseForm" action="" method="POST" enctype="multipart/form-data">
        <h2>Update a course</h2>

        <input type="hidden" name="id" value="<?= $id ?>">

        <label for="titre">Title:</label>
        <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($titre) ?>">

        <label for="prix">Price (dt) :</label>
        <input type="number" name="prix" id="prix" value="<?= htmlspecialchars($prix) ?>">

        <label for="description">Description :</label>
        <textarea name="description" id="description"><?= htmlspecialchars($description) ?></textarea>

        <div class="file-input-group">
            <label for="imgCover">Cover image (optional) :</label>
            <input type="file" name="imgCover" id="imgCover" accept="image/*">
        </div>

        <div class="file-input-group">
            <label for="courseExport">Exported file (optional) :</label>
            <input type="file" name="courseExport" id="courseExport" accept=".pdf,.jpg,.png,.mp4">
        </div>

        <button type="submit" name="update">Update</button>
    </form>

    <style>
        form#addCourseForm {
            background-color: #f9f9f9; /* Fond clair */
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px 30px;
            max-width: 600px;
            margin: 30px auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            font-family: Arial, sans-serif;
        }

        form#addCourseForm h2 {
            color: #003366;
            margin-bottom: 20px;
            text-align: center;
        }

        form#addCourseForm label {
            display: block;
            margin: 12px 0 6px;
            color: #003366;
            font-weight: bold;
        }

        form#addCourseForm input[type="text"],
        form#addCourseForm input[type="number"],
        form#addCourseForm input[type="file"],
        form#addCourseForm textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
            box-sizing: border-box;
        }

        form#addCourseForm textarea {
            resize: vertical;
            height: 100px;
        }

        form#addCourseForm button[type="submit"] {
            background-color: #003366;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            width: 100%;
        }

        form#addCourseForm button[type="submit"]:hover {
            background-color: #002244;
        }

        .file-input-group {
            margin-bottom: 20px;
        }

        .file-input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #003366;
            font-family: Arial, sans-serif;
        }

        .file-input-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            font-size: 15px;
            cursor: pointer;
            box-sizing: border-box;
        }
    </style>
<?php endif; ?>


        <!-- Formulaire de tri/recherche -->
         <center>
        <form method="GET" class="search-bar">
          <label for="tri">Sort by :</label>
          <select name="tri" id="tri" onchange="this.form.submit()">
            <option value="id" <?= ($tri === 'id') ? 'selected' : '' ?>>ID (récent)</option>
            <option value="date" <?= ($tri === 'date') ? 'selected' : '' ?>>Date</option>
            <option value="note" <?= ($tri === 'note') ? 'selected' : '' ?>>Note</option>
            <option value="vues" <?= ($tri === 'vues') ? 'selected' : '' ?>>Vues</option>
          </select>
          <input type="text" name="search" placeholder="🔍 Search a title..." value="<?= htmlspecialchars($search) ?>">
          
        </form>
        </center>
        <style>
          .search-bar {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 12px;
  background-color: #f4f6f8;
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 15px 20px;
  margin: 20px 0;
  max-width: 600px;
  font-family: Arial, sans-serif;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.search-bar label {
  font-weight: bold;
  color: #003366;
}

.search-bar select,
.search-bar input[type="text"] {
  padding: 8px 10px;
  border-radius: 5px;
  border: 1px solid #bbb;
  font-size: 14px;
}

.search-bar input[type="text"] {
  flex-grow: 1;
  min-width: 200px;
}

.search-bar select:focus,
.search-bar input[type="text"]:focus {
  outline: none;
  border-color: #003366;
  box-shadow: 0 0 4px rgba(0, 51, 102, 0.3);
}

        </style>
        
        

         
    </main>

    <!-- === Right Section  === -->
    <div class="right">
      <div class="top">
        <button id="menu_bar"><span class="material-symbols-sharp">menu</span></button>
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

    <form method="GET" class="Derniers Commentaires">
    <div class="Derniers Commentaires">
    <div class="Derniers Commentaires">
        <h2>🗨️ Latest Comments</h2>
        <br><br>
        <?php if (!empty($commentaires)): ?>
            <ul>
                <?php foreach ($commentaires as $commentaire): ?>
                  <li class="comment-item">
  <p><strong>COURSE ID :</strong> <?= htmlspecialchars($commentaire['cours_id']) ?> |
     <strong>USER :</strong> <?= htmlspecialchars($commentaire['idUser']) ?></p>
  <p><em><?= nl2br(htmlspecialchars($commentaire['commentaire'])) ?></em></p>
</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No comments for the moment.</p>
        <?php endif; ?>
    </div>
  </div>
  </form>
  <style>
    li.comment-item {
  background-color: #f9f9f9; /* Fond clair */
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 15px 20px;
  margin-bottom: 15px;
  font-family: Arial, sans-serif;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

li.comment-item p {
  margin: 8px 0;
  font-size: 15px;
  color: #333;
}

li.comment-item strong {
  color: #003366; /* Bleu foncé pour les titres */
}

li.comment-item em {
  display: block;
  margin-top: 8px;
  color: #555;
  font-style: italic;
  white-space: pre-line;
}

  </style>
      <br><br>

      <!-- Formulaire de statistiques et pdf -->
      
<form method="GET" class="statistiques">

<div class="stats-section">
  <h2>Statistics</h2>
  <p><strong>Total of courses :</strong> <?php echo $totalCours; ?></p>
  <p><strong>Average price of courses :</strong> <?php echo number_format($prixMoyen, 2); ?> dt</p>
</div>

        <br>
    <button id="statButton" type="button" onclick="toggleStats()">Show statistics</button>
    <style>
      .stats-section {
  background-color: #f4f6f8;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 25px;
  max-width: 500px;
  font-family: Arial, sans-serif;
  margin-top: 30px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.stats-section h2 {
  color: #003366; /* Bleu foncé */
  margin-bottom: 20px;
  font-size: 22px;
}

.stats-section p {
  font-size: 16px;
  margin: 8px 0;
  color: #333;
}

.stats-section strong {
  color: #000;
}

      #statButton {
  background-color: #003366; /* Bleu foncé */
  color: white;
  border: none;
  padding: 12px 25px;
  font-size: 16px;
  font-family: Arial, sans-serif;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
  margin-top: 20px;
  display: inline-block;
}

#statButton:hover {
  background-color: #002244;
  transform: scale(1.02);
}

    </style>
</form>
<?php if ($coursPopulaire): ?>
  <div class="popular-course">
  <p>
    <strong>Most popular course :</strong> 
    <?= htmlspecialchars($coursPopulaire['Titre']) ?> 
    (<span class="views"><?= intval($coursPopulaire['NbrVu']) ?> views</span>)
  </p>
</div>
<style>
  .popular-course {
  background-color: #e9f0f7;
  border: 1px solid #c4d2e0;
  border-radius: 8px;
  padding: 20px;
  max-width: 500px;
  font-family: Arial, sans-serif;
  margin-top: 20px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
}

.popular-course p {
  font-size: 16px;
  color: #003366;
  margin: 0;
}

.popular-course strong {
  color: #002244;
}

.popular-course span.views {
  font-weight: bold;
  color: #555;
}

</style>

<!-- Contenu des statistiques caché au départ -->
<div id="statistiquesContent" style="display: none;">
    <h2>Statistiques des Cours</h2>

    <!-- Ici, le canvas pour afficher le diagramme -->
    <canvas id="statChart" width="400" height="200"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
    // Fonction pour afficher ou cacher les statistiques
    function toggleStats() {
        var statsContent = document.getElementById('statistiquesContent');
        var statButton = document.getElementById('statButton');
        
        // Si le contenu est actuellement caché, on l'affiche
        if (statsContent.style.display === 'none') {
            statsContent.style.display = 'block';
            statButton.textContent = 'Hide statistics'; // Changer le texte du bouton
        } else {
            statsContent.style.display = 'none';
            statButton.textContent = 'Show statistics'; // Revenir au texte initial
        }
        
        // Charger le graphique lorsque les statistiques sont affichées
        loadChart();
    }

    // Charger et afficher le graphique
    
<?php endif; ?>

function loadChart() {
    var ctx = document.getElementById('statChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Intelligence Artificielle', 'Lancer sa Startup de A à Z', 'Stratégies de marketing digital', 'Entrepreneuriat et Business'],
            datasets: [
                {
                    label: 'Nombre de vues',
                    data: [77, 51, 48, 40],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Note moyenne',
                    data: [4.5, 4.0, 3.8, 4.3],
                    backgroundColor: 'rgba(255, 206, 86, 0.6)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Valeurs'
                    }
                }
            }
        }
    });
}
</script>

<script>
    // Remplacer les données statiques par des données dynamiques PHP
    var labels = <?php echo $labelsJson; ?>;
    var vues = <?php echo $vuesJson; ?>;
    var notes = <?php echo $notesJson; ?>;

    // Charger le graphique avec ces données
    loadChart();
</script>


    </div>
  </div>
  
  <!-- Message de retour -->
  <?php if (!empty($message)): ?>
          <p style="color: <?= str_starts_with($message, '✅') ? 'green' : 'red' ?>;">
            <?= htmlspecialchars($message) ?>
          </p>
        <?php endif; ?>

        <!-- Tableau des cours -->
        <?php if (!empty($courses)): ?>
          <br><br>
          <center>
        <h1>Courses List</h1>
        </center>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Date</th>
              <th>Titre</th>
              <th>Description</th>
              <th>Prix</th>
              <th>Note</th>
              <th>Vues</th>
              <th>Image</th>
              <th>Export</th>
              <th>Contenu</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
              <td><?= $course['id'] ?></td>
              <td><?= date("d/m/Y", strtotime($course['DateAjout'])) ?></td>
              <td><?= htmlspecialchars($course['Titre']) ?></td>
              <td><?= htmlspecialchars($course['Description']) ?></td>
              <td><?= $course['Prix'] ?></td>
              <td><?= $course['Notes'] ?></td>
              <td><?= $course['NbrVu'] ?></td>
              <td>
                <?php if ($course['ImgCover']): ?>
                  <img src="<?= $course['ImgCover'] ?>" alt="Cover">
                <?php endif; ?>
              </td>
              <td>
                <?php if ($course['Exportation']): ?>
                  <a href="<?= $course['Exportation'] ?>" target="_blank">Inspect</a>
                <?php endif; ?>
              </td>
              <td><a href="CoursContenu.php?id=<?= $course['id'] ?>" class="btn btn-sm btn-primary">+</a></td>
              <td class="btn-actions">
  <a href="?delete=<?= $course['id'] ?>" class="btn-delete" title="Supprimer">
    <i class="fas fa-trash"></i>
  </a>
  <a href="?update=<?= $course['id'] ?>" class="btn-edit" title="Modifier">
    <i class="fas fa-edit"></i>
  </a>
</td>

            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p>No course found.</p>
        <?php endif; ?>
      </div>
      <style>
        table {
  width: 100%;
  border-collapse: collapse;
  background-color: #ffffff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  border-radius: 8px;
  overflow: hidden;
  font-family: Arial, sans-serif;
}

thead {
  background-color: #003366;
  color: #ffffff;
}

thead th {
  padding: 12px 10px;
  text-align: left;
}

tbody td {
  padding: 10px;
  border-bottom: 1px solid #e0e0e0;
}

tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

img {
  width: 60px;
  height: auto;
  border-radius: 4px;
}

a.btn {
  padding: 6px 10px;
  background-color: #0057a3;
  color: #fff;
  text-decoration: none;
  border-radius: 5px;
  font-size: 14px;
}

a.btn:hover {
  background-color: #003f7a;
}

.btn-actions {
  display: flex;
  gap: 8px;
  align-items: center;
}

.btn-delete, .btn-edit {
  color: #fff;
  padding: 6px 8px;
  border-radius: 4px;
  display: inline-block;
}

.btn-delete {
  background-color: #d9534f;
}

.btn-edit {
  background-color: #5bc0de;
}

.btn-delete:hover {
  background-color: #c9302c;
}

.btn-edit:hover {
  background-color: #31b0d5;
}

      </style>

  <script src="cours.js"></script>
</body>
</html>
