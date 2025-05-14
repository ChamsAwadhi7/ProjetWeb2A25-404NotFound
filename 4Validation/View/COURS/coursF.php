<?php
require_once '../../config.php';


session_start();
if (!isset($_SESSION['utilisateur'])) {
    header('Location: ../login_register.php');
    exit;
}
// Exemple : afficher le nom de l'utilisateur connecté
//echo "Bienvenue, " . htmlspecialchars($_SESSION['utilisateur']['nom']) . "!";



// Vérifier si une option de tri est sélectionnée
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'DateAjout DESC';

// Déterminer la logique de tri selon l'option sélectionnée
switch ($sortOption) {
    case 'price_asc':
        $orderBy = "ORDER BY Prix ASC";
        break;
    case 'price_desc':
        $orderBy = "ORDER BY Prix DESC";
        break;
    case 'views':
        $orderBy = "ORDER BY NbrVu DESC";
        break;
    case 'rating':
        $orderBy = "ORDER BY Notes DESC";
        break;
    default:
        $orderBy = "ORDER BY DateAjout DESC";
}

// Récupérer les cours en fonction de l'option de tri sélectionnée
try {

    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($searchTerm !== '') {
    $query = "SELECT * FROM cours WHERE Titre LIKE :search OR Description LIKE :search $orderBy";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['search' => '%' . $searchTerm . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM cours $orderBy");
}
    $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de récupération des cours : " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NextStep | Courses</title>
    <link rel="stylesheet" href="coursF.css" />
    <link rel="stylesheet" href="front.css" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
 <header>  <!-- Navbar -->
    <nav class="navbar">
      <div class="logo">
      <a href="http://localhost/4Validation/View/index.php" style="text-decoration: none; color: inherit;">
        <img
          src="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png"
          alt="Logo"
          class="logo-img"
        />
        </a>
        Next<span>Step</span>
      </div>
      <ul class="nav-links">
        <li class="dropdown">
          <button class="dropbtn">
            HOME <i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
          <li>
              <a href="http://localhost/4Validation/View/index.php#"
                ><i class="fas fa-home"></i> Home</a
              >
            </li>
            <li>
              <a href="#"
                ><i class="fas fa-question-circle"></i> Why Us</a
              >
            </li>

          </ul>
        </li>

        <li class="dropdown">
          <button class="dropbtn">
            Startup <i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li>
              <a href="http://localhost/4Validation/View/STARTUP/FrontOffice/startup.php"><i class="fas fa-lightbulb"></i>Startup</a>
            </li>
          </ul>
        </li>
        <li class="dropdown">
                <button class="dropbtn">
                  Incubator <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="http://localhost/4Validation/View/STARTUP/FrontOffice//incubator.php#nitro-section"><i class="fas fa-bolt"></i> Nitro Plans</a></li>
                  <li><a href="http://localhost/4Validation/View/STARTUP/FrontOffice//incubator.php#workspace-section"><i class="fas fa-chair"></i> Working Space</a></li>
                  <li><a href="http://localhost/4Validation/View/STARTUP/FrontOffice//incubator.php#workshop-section"><i class="fas fa-chalkboard-teacher"></i> Workshops</a></li>
                </ul>
              </li>

        <li class="dropdown">
          <button class="dropbtn">
            Our Courses <i class="fas fa-chevron-down"></i>
            <!-- Changed to 'Our Courses' -->
          </button>
          <ul class="dropdown-menu">
          
            <li>
              <a href="http://localhost/4Validation/View/COURS/coursF.php">
              <i class="fas fa-book"></i> Courses</a>
          </li>
            <li>
              <a href="#"
                ><i class="fas fa-rocket"></i> Entrepreneurship Basics</a
              >
            </li>
            
            <li>
              <a href="#"
                ><i class="fas fa-lightbulb"></i> Innovation Workshops</a
              >
            </li>
            <li>
              <a href="#"
                ><i class="fas fa-user-tie"></i> Leadership Programs</a
              >
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <button class="dropbtn">
            Our Events <i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li>
              <a href="http://localhost/4Validation/View/EVENT/FrontOffice/View/eventsF.php"
                ><i class="fas fa-bullseye"></i> Our Events</a
              >
            </li>
          </ul>
        </li>

        <li class="dropdown">
          <button class="dropbtn">
             Formation<i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li>
              <a href="http://localhost/4Validation/View/Formation/FrontOffice/Formations2.php"><i class="fas fa-chart-line"></i>Formation</a>
            </li>
            <li>
              <a href="http://localhost/4Validation/View/Formation/FrontOffice/Formations2.php"
                ><i class="fas fa-calendar-check"></i> calendar</a
              >
            </li>
          </ul>
        </li>
        
        
      </ul>
      <div class="search-box">
            <input type="text" placeholder="Search..." />
            <select class="search-category">
                <option value="project">🔍 Project</option>
                <option value="startup">🚀 Startup</option>
            </select>
        </div>
        <hr><hr><hr><hr>
        <a href="http://localhost/4Validation/View/profile.php" class="profile-button" title="Mon profil">
         <i class="fas fa-user"></i>
        </a>

        <a href="logout.php"><button class="login-btn"><i class=""></i> ⏻ logout</button></a>
<div class="containerr" id="containerr" style="display: none;">
        <div class="form-container sign-in-container">
            <form action="#">
                <h1>Sign in</h1>
                <span>or use your account</span>
                <input type="email" placeholder="Email" required />
                <input type="password" placeholder="Password" required />
                <a href="#">Forgot your password?</a>
                <button>Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Back to Building the Future!</h1>
                    <p>Your next big idea starts here. Log in to stay connected, collaborate, and turn innovation into impact!</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Join the Movement of Innovators!</h1>
                    <p>The future is shaped by those who dare to create. Sign up now and be part of a community that turns ideas into reality!</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
      </div>
    </nav>
    <section class="coursV" id="coursV">
      <video autoplay muted loop class="background-video">
        <source src="image/video.mp4" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
      <br><br><br><br>
      <h1>Catalogue des Cours</h1>
      </section>

      <style>
        .coursV {
  position: relative;
  width: 104%;
  height: 50vh; /* ou une hauteur fixe comme 500px */
  overflow: hidden;
}

.background-video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover; /* remplit la section sans déformation */
  z-index: -1;
}
.coursV h1 {
  font-size: 3rem;
  color: white;
  z-index: 1;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
  padding: 20px;
  border-radius
      </style>

</header>

  
    <!-- Section Cours -->
    <section class="Cours cours-page">
        <div class="container">
            <!-- Formulaire de tri -->
<form method="GET" action="" class="formulaireTri">
    <label for="sort">Trier par :</label>
    <select name="sort" id="sort">
        <option value="price_asc">Prix croissant</option>
        <option value="price_desc">Prix décroissant</option>
        <option value="views">Nombre de vues</option>
        <option value="rating">Notes</option>
    </select>

    <input type="text" name="search" placeholder="Rechercher un cours..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    <button type="submit">Filtrer</button>
</form>
<style>
  .formulaireTri {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
   
    width: 101%;
}

.formulaireTri label {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    weight: 800;
    margin-left: 3rem;
    
}

.formulaireTri input[type="text"] {
    padding: 0.8rem;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1rem;
    width: 250px;
    min-width: 200px;
    transition: border-color 0.3s ease;
}

.formulaireTri input[type="text"]:focus {
    border-color: #007bff;
    outline: none;
}

.formulaireTri select {
    padding: 0.8rem;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1rem;
    width: 200px;
    min-width: 150px;
    transition: border-color 0.3s ease;
}

form select:focus {
    border-color: #007bff;
    outline: none;
}

form button {
    background-color: #007bff;
    color: white;
    padding: 0.8rem 1.2rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #0056b3;
}
</style>



            <br><br>

            <!-- Affichage des cours -->
            <div class="grid">
                <?php foreach ($cours as $c): ?>
                    <div class="card">
                        <img src="<?= htmlspecialchars($c['ImgCover']) ?>" alt="Couverture du cours" />
                        <div class="content">
                            <div class="title"><?= htmlspecialchars($c['Titre']) ?></div>
                            <div class="price">
                                <?php 
                                    if ($c['Prix'] == 0) {
                                        echo "Gratuit";
                                    } else {
                                        echo number_format($c['Prix'], 2) . " DT";
                                    }
                                ?>
                            </div>
                            <div class="description"><?= substr(htmlspecialchars($c['Description']), 0, 80) ?>...</div>
                            <p class="note">
                                <?php
                                    $fullStars = floor($c['Notes']);
                                    $halfStar = ($c['Notes'] - $fullStars >= 0.5);
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                                    for ($i = 0; $i < $fullStars; $i++) echo '★';
                                    if ($halfStar) echo '☆';
                                    for ($i = 0; $i < $emptyStars; $i++) echo '✩';
                                ?>
                                <span>(<?= number_format($c['Notes'], 1) ?>/5)</span>
                                <span class="views">(Vues: <?= number_format($c['NbrVu']) ?>)</span>
                            </p>
                            <a href="coursF_detail.php?id=<?= $c['id'] ?>" class="btn">Voir détails</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</body>
</html>
