<?php
ob_start(); 
?>
<?php
require_once __DIR__ . '/../../../Controller/eventController.php';
require_once __DIR__ . '/../../../Controller/rejoindreController.php';
$eventC = new EventC();
$events = $eventC->listEvents();
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'asc'; // Default to 'asc'

// Call the function to get filtered and sorted events
$events = $eventC->searchAndSortEvents($searchTerm, $sortOrder);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements - NextStep</title>
    <link rel="stylesheet" href="styles.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .container {
          max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
        }
        .section-title {
    text-align: center;
    font-size: 2.8rem;
    margin-bottom: 40px;
    color: #2c3e50;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-weight: bold;
}
.container h1 {
    font-size: 3rem;
    text-align: center;
    color: #fff;
    background: linear-gradient(to right, #ff6b6b, #5f27cd);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 30px;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
}

.container h1::after {
  content: '';
  width: 80px;
  height: 4px;
  background: #007bff;
  display: block;
  margin: 10px auto 0;
  border-radius: 10px;
}
        
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 40px;
        }
        
        /* EVENTS GRID */
        .events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    padding: 30px 10px;
}

        
        /* EVENT CARD */
        .event-card {
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    backdrop-filter: blur(8px);
    animation: fadeSlideIn 0.6s forwards;
    animation-delay: calc(var(--i) * 0.1s);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
@keyframes fadeSlideIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.event-card:hover {
    transform: scale(1.03) translateY(-5px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
}

        
        /* EVENT IMAGE */
        .event-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    transition: transform 0.5s ease;
}

.event-card:hover .event-image {
    transform: scale(1.05);
}

        
        .event-content {
            padding: 10px 15px;
    background-color: #ffffff;
    border-top: 3px solid #3498db;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease-in-out;
}

.event-title {
    margin: 0;
    padding: 10px 5px;
    font-size: 1.5rem;
    color: #000 !important;
    margin-bottom: 15px;
    font-style: italic;
    display: flex !important;
    align-items: center;
    gap: 8px;
    opacity: 1 !important;

}

.event-date {
    
    font-size: 0.95rem;
    color: #7f8c8d;
    margin-bottom: 15px;
    font-style: italic;
    display: flex;
    align-items: center;
    gap: 8px;
}

.event-date::before {
    content: "📅";
    font-size: 1rem;
}

.event-description {
    margin: 0;
    padding: 10px 0;
    font-size: 1rem;
    color: #555 !important;
    margin-bottom: 50px;
    line-height: 1.6;
    height: auto;
    opacity: 1 !important;
    display: flex ;
}

        
        .btn-participate {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .btn-participate:hover {
            background-color: #2980b9;
        }
        
        .no-events {
            text-align: center;
            grid-column: 1 / -1;
            padding: 40px;
            color: #7f8c8d;
        }
        /* Style général pour le formulaire */
.event-filter-form {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin: 20px 0;
    flex-wrap: wrap;
}

/* Style pour le champ de recherche */
.search-field {
    width: 300px;
    padding: 12px 20px;
    font-size: 16px;
    border: 2px solid #3498db;
    border-radius: 25px;
    outline: none;
    transition: border-color 0.3s ease;
    background-color: #f9f9f9;
}

.search-field:focus {
    border-color: #2980b9;
}

.search-field::placeholder {
    color: #aaa;
    font-style: italic;
}

/* Style pour le menu déroulant de tri */
.sort-dropdown {
    padding: 12px 20px;
    font-size: 16px;
    border: 2px solid #3498db;
    border-radius: 25px;
    background-color: #f9f9f9;
    outline: none;
    transition: border-color 0.3s ease;
    color: #333;
    width: 200px;
}

.sort-dropdown:focus {
    border-color: #2980b9;
}

/* Style pour le bouton de soumission */
.submit-btn {
    padding: 12px 25px;
    font-size: 16px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #2980b9;
}

/* Espacement et alignement des éléments */
.event-filter-form input, .event-filter-form select, .event-filter-form button {
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .event-filter-form {
        flex-direction: column;
        align-items: stretch;
    }
    .event-filter-form input, .event-filter-form select, .event-filter-form button {
        width: 100%;
    }
}
.btn-detail {
    background: linear-gradient(to right, #f39c12, #e74c3c);
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.4s, transform 0.2s;
}

.btn-detail:hover {
    background: linear-gradient(to right, #e67e22, #c0392b);
    transform: scale(1.05);
}


.btn-detail:focus {
    outline: none; /* Enlever le contour lors du focus */
}

.btn-detail:active {
    background-color: #1c598d; /* Couleur de fond lors du clic */
    transform: translateY(0); /* Retirer l'élévation lors du clic */
    box-shadow: none; /* Enlever l'ombre lors du clic */
}
.hero-video-section {
    position: relative;
    width: 100%;
    height: 60vh;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-video {
    width: 100%;
    height: 100%;
    border-bottom: 2px solid #3498db;
    object-fit: cover;
    filter: brightness(60%);
    border-bottom-left-radius: 25px;
    border-bottom-right-radius: 25px;
}

.hero-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    text-align: center;
    z-index: 2;
    padding: 20px;
}

.hero-title {
    color: #ffffff;
    font-size: 3.5rem;
    font-weight: 800;
    letter-spacing: -1px;
    margin-bottom: 20px;
    text-align: center;
    background: #fb943b;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: fadeInDown 0.8s ease-out;
}

.hero-subtitle {
    color: rgba(255, 255, 255, 0.9) !important;
    font-size: 1.3rem;
    font-weight: 400;
    max-width: 680px;
    margin: 0 auto 10px auto;
    text-align: center;
    backdrop-filter: blur(3px);
    background-color: rgba(255, 255, 255, 0.05);
    padding: 10px 20px;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    animation: fadeInUp 1s ease-out;
    visibility: visible !important;
    opacity: 1 !important;
    display: block !important;
}

/* Animations */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}


@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }

    .hero-subtitle {
        font-size: 1rem;
    }



}
.input-group {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    background-color: #f1f1f1;
    padding: 8px 12px;
    border-radius: 8px;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
}

.input-group i {
    color: #555;
    margin-right: 10px;
}

.search-field, .sort-dropdown {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 1rem;
}

.footer-copyright .copyright-text {
  margin: 0;
  font-size: 1rem;
  color: #ffffff !important;
  visibility: visible !important;
  opacity: 1 !important;
  display: block !important;
  font-weight: 500;
  letter-spacing: 0.5px;
  font-family: 'Segoe UI', sans-serif;
}


    </style>
</head>
<body>
<nav class="navbar">
<div class="logo">
        <img
          src="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png"
          alt="Logo"
          class="logo-img"
        />
        Next<span>Step</span>
      </div>
      <ul class="nav-links">
        <li class="dropdown">
          <button class="dropbtn">
            Explore Opportunities <i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li>
              <a href="#"
                ><i class="fas fa-lightbulb"></i> Innovative Projects</a
              >
            </li>
            <!-- Icon for Innovation -->
            <li>
              <a href="#"
                ><i class="fas fa-users"></i> Collaborative Ventures</a
              >
            </li>
            <!-- Icon for Collaboration -->
            <li>
              <a href="#"
                ><i class="fas fa-dollar-sign"></i> Funding Opportunities</a
              >
            </li>
            <!-- Icon for Funding -->
            <li>
              <a href="#"><i class="fas fa-handshake"></i> Partnerships</a>
            </li>
            <!-- Icon for Partnerships -->
          </ul>
        </li>
        <li class="dropdown">
          <button class="dropbtn">
            Our Courses <i class="fas fa-chevron-down"></i>
            <!-- Changed to 'Our Courses' -->
          </button>
          <ul class="dropdown-menu">
            <li>
              <a href="coursF.html">
              <i class="fas fa-book"></i> Courses</a>
          </li>
            <li>
              <a href="#"
                ><i class="fas fa-rocket"></i> Entrepreneurship Basics</a
              >
            </li>
            <li>
              <a href="#"
                ><i class="fas fa-chart-line"></i> Business Strategies</a
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
              <a href="eventsF.php"
                ><i class="fas fa-calendar-alt"></i> Our Events</a
              >
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <button class="dropbtn">
            Incubator <i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li><a href="incubator.html #nitro-section"><i class="fas fa-bolt"></i> Nitro Plans</a></li>
            <li><a href="incubator.html #workspace-section"><i class="fas fa-chair"></i> Working Space</a></li>
            <li><a href="incubator.html #workshop-section"><i class="fas fa-chalkboard-teacher"></i> Workshops</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <button class="dropbtn">
            Startup <i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li>
              <a href="startup.html"><i class="fas fa-cogs"></i> Startup</a>
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <button class="dropbtn">
            Why Us <i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li>
              <a href="#"><i class="fas fa-cogs"></i> How It Works</a>
            </li>
            <li>
              <a href="#"><i class="fas fa-trophy"></i> Success Stories</a>
            </li>
            <li>
              <a href="#"><i class="fas fa-tags"></i> Pricing</a>
            </li>
            <li>
              <a href="#"><i class="fas fa-question-circle"></i> FAQ</a>
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
      <button class="login-btn"><i class="fas fa-user"></i> Log In</button>
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
    <section class="hero-video-section">
    <video autoplay muted loop playsinline class="hero-video">
        <source src="image/video2.mp4" type="video/mp4">
        Votre navigateur ne supporte pas la vidéo HTML5.
    </video>
    <div class="hero-overlay">
        <h1 class="hero-title">Bienvenue à Nos Événements</h1>
        <p class="hero-subtitle">Participez à des expériences inoubliables organisées tout au long de l'année</p>
    </div>
</section>

    <div class="search-sort-container">
    <form method="GET" action="eventsF.php" class="event-filter-form">
    <!-- Champ de recherche avec icône -->
    <div class="input-group">
        <i class="fas fa-search"></i>
        <input type="text" name="search" class="search-field" placeholder="Rechercher un événement"
            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
    </div>

    <!-- Menu déroulant avec icône -->
    <div class="input-group">
        <i class="fas fa-sort"></i>
        <select name="sort" id="sort" class="sort-dropdown">
            <option value="asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'asc' ? 'selected' : '' ?>>Trier par date (Ascendant)</option>
            <option value="desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'selected' : '' ?>>Trier par date (Descendant)</option>
        </select>
    </div>

    <!-- Bouton avec icône -->
    <button type="submit" class="submit-btn">
        <i class="fas fa-filter"></i> Filtrer
    </button>
</form>

</div>
<style>
  .pagination-container {
    display: flex;
    justify-content: center;
    align-items: center ;
    margin: 50px 0;
}

.pagination {
    display: flex;
    gap: 10px;
    list-style: none;
    padding: 0;
    margin: 0;
    flex-wrap: wrap;
}

.pagination li {
  display: inline-block;
    margin: 0 5px;
    transition: transform 0.2s ease;
}

.pagination li a {
    padding: 10px 18px;
    background: linear-gradient(to right, #3498db, #5dade2);
    color: white !important;
    font-weight: 600;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: background 0.3s, transform 0.2s;
}

.pagination li a:hover {
    background: linear-gradient(to right, #2980b9, #3498db);
    transform: translateY(-2px);
}

.pagination li.active a {
    background: #2c3e50;
    color: #fff;
    cursor: default;
    pointer-events: none;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.pagination li.disabled a {
    background-color: #ccc;
    color: #777;
    pointer-events: none;
}
</style>
<div class="container">
    <h1>Nos Événements à Venir</h1>
    <div class="events-grid">
        <?php if (empty($events)): ?>
            <div class="no-events">
                <p>Aucun événement prévu pour le moment. Revenez plus tard!</p>
            </div>
        <?php else: ?>
          <?php
              // Pagination
              $eventsPerPage = 6;
              $totalEvents = count($events);
              $totalPages = ceil($totalEvents / $eventsPerPage);

              // Obtenir la page actuelle via l'URL, par défaut 1
              $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

              // Décalage (OFFSET)
              $offset = ($currentPage - 1) * $eventsPerPage;

              // Découper le tableau d'événements
              $paginatedEvents = array_slice($events, $offset, $eventsPerPage);
              ?>

              <?php foreach ($paginatedEvents as $event): ?>

                <div class="event-card">
                    <?php
                    $defaultImage = '/Web project 2025/uploads/events/default-event.jpg';
                    $imageFile = !empty($event['img_event']) ? basename($event['img_event']) : 'default-event.jpg';

                    // Full path to check file existence (for PHP)
                    $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/Web project 2025/View/BackOffice/uploads/events/' . $imageFile;

                    // Web path used in <img src="">
                    $webPath = '/Web project 2025/View/BackOffice/uploads/events/' . $imageFile;

                    // If file doesn't exist, fallback to default image
                    if (!file_exists($fullPath)) {
                        $webPath = $defaultImage;
                    }
                    ?>

                    <img src="<?= htmlspecialchars($webPath) ?>" alt="<?= htmlspecialchars($event['nom_event']) ?>" class="event-image">

                    <div class="event-content">
                        <h2 class="event-title"><?= htmlentities($event['nom_event'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <br><br>
                        <div class="event-date"><?= date('d/m/Y', strtotime($event['date_event'])) ?></div>
                        
                        <button class="btn-detail" onclick="openModal({
    id_event: <?= $event['id_event']; ?>,
    title: '<?= addslashes($event['nom_event']); ?>',
    date: '<?= $event['date_event']; ?>',
    location: '<?= addslashes($event['lieu']); ?>',
    description: '<?= addslashes($event['desc_event']); ?>',
    image: '<?= addslashes($event['img_event']); ?>'
})">
    Voir Détails
</button>

                    </div>
                </div>
                
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php if ($totalPages > 1): ?>
<div class="pagination-container">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="<?= ($i == $currentPage) ? 'active' : '' ?>">
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</div>
<?php endif; ?>
</div>




<!-- Modal structure -->
<div id="eventModal" style="display: none;">
    <div id="modalContent" style="
        background: white;
        max-width: 600px;
        overflow-y: auto;
        height: 80vh;
        margin: 10vh auto;
        padding: 30px;
        border-radius: 10px;
        position: relative;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    ">
        <span id="closeModal" style="
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
            font-size: 1.5rem;
            font-weight: bold;
        ">&times;</span>

        <img id="modalImage" src="" alt="Event Image" style="width: 100%; height: auto; border-radius: 10px; margin-bottom: 20px; visibility: visible !important; opacity: 1 !important; display: block;">
        <h2 id="modalTitle"></h2>
        <!-- Ensure you have included Font Awesome in the <head> of your HTML -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<p><strong><i class="fas fa-calendar-day"></i> Date:</strong> <span id="modalDate" style="color: #000"></span></p>
<p><strong><i class="fas fa-map-marker-alt"></i> Lieu:</strong> <span id="modalLocation"></span></p>
<p><strong><i class="fas fa-info-circle"></i> Description:</strong> <span id="modalDescription" style="color: #000"></span></p>
        <br><br>
        <div style="margin-bottom: 25px; text-align: center;">
    <h2 style="font-size: 1.8rem; color: #007bff; margin-bottom: 10px; display: flex; justify-content: center; align-items: center; gap: 10px;">
        <i class="fas fa-calendar-check"></i> Participation à l'événement
    </h2>
    <p style="font-size: 1rem; color: #dc3545; background: #fff3cd; border: 1px solid #ffeeba; padding: 10px 15px; border-radius: 8px; display: inline-block;">
        <i class="fas fa-exclamation-triangle"></i> Veuillez remplir le formulaire avant de participer à l'événement.
    </p>
</div>

        <form id="participationForm" onsubmit="return validateParticipationForm(event)" style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); font-family: 'Segoe UI', sans-serif;">
    <!-- champs cachés -->
    <input type="hidden" name="id_event" value="<?= htmlspecialchars($event['id_event']) ?>">
    <input type="hidden" name="id_user" value="<?= htmlspecialchars($user['id_user']) ?>">

    <div style="margin-bottom: 20px;">
        <label for="telnbr" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">📞 Numéro de téléphone</label>
        <input type="text" id="telnbr" name="telnbr" placeholder="Votre numéro de téléphone" 
               style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem; transition: border 0.3s ease;">
    </div>

    <div style="margin-bottom: 25px;">
        <label for="nbrguest" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">👥 Nombre d'invités</label>
        <input type="number" id="nbrguest" name="nbrguest" placeholder="Nombre d'invités (1-3)"
               style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem; transition: border 0.3s ease;">
    </div>

    <button  type="submit" class="btn-participate" style="width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.3s ease;">
        ✅ Valider la participation
    </button>
</form>

<br>
<br>

        <a id="btnParticipate" class="btn-participate">Participer</a>
        </div>
    </div>
</div>



<!-- Modal Background -->
<style>
    /* MODAL BASE */
#eventModal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.55);
  z-index: 9999;
  display: none;
  justify-content: center;
  align-items: center;
}

/* MODAL CONTENT */
#modalContent {
  animation: slideIn 0.4s ease;
  max-width: 600px;
  width: 95%;
  background: #fff;
  padding: 30px;
  border-radius: 16px;
  position: relative;
}
/* ANIMATION */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
<style>
#modalContent h2,
#modalContent p {
    color: #000 !important;
    visibility: visible !important;
    opacity: 1 !important;
    display: block;
}
</style>

<script>
  function openModal(eventData) {
    document.getElementById("modalTitle").innerText = eventData.title;
    document.getElementById("modalDate").innerText = eventData.date;
    document.getElementById("modalLocation").innerText = eventData.location;
    document.getElementById("modalDescription").innerText = eventData.description;

    const imagePath = eventData.image && eventData.image.trim() !== ''
        ? '/Web project 2025/View/BackOffice/' + eventData.image
        : '/Web project 2025/View/BackOffice/uploads/events/default-event.jpg';
    document.getElementById("modalImage").src = imagePath;
   



    // Redirection bouton participation
    document.getElementById('btnParticipate').href = 'participate.php?event_id=' + encodeURIComponent(eventData.id_event);

    // Affichage du modal
    document.getElementById("eventModal").style.display = "block";
}

  
function validateParticipationForm(event) {
    event.preventDefault(); // Empêche la soumission automatique

    const phoneNumber = document.getElementById('telnbr').value.trim();
    const numberOfGuests = parseInt(document.getElementById('nbrguest').value.trim(), 10);

    // Regex basique pour téléphone : commence par 0 et 9 chiffres ensuite (France)
    const phoneRegex = /^0[1-9][0-9]{8}$/;

    // Validation du téléphone
    if (!phoneRegex.test(phoneNumber)) {
        alert("Veuillez entrer un numéro de téléphone valide (ex : 0612345678).");
        return false;
    }

    // Validation du nombre d'invités
    if (isNaN(numberOfGuests) || numberOfGuests < 1 || numberOfGuests > 3) {
        alert("Veuillez entrer un nombre d'invités entre 1 et 3.");
        return false;
    }

    // Si tout est valide
    alert("Participation validée ! Merci.");
  
}



  document.querySelectorAll('.open-modal').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('modalTitle').textContent = button.dataset.title;
            document.getElementById('modalDate').textContent = button.dataset.date;
            document.getElementById('modalDescription').textContent = button.dataset.description;
            document.getElementById('modalLocation').textContent = button.dataset.location;
            document.getElementById('modalImage').src = button.dataset.image;
            document.getElementById('eventModal').style.display = 'block';
        });
    });
 
    document.getElementById('closeModal').addEventListener('click', () => {
        document.getElementById('eventModal').style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target.id === 'eventModal') {
            document.getElementById('eventModal').style.display = 'block';
        }
    });
</script>
<footer class="footer">
      <div class="container">
        <div class="row">
          <div class="footer-col">
            <h4>company</h4>
            <ul>
              <li><a href="#">about us</a></li>
              <li><a href="#">our services</a></li>
              <li><a href="#">privacy policy</a></li>
              <li><a href="#">affiliate program</a></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>get help</h4>
            <ul>
              <li><a href="#">FAQ</a></li>
              <li><a href="#">shipping</a></li>
              <li><a href="#">returns</a></li>
              <li><a href="#">order status</a></li>
              <li><a href="#">payment options</a></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>follow us</h4>
            <div class="social-links">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <div class="footer-copyright">
  <p class="copyright-text">&copy; <?= date('Y') ?> NextStep.tn , Tous droits réservés.</p>
</div>

          </div>
        </div>
      </div>
   </footer> 
</body>
</html>