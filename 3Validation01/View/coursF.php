<?php
require_once '../config.php';

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
    $stmt = $pdo->query("SELECT * FROM cours $orderBy");
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
    <title>Cours disponibles</title>
    <link rel="stylesheet" href="coursF.css" />
    <link rel="stylesheet" href="front.css" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
 <header> <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <a href="index.html">
                <img src="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png" alt="Logo" class="logo-img" />
            </a>
            Next<span>Step</span>
        </div>

        <ul class="nav-links">
            <li class="dropdown">
                <button class="dropbtn">Incubator <i class="fas fa-chevron-down"></i></button>
                <ul class="dropdown-menu">
                    <li><a href="incubator.html#nitro-section"><i class="fas fa-bolt"></i> Nitro Plans</a></li>
                    <li><a href="incubator.html#workspace-section"><i class="fas fa-chair"></i> Working Space</a></li>
                    <li><a href="incubator.html#workshop-section"><i class="fas fa-chalkboard-teacher"></i> Workshops</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <button class="dropbtn">Explore Opportunities <i class="fas fa-chevron-down"></i></button>
                <ul class="dropdown-menu">
                    <li><a href="#"><i class="fas fa-lightbulb"></i> Innovative Projects</a></li>
                    <li><a href="#"><i class="fas fa-users"></i> Collaborative Ventures</a></li>
                    <li><a href="#"><i class="fas fa-dollar-sign"></i> Funding Opportunities</a></li>
                    <li><a href="#"><i class="fas fa-handshake"></i> Partnerships</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <button class="dropbtn">Our Courses <i class="fas fa-chevron-down"></i></button>
                <ul class="dropdown-menu">
                    <li><a href="#"><i class="fas fa-rocket"></i> Entrepreneurship Basics</a></li>
                    <li><a href="#"><i class="fas fa-chart-line"></i> Business Strategies</a></li>
                    <li><a href="#"><i class="fas fa-lightbulb"></i> Innovation Workshops</a></li>
                    <li><a href="#"><i class="fas fa-user-tie"></i> Leadership Programs</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <button class="dropbtn">Why Us <i class="fas fa-chevron-down"></i></button>
                <ul class="dropdown-menu">
                    <li><a href="#"><i class="fas fa-cogs"></i> How It Works</a></li>
                    <li><a href="#"><i class="fas fa-trophy"></i> Success Stories</a></li>
                    <li><a href="#"><i class="fas fa-tags"></i> Pricing</a></li>
                    <li><a href="#"><i class="fas fa-question-circle"></i> FAQ</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <button class="dropbtn">Startup <i class="fas fa-chevron-down"></i></button>
                <ul class="dropdown-menu">
                    <li><a href="startup.html"><i class="fas fa-cogs"></i> Startup</a></li>
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

        <button class="login-btn"><i class="fas fa-user"></i> Log In</button>

        <div class="container" id="container" style="display: none;">
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
</header>
    
    
    <!-- Section Cours -->
    <section class="Cours cours-page">
        <div class="container">
            <br><br>
              <h1>Catalogue des Cours</h1>
            <br><br>

            <!-- Formulaire de tri -->
            <form method="GET" action="">
                <label for="sort">Trier par :</label>
                <select name="sort" id="sort">
                    <option value="price_asc">Prix croissant</option>
                    <option value="price_desc">Prix décroissant</option>
                    <option value="views">Nombre de vues</option>
                    <option value="rating">Notes</option>
                </select>
                <button type="submit">Trier</button>
            </form>

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
