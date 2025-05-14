<?php
require_once(__DIR__ . '/../../../Model/Formation.php');
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

$formationModel = new Formation();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['edit'])) {
    // Collect form data excluding the formation_id
    $class = $_POST['class'];
    $date = $_POST['date'];
    $desc = $_POST['desc_form'];
    $price = $_POST['price_form'];
    $url = $_POST['url_form'];
    $duration = $_POST['duration_form'];
    $capacity = $_POST['capacity_form'];
    $image = $_FILES['image_form'];   

    // Validate if required fields are not empty
    if ( empty($class) || empty($date) || empty($desc) || empty($price) || empty($url) || empty($duration) || empty($capacity)|| empty($image)) {
        echo "<script>alert('Please fill all the required fields.');</script>";
    } else {
        // Add the formation if all fields are valid
        $formationModel->addFormation($class, $date, $desc, $price, $url, $duration, $capacity,$image);
        header("Location: formations.php");
        exit();
    }
}

// Handle update request if 'edit' is set
if (isset($_POST['edit'])) {
    $id_form = $_POST['id_form'];
    $class_form = $_POST['class_form'];
    $date_form = $_POST['date_form'];
    $desc_form = $_POST['desc_form'];
    $price_form = $_POST['price_form'];
    $url_form = $_POST['url_form'];
    $duration_form = $_POST['duration_form'];
    $capacity_form = $_POST['capacity_form'];
    $image_form = $_POST['image_form'];
    

    // Validate if required fields are not empty
    if (empty($id_form) || empty($class_form) || empty($date_form) || empty($desc_form) || empty($price_form) || empty($url_form) || empty($duration_form) || empty($capacity_form)|| empty($image_form)) {
        echo "<script>alert('Please fill all the required fields.');</script>";
    } else {
        // Update formation if all fields are valid
        $formationModel->updateFormation($id_form, $class_form, $date_form, $desc_form, $price_form, $url_form, $duration_form, $capacity_form, $image_form);
        header("Location: formations.php");
        exit();
    }
}

// Handle deletion of a formation
if (isset($_GET['delete_id'])) {
    $id_form = $_GET['delete_id'];
    $formationModel->deleteFormation($id_form);
    header("Location: formations.php");
    exit();
}

$formations = $formationModel->getAllFormations();
$widgets = $formationModel->getWidgets();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formations | NextStep</title>
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
        <a href="http://localhost/4Validation/View/EVENT/BackOffice/Event.php">
          <span class="material-symbols-sharp">receipt_long</span>
          <h3>Events</h3>
        </a>
        <a href="http://localhost/4Validation/View/EVENT/BackOffice/participants.php">
          <span class="material-symbols-sharp">rocket_launch</span>
          <h3>Participants</h3>
        </a>
        <a href="http://localhost/4Validation/View/Formation/BackOffice/formations.php"class="active">
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

        <main id="main-content">
            <div class="formation-content">
              <div class="formations-header">
  <h1>Formations List</h1>
  <h2>Add New Formation</h2>
</div>
<style>
  .formations-header {
  text-align: center;
  margin: 2rem 0;
}

.formations-header h1 {
  font-size: 2.4rem;
  color: #2c3e50;
  margin-bottom: 0.5rem;
}

.formations-header h2 {
  font-size: 1.6rem;
  color: #34495e;
  font-weight: normal;
}

</style>
                <form id="addFormationForm" action="formations.php" method="POST" enctype="multipart/form-data">
    <textarea name="class" placeholder="Type"></textarea><br>
    <textarea name="desc_form" placeholder="Description"></textarea><br>
    <input type="date" name="date"><br>
    <input type="text" name="price_form" placeholder="Formation price"><br>
    <input type="text" name="url_form" placeholder="URL-Link"><br>
    <input type="text" name="duration_form" placeholder="Duration"><br>
    <input type="text" name="capacity_form" placeholder="Formation Capacity"><br>
    <input type="file" name="image_form" accept="image/*"><br> <!-- Image upload field -->
    <button type="submit">Add Formation</button>
</form>
<style>
  /* Style principal pour la section du formulaire */
#main-content {
  padding: 2rem;
  background-color: #f9f9f9;
  border-radius: 8px;
}

/* Titre du formulaire */
#main-content h1, #main-content h2 {
  color: #333;
  font-size: 2rem;
  margin-bottom: 1rem;
}

/* Conteneur du formulaire */
.formation-content {
  background-color: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Style pour les champs de texte (input, textarea) */
input[type="text"], input[type="date"], textarea {
  width: 100%;
  padding: 0.8rem;
  margin-bottom: 1rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1rem;
  background-color: #f9f9f9;
  transition: border-color 0.3s ease;
}

input[type="text"]:focus, input[type="date"]:focus, textarea:focus {
  border-color: #28a745; /* Bordure verte au focus */
  outline: none;
}

/* Style pour le bouton */
button[type="submit"] {
  width: 100%;
  padding: 1rem;
  background-color: #007bff; /* Bleu */
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

button[type="submit"]:hover {
  background-color: #0056b3; /* Bleu foncé au survol */
  transform: translateY(-2px); /* Légère élévation */
}

button[type="submit"]:active {
  background-color: #004085; /* Bleu très foncé lors du clic */
  transform: translateY(0); /* Retour à la position d'origine */
}

/* Styles pour le champ de fichier (image) */
input[type="file"] {
  margin-bottom: 1rem;
}

/* Erreurs de validation (si nécessaire) */
span.error {
  color: red;
  font-size: 0.9rem;
}

</style>
                <hr>

                <!-- Table of Existing Formations -->
                <h2>Existing Formations</h2>
                <table>
                    <tr>
                        <th>ID</th><th>Class</th><th>Date</th><th>Description</th><th>Price</th><th>URL</th><th>Duration</th><th>Capacity</th><th>Image</th><th>Actions</th>
                    </tr>
                    <?php foreach ($formations as $formation): ?>
                        <tr>
    <td><?= htmlspecialchars($formation['id_form']) ?></td>
    <td><?= htmlspecialchars($formation['class_form']) ?></td>
    <td><?= htmlspecialchars($formation['date_form']) ?></td>
    <td><?= htmlspecialchars($formation['desc_form']) ?></td>
    <td><?= htmlspecialchars($formation['price_form']) ?></td>
    <td><?= htmlspecialchars($formation['url_form']) ?></td>
    <td><?= htmlspecialchars($formation['duration_form']) ?></td>
    <td><?= htmlspecialchars($formation['capacity_form']) ?></td>
    <td>
        <img src="<?= htmlspecialchars($formation['image_form']) ?>" alt="Formation Image" style="max-width: 100px; max-height: 100px;">
    </td>
    <td>
        <a href="formations.php?edit_id=<?= $formation['id_form'] ?>">Edit</a> |
        <a href="formations.php?delete_id=<?= $formation['id_form'] ?>" onclick="return confirm('Delete this formation?')">Delete</a>
    </td>
</tr>
                    <?php endforeach; ?>
                </table>
                <style>
                  /* Style principal pour le tableau des formations */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
  font-family: Arial, sans-serif;
}

th, td {
  padding: 1rem;
  text-align: left;
  border: 1px solid #ddd;
  font-size: 1rem;
}

/* En-tête du tableau */
th {
  background-color: #007bff;
  color: white;
  font-size: 1.2rem;
  font-weight: bold;
}

/* Ligne paire du tableau */
tr:nth-child(even) {
  background-color: #f9f9f9;
}

/* Style des liens (actions) dans le tableau */
a {
  color: #007bff;
  text-decoration: none;
  font-size: 1rem;
  margin-right: 0.5rem;
}

a:hover {
  text-decoration: underline;
  color: #0056b3;
}

/* Style pour les images de formation */
td img {
  max-width: 100px;
  max-height: 100px;
  object-fit: cover;
  border-radius: 4px;
}

/* Style des actions (modification et suppression) */
td a {
  padding: 0.3rem 0.6rem;
  background-color: #28a745; /* Vert pour Edit */
  border-radius: 4px;
  color: white;
  transition: background-color 0.3s ease;
}

td a:hover {
  background-color: #218838; /* Vert foncé au survol */
}

/* Lien de suppression */
td a[href*="delete"] {
  background-color: #dc3545; /* Rouge pour Delete */
}

td a[href*="delete"]:hover {
  background-color: #c82333; /* Rouge foncé au survol */
}

/* Style pour le message d'absence de données */
p.no-data {
  text-align: center;
  font-size: 1.2rem;
  color: #777;
}

                </style>

                <?php
                if (isset($_GET['edit_id'])):
                    $id_form = $_GET['edit_id'];
                    $formation = $formationModel->getFormationById($id_form);
                ?>
                    <h2>Edit Formation</h2>
                    <form action="formations.php" method="POST">
                        <input type="text" name="id_form" value="<?= htmlspecialchars($formation['id_form']) ?>"><br>
                        <input type="text" name="class_form" value="<?= htmlspecialchars($formation['class_form']) ?>"><br>
                        <textarea name="desc_form"><?= htmlspecialchars($formation['desc_form']) ?></textarea><br>
                        <input type="date" name="date_form" placeholder="Date" value="<?= htmlspecialchars($formation['date_form']) ?>"><br>
                        <input type="text" name="price_form" placeholder="Price" value="<?= htmlspecialchars($formation['price_form']) ?>"><br>
                        <input type="text" name="url_form" placeholder="URL-Link" value="<?= htmlspecialchars($formation['url_form']) ?>"><br>
                        <input type="text" name="duration_form" placeholder="Duration" value="<?= htmlspecialchars($formation['duration_form']) ?>"><br>
                        <input type="text" name="capacity_form" placeholder="Capacity" value="<?= htmlspecialchars($formation['capacity_form']) ?>"><br>
                        <button type="submit" name="edit">Update Formation</button>
                    </form>
                <?php endif; ?>

                <hr>

                <h2>Formation Widgets</h2>
                <div class="widget-container">
                    <?php foreach ($widgets as $widget): ?>
                        <div class="widget-card">
                            <h3><?= htmlspecialchars($widget['title']) ?></h3>
                            <p><?= htmlspecialchars($widget['content']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <style>
              /* Style principal pour la section des widgets */
.widget-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1rem;
  margin-top: 2rem;
}

/* Carte de chaque widget */
.widget-card {
  background-color: #ffffff;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 1rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.widget-card h3 {
  font-size: 1.25rem;
  margin-bottom: 1rem;
  color: #007bff;
}

.widget-card p {
  font-size: 1rem;
  color: #555;
  line-height: 1.6;
}

/* Hover effect pour la carte */
.widget-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

/* Titres de section */
h2 {
  font-size: 2rem;
  color: #333;
  margin-bottom: 1.5rem;
}

/* Style de la ligne horizontale */
hr {
  border: 0;
  border-top: 2px solid #007bff;
  margin: 1.5rem 0;
}

            </style>
            
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
        document.addEventListener('DOMContentLoaded', function () {
    const addFormationForm = document.getElementById('addFormationForm');
    const classFormInput = addFormationForm.querySelector('textarea[name="class"]');
    const dateInput = addFormationForm.querySelector('input[name="date"]');
    const urlInput = addFormationForm.querySelector('input[name="url_form"]');

    if (!addFormationForm || !classFormInput || !dateInput || !urlInput) return; // Ensure form, class input, date input, and URL input exist

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);

    // Real-time validation for class_form (letters and spaces only)
    classFormInput.addEventListener('input', function () {
        validateClassField(classFormInput);
    });

    // On form submit, validate all fields
    addFormationForm.addEventListener('submit', function (e) {
        let isValid = true;
        const inputs = addFormationForm.querySelectorAll('input, textarea');

        // Remove old error messages
        addFormationForm.querySelectorAll('.error-msg').forEach(msg => msg.remove());

        inputs.forEach(input => {
            const value = input.value.trim();
            const name = input.getAttribute('name');

            input.style.border = ""; // Reset border

            // Check for empty fields
            if (value === '') {
                isValid = false;
                showError(input, "This field is required");
            } else {
                // Additional validations
                if (name === 'price_form' && isNaN(value)) {
                    isValid = false;
                    showError(input, "Price can only be a number");
                }

                if (name === 'capacity_form' && (!Number.isInteger(Number(value)) || Number(value) < 1)) {
                    isValid = false;
                    showError(input, "Capacity must be a positive number");
                }

                // Validate date input (must be present or future)
                if (name === 'date') {
                    const selectedDate = new Date(value);
                    const currentDate = new Date();
                    currentDate.setHours(0, 0, 0, 0); // Set to the start of the day for comparison

                    if (selectedDate < currentDate) {
                        isValid = false;
                        showError(input, "Set date cannot be older");
                    }
                }

                // Validate URL input (specific format)
                if (name === 'url_form') {
                    const urlRegex = /^www\.google\.meet\/[A-Za-z0-9]+$/; // Updated URL format validation for AAAAA
                    if (!urlRegex.test(value)) {
                        isValid = false;
                        showError(input, "The URL link must be in the  www.google.meet/AAAAA format .");
                    }
                }

                // Validate class_form (only letters and spaces)
                if (name === 'class') {
                    if (!/^[A-Za-zÀ-ÿ\s]+$/.test(value)) { // Letters and spaces only
                        isValid = false;
                        showError(input, "Le champ classe ne peut contenir que des lettres et des espaces.");
                    }
                }
            }
        });

        if (!isValid) {
            e.preventDefault(); // Prevent form submission if any field is invalid
        }
    });

    // Function to show error messages
    function showError(input, message) {
        input.style.border = "2px solid red"; // Highlight field with invalid input
        const error = document.createElement('span');
        error.classList.add('error-msg');
        error.style.color = 'red';
        error.style.fontSize = '0.9rem';
        error.textContent = message;
        input.after(error);
    }

    // Function to validate class_form field (only letters and spaces)
    function validateClassField(input) {
        const value = input.value.trim();
        const regex = /^[A-Za-zÀ-ÿ\s]*$/; // Allow only letters and spaces

        if (!regex.test(value)) {
            input.style.border = "2px solid red"; // Highlight field with invalid input
            let errorMsg = input.nextElementSibling;
            if (!errorMsg || !errorMsg.classList.contains('error-msg')) {
                showError(input, "The class field can only contain Letters and Spaces");
            }
        } else {
            input.style.border = ""; // Reset border if valid
            let errorMsg = input.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('error-msg')) {
                errorMsg.remove(); // Remove error message
            }
        }
    }
});
    </script>

</body>
</html>
