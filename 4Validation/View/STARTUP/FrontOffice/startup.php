<?php
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NextStep | STARTUPS</title>
    <link rel="stylesheet" href="incubator.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="website icon" type="PNG" href="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png" style="width: 80px; height: auto;" />
    <style>
        :root {
            --primary: #6c5ce7;
            --secondary: #a29bfe;
            --accent: #fd79a8;
            --dark: #2d3436;
            --light: #f5f6fa;
            --success: #00b894;
            --warning: #fdcb6e;
            --danger: #d63031;
            --gradient: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9ff;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        /* Header Section */
        .startup {
            position: relative;
            height: 60vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            padding: 0 20px;
        }

        .content h3 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            background: linear-gradient(to right, #fff, #a29bfe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: fadeInDown 1s ease;
        }

        .content p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease 0.3s both;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Main Content */
        .startups {
            padding: 80px 0;
            background-color: #f9f9ff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .container h1 {
            font-size: 3.5rem;
            text-align: center;
            color: transparent;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }

        .container h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--gradient);
            border-radius: 2px;
        }

        .container h3 {
            text-align: center;
            color: #666;
            font-weight: 400;
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 50px;
            line-height: 1.8;
        }

        /* Filter Controls */
        .filter-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .search-container {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-container input {
            width: 100%;
            padding: 12px 45px 12px 20px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .search-container input:focus {
            border-color: var(--primary);
            outline: none;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.1);
        }

        .search-container i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            pointer-events: none;
            font-size: 18px;
        }

        .sort-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sort-container label {
            font-weight: 500;
            color: #555;
            font-size: 15px;
        }

        .sort-container select {
            padding: 10px 15px;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            background-color: #f9f9f9;
            font-size: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .sort-container select:focus {
            border-color: var(--primary);
            background-color: #fff;
            outline: none;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.1);
        }

        /* Startup Cards Grid */
        .startup-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 55px;
            margin-bottom: 50px;
        }

        .startup-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            transform: translateY(0);
            width: 400px;
            padding: 10px; /* Optional for inner spacing */
            margin-bottom: 10px; 
        }

        .startup-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .startup-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient);
        }

        .startup-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
            transition: transform 0.5s ease;
        }

        .startup-card:hover img {
            transform: scale(1.05);
        }

        .startup-card-content {
            padding: 25px;
        }

        .startup-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--dark);
            position: relative;
            padding-bottom: 10px;
        }

        .startup-card h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--secondary);
        }

        .startup-card h5 {
            font-size: 0.95rem;
            margin-bottom: 10px;
            color: #555;
            font-weight: 400;
        }

        .startup-card h5 strong {
            color: var(--dark);
            font-weight: 600;
        }

        .startup-card-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .invest-btn, .delete-btn, .edit-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .invest-btn {
            background: var(--gradient);
            color: white;
            border: none;
            flex: 1;
            justify-content: center;
        }

        .invest-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
        }

        .delete-btn {
            background-color: #ff7675;
            color: white;
            border: none;
        }

        .delete-btn:hover {
            background-color: #d63031;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(214, 48, 49, 0.2);
        }

        .edit-btn {
            background-color: #74b9ff;
            color: white;
            border: none;
        }

        .edit-btn:hover {
            background-color: #0984e3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(9, 132, 227, 0.2);
        }

        /* Start Your Startup Button */
        .start-your-startup {
            text-align: center;
            margin-top: 50px;
        }

        #startStartupBtn {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(108, 92, 231, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        #startStartupBtn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(108, 92, 231, 0.4);
        }

        #startStartupBtn i {
            font-size: 1.2rem;
        }

        /* Startup Form */
        .startup-form-container {
            display: none;
            max-width: 800px;
            margin: 50px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .form-container {
            padding: 40px;
        }

        .form-container h2 {
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        .form-container h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--gradient);
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        .form-container input,
        .form-container textarea,
        .form-container select {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-container input:focus,
        .form-container textarea:focus,
        .form-container select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
        }

        .form-container textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-container button[type="submit"] {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .form-container button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(108, 92, 231, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .content h3 {
                font-size: 2.8rem;
            }
            
            .content p {
                font-size: 1.2rem;
            }
            
            .container h1 {
                font-size: 2.8rem;
            }
            
            .startup-cards {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .filter-controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-container {
                max-width: 100%;
            }
            
            .sort-container {
                width: 100%;
                justify-content: space-between;
            }
            
            .content h3 {
                font-size: 2.2rem;
            }
            
            .container h1 {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 576px) {
            .content h3 {
                font-size: 1.8rem;
            }
            
            .content p {
                font-size: 1rem;
            }
            
            .startup-card-actions {
                flex-direction: column;
            }
            
            .invest-btn, .delete-btn, .edit-btn {
                width: 100%;
                justify-content: center;
            }
            
            .form-container {
                padding: 30px 20px;
            }
        }

        /* Animation for cards */
        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .startup-card {
            animation: cardEntrance 0.5s ease-out;
            animation-fill-mode: backwards;
        }

        /* Delay animations for each card */
        .startup-card:nth-child(1) { animation-delay: 0.1s; }
        .startup-card:nth-child(2) { animation-delay: 0.2s; }
        .startup-card:nth-child(3) { animation-delay: 0.3s; }
        .startup-card:nth-child(4) { animation-delay: 0.4s; }
        .startup-card:nth-child(5) { animation-delay: 0.5s; }
        .startup-card:nth-child(6) { animation-delay: 0.6s; }
        .startup-card:nth-child(7) { animation-delay: 0.7s; }
        .startup-card:nth-child(😎 { animation-delay: 0.8s; }
        .startup-card:nth-child(9) { animation-delay: 0.9s; }
        .startup-card:nth-child(10) { animation-delay: 1s; }

        
    </style>
</head>
<body>
    <header>
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

          <section class="startup" id="startup">
      <video autoplay muted loop class="background-video">
        <source src="image/video.mp4" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
      <div class="overlay"></div>
      <div class="content" id="content">
        <center>
        <h3>START YOUR DREAM</h3>
        <p>
          Build your dream step by step with us
        </p>
        </center>
      </div>
    </section>
    </header>

    <!-- Startup Page Section -->
    <section class="startups">
        <div class="container">
          <br><br>
            <h1>OUR STARTUPS</h1>
            <br><br>
            <h3>Here are some of the exciting startups we're working with. Get inspired and join us!</h3>
            <style>
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
            </style>
            <!-- metier -->
<div class="filter-controls">
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search startups..." onkeyup="searchStartups()">
        <i class="fas fa-search"></i>
    </div>
    <div class="sort-container">
        <label for="sortSelect">Sort by:</label>
        <select id="sortSelect" onchange="sortStartups()">
            <option value="name-asc">Name (A-Z)</option>
            <option value="name-desc">Name (Z-A)</option>
            <option value="date-asc">Date (Oldest)</option>
            <option value="date-desc">Date (Newest)</option>
        </select>
    </div>
</div>

<style>
    .filter-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    margin-bottom: 20px;
    flex-wrap: wrap;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.search-container {
    position: relative;
    flex: 1;
    max-width: 300px;
}

.search-container input {
    width: 100%;
    padding: 10px 40px 10px 15px;
    border: 1px solid #ccc;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
    background-color: #f9f9f9;
}

.search-container input:focus {
    border-color: #007BFF;
    outline: none;
    background-color: #fff;
}

.search-container i {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #888;
    pointer-events: none;
}

.sort-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.sort-container label {
    font-weight: 500;
    color: #333;
}

.sort-container select {
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
    font-size: 15px;
    transition: all 0.3s ease;
}

.sort-container select:focus {
    border-color: #007BFF;
    background-color: #fff;
    outline: none;
}
</style>
            <br><br>
            <div class="startup-cards">
    <?php
  
    require_once '../../../config.php';
    require '../../../Model/startup.php';
    require '../../../Model/incubator.php';
    require '../../../Controller/startupC.php';
    require '../../../Controller/incubatorC.php';

    $startupC = new startupC();
    $nitroC = new nitroC();

    // Pagination logic
    $limit = 6; // Six startups per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    // Get the total number of startups
    $total_startups = count($startupC->liststartup());
    $total_pages = ceil($total_startups / $limit);

    // Fetch startups for the current page
    $startups = $startupC->liststartupWithLimit($start, $limit);

    foreach ($startups as $startup) {
        echo "<div class='startup-card'>";
        echo "<img src='" . htmlspecialchars($startup['img_startup']) . "' alt='" . htmlspecialchars($startup['nom_startup']) . "' />";
        echo "<h3>" . htmlspecialchars($startup['nom_startup']) . "</h3>";
        echo "<h5><strong>Hoster Name:</strong> " . htmlspecialchars($startup['nom_hoster']) . "</h5>";
        echo "<h5><strong>Hoster Surname:</strong> " . htmlspecialchars($startup['prenom_hoster']) . "</h5>";
        echo "<h5><strong>Purpose:</strong> " . htmlspecialchars($startup['but_startup']) . "</h5>";
        echo "<h5><strong>Description:</strong> " . htmlspecialchars($startup['desc_startup']) . "</h5>";
        echo "<h5><strong>Launch Date:</strong> " . htmlspecialchars($startup['date_startup']) . "</h5>";

        $nitro = $nitroC->listnitrobyid($startup['nitro']);
        if ($nitro && isset($nitro['nitro_name'])) {
            echo "<h5><strong>Nitro:</strong> " . htmlspecialchars($nitro['nitro_name']) . "</h5>";
        } else {
            echo "<h5><strong>Nitro:</strong> No Nitro</h5>";
        }

        //echo "<button class='invest-btn '><i class='fas fa-money-bill-wave'></i> Invest</button>";
        echo "<a href='incubator.php?id=" . $startup['startup_id_id'] . "' 
            style='background-color: blue; color: white; border: none; padding: 10px 15px; cursor: pointer; text-decoration: none; display: inline-block;'>
            <i class='fas fa-bolt'></i> Boost
        </a>";
        echo "</div>";
    }
    ?>
</div>

<!-- Pagination Links -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="btn">Previous</a>
    <?php endif; ?>

    <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?>" class="btn">Next</a>
    <?php endif; ?>
</div>

<style>
    <style>
    .pagination {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }
    .pagination .btn {
        padding: 5px 15px;
        background-color: #007bff;
        color: white;
        border-radius: 5px;
        text-decoration: none;
    }
    .pagination .btn:hover {
        background-color: #0056b3;
    }
</style>

</style>
<!-- Startup Button -->
<div class="start-your-startup">
                <button id="startStartupBtn">Start Your Startup</button>
            </div>
        </div>
            
    </section>

    <!-- Startup-->
    <center>
    <div class="startup-form-container" id="startupFormContainer" style="display:none; max-height: 80vh; overflow-y: auto; padding: 20px; margin-bottom: 20px; border: 1px solid #ccc; background-color: #f9f9f9;">
        <div class="form-container" style="max-width: 500px; width: 100%;">
            <h2 id="formTitle">Start Your Startup</h2>
            <form id="startupForm" method="POST" action="startup.php" enctype="multipart/form-data">
                <img src="C:/xampp/htdocs/Projetweb/View/view/frontOffice/image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png" alt="">
                <input type="hidden" id="startup_id_id" name="startup_id_id" />
                
                <label for="nom_startup">Startup Name</label>
                <input type="text" id="nom_startup" name="nom_startup" />

                <label for="nom_hoster">Hoster Name</label>
                <input type="text" id="nom_hoster" name="nom_hoster" />

                <label for="prenom_hoster">Hoster Surname</label>
                <input type="text" id="prenom_hoster" name="prenom_hoster" />

                <label for="but_startup">Purpose</label>
                <input type="text" id="but_startup" name="but_startup" />

                <label for="desc_startup">Description</label>
                <textarea id="desc_startup" name="desc_startup" required></textarea>

                <label for="date_startup">Launch Date</label>
                <input type="date" id="date_startup" name="date_startup" />

                <label for="img_startup">Image</label>
                <input type="file" id="img_startup" name="img_startup" />

                <button type="submit" id="formSubmitButton">Submit</button>
            </form>
        </div>
    </div>
</center>


    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include_once '../../controller/startupC.php';
        include_once '../../model/startup.php';

        $startupC = new startupC();

       
        if (isset($_POST['delete_id'])) {
            $delete_id = $_POST['delete_id'];
            $startupC->deletestartup($delete_id);
            header("Location: startup.php");
            exit();
        }

       
        if (isset($_POST['nom_startup'])) {
            $startup_id_id = $_POST['startup_id_id'] ?? null;
            $nom_startup = $_POST['nom_startup'];
            $nom_hoster = $_POST['nom_hoster'];
            $prenom_hoster = $_POST['prenom_hoster'];
            $but_startup = $_POST['but_startup'];
            $desc_startup = $_POST['desc_startup'];
            $date_startup = $_POST['date_startup'];
            $img_startup = null;

            
            $uploads_dir = 'uploads/';
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0777, true); 
            }

            
            if (isset($_FILES['img_startup']) && $_FILES['img_startup']['error'] == 0) {
                $img_startup = $uploads_dir . basename($_FILES['img_startup']['name']);
                if (!move_uploaded_file($_FILES['img_startup']['tmp_name'], $img_startup)) {
                    die('Error: Unable to move the uploaded file.');
                }
            }

           
            $startup = new startup_id($startup_id_id, $nom_startup, $nom_hoster, $prenom_hoster, $but_startup, $desc_startup, $date_startup, $img_startup,$nitro=null);

            if ($startup_id_id) {
                
                $startupC->updatestartup($startup, $startup_id_id);
            } else {
                
                $startupC->addstartup($startup);
            }

            
            header("Location: startup.php");
            exit();
        }
    }
    // In your PHP code where you fetch startups, add sorting capability
$sort = $_GET['sort'] ?? 'name-asc'; // Default sort
$startups = $startupC->liststartup();

// Apply sorting
usort($startups, function($a, $b) use ($sort) {
    switch($sort) {
        case 'name-asc':
            return strcmp($a['nom_startup'], $b['nom_startup']);
        case 'name-desc':
            return strcmp($b['nom_startup'], $a['nom_startup']);
        case 'date-asc':
            return strtotime($a['date_startup']) - strtotime($b['date_startup']);
        case 'date-desc':
            return strtotime($b['date_startup']) - strtotime($a['date_startup']);
        default:
            return 0;
    }
});
    ?>

    <script src="incubator.js"></script>
    <script>
        
        document.getElementById("startStartupBtn").addEventListener("click", showVerificationModal);
        
        function editStartup(startup) {
            const formContainer = document.getElementById("startupFormContainer");
            document.getElementById("formTitle").innerText = "Edit Startup";
            document.getElementById("formSubmitButton").innerText = "Update";
            document.getElementById("startup_id_id").value = startup.startup_id_id;
            document.getElementById("nom_startup").value = startup.nom_startup;
            document.getElementById("nom_hoster").value = startup.nom_hoster;
            document.getElementById("prenom_hoster").value = startup.prenom_hoster;
            document.getElementById("but_startup").value = startup.but_startup;
            document.getElementById("desc_startup").value = startup.desc_startup;
            document.getElementById("date_startup").value = startup.date_startup;
            formContainer.style.display = "block";
        }
        document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("startup-form"); 
  if (!form) return;

  form.addEventListener("startStartupBtn", function (e) {
    let isValid = true;
    const requiredFields = form.querySelectorAll("[required]");

    requiredFields.forEach((field) => {
      field.classList.remove("error"); 
      if (field.value.trim() === "") {
        isValid = false;
        field.classList.add("error");
      } else if (field.type === "email" && !field.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        isValid = false;
        field.classList.add("error");
      } else if (field.type === "number" && isNaN(field.value)) {
        isValid = false;
        field.classList.add("error");
      }
    });

    if (!isValid) {
      e.preventDefault(); 
      alert("Veuillez remplir tous les champs correctement.");
    }
  });
});

    </script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("startupForm"); 
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let isValid = true;
        let messages = [];

        const startupName = document.getElementById("nom_startup").value.trim();
        const hosterName = document.getElementById("nom_hoster").value.trim();
        const hosterSurname = document.getElementById("prenom_hoster").value.trim();
        const purpose = document.getElementById("but_startup").value.trim();
        const description = document.getElementById("desc_startup").value.trim();

        // Check if Startup Name starts with uppercase
        if (!/^[A-Z]/.test(startupName)) {
            isValid = false;
            messages.push("Startup name must start with an uppercase letter.");
        }

        // Check if Hoster Name has no numbers
        if (/\d/.test(hosterName)) {
            isValid = false;
            messages.push("Hoster name must not contain numbers.");
        }

        // Check if Hoster Surname has no numbers
        if (/\d/.test(hosterSurname)) {
            isValid = false;
            messages.push("Hoster surname must not contain numbers.");
        }

        // Check if Purpose contains no letters (only numbers or symbols)
        if (/[a-zA-Z]/.test(purpose)) {
            isValid = false;
            messages.push("Purpose must not contain letters.");
        }

        // Check if Description starts with uppercase
        if (!/^[A-Z]/.test(description)) {
            isValid = false;
            messages.push("Description must start with an uppercase letter.");
        }

        if (!isValid) {
            e.preventDefault(); // Stop form from submitting
            alert(messages.join("\n")); // Show all errors
        }
    });
});
</script>
<script>
  // Add these functions to your script section
function sortStartups() {
    const sortValue = document.getElementById('sortSelect').value;
    const container = document.querySelector('.startup-cards');
    const cards = Array.from(container.querySelectorAll('.startup-card'));
    
    cards.sort((a, b) => {
        const nameA = a.querySelector('h3').textContent.toLowerCase();
        const nameB = b.querySelector('h3').textContent.toLowerCase();
        const dateA = new Date(a.querySelector('h5:nth-last-child(3)').textContent.replace('Launch Date: ', ''));
        const dateB = new Date(b.querySelector('h5:nth-last-child(3)').textContent.replace('Launch Date: ', ''));
        
        switch(sortValue) {
            case 'name-asc':
                return nameA.localeCompare(nameB);
            case 'name-desc':
                return nameB.localeCompare(nameA);
            case 'date-asc':
                return dateA - dateB;
            case 'date-desc':
                return dateB - dateA;
            default:
                return 0;
        }
    });
    
    // Re-append sorted cards
    cards.forEach(card => container.appendChild(card));
}

function searchStartups() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const cards = document.querySelectorAll('.startup-card');
    
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(filter) ? 'block' : 'none';
    });
}
</script>

<!-- Advanced CAPTCHA Verification Modal -->
<div id="humanVerifyModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="captcha-header">
            <div class="captcha-icon">
                <i class="fas fa-robot"></i>
                <i class="fas fa-arrow-right"></i>
                <i class="fas fa-user"></i>
            </div>
            <h3>Human Verification</h3>
            <p>Complete this verification to prove you're not a bot</p>
        </div>
        
        <div class="verification-methods">
            <!-- Math CAPTCHA -->
            <div class="verification-method active" id="mathMethod">
                <div class="method-header">
                    <i class="fas fa-calculator"></i>
                    <h4>Math Challenge</h4>
                </div>
                <div class="verification-box">
                    <div class="equation-display">
                        <span id="num1">3</span> 
                        <span id="operator">+</span> 
                        <span id="num2">5</span> 
                        <span>= ?</span>
                    </div>
                    <div class="captcha-input">
                        <input type="text" id="verificationAnswer" placeholder="Enter answer" autocomplete="off">
                        <button id="verifyBtn" class="captcha-button">
                            <i class="fas fa-check"></i> Verify
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Image CAPTCHA -->
            <div class="verification-method" id="imageMethod">
                <div class="method-header">
                    <i class="fas fa-images"></i>
                    <h4>Image Recognition</h4>
                </div>
                <div class="verification-box">
                    <p>Select all images containing <span class="image-prompt">cars</span>:</p>
                    <div class="image-grid" id="imageGrid">
                        <!-- Images will be loaded dynamically -->
                    </div>
                    <div class="captcha-controls">
                        <button class="captcha-button refresh-btn">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button class="captcha-button verify-btn" disabled>
                            <i class="fas fa-check"></i> Verify
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Puzzle CAPTCHA -->
            <div class="verification-method" id="puzzleMethod">
                <div class="method-header">
                    <i class="fas fa-puzzle-piece"></i>
                    <h4>Puzzle Challenge</h4>
                </div>
                <div class="verification-box">
                    <p>Drag the puzzle piece to complete the image:</p>
                    <div class="puzzle-container">
                        <div class="puzzle-base"></div>
                        <div class="puzzle-piece" draggable="true"></div>
                    </div>
                    <div class="captcha-controls">
                        <button class="captcha-button verify-btn" disabled>
                            <i class="fas fa-check"></i> Verify
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="verification-footer">
            <div class="error-message" id="verificationError" style="display:none;">
                <i class="fas fa-exclamation-circle"></i>
                <span>Verification failed. Please try again.</span>
            </div>
            <div class="attempts-counter">
                Attempts remaining: <span id="attemptsLeft">3</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal styles */
    .equation-display {
        font-size: 28px;
        text-align: center;
        margin: 20px 0;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        position: relative;
        overflow: hidden;
    }

    .equation-display::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, rgba(255,255,255,0.😎 0%, rgba(255,255,255,0) 50%, rgba(255,255,255,0.😎 100%);
        animation: shimmer 2s infinite;
        z-index: 1;
    }

    .equation-display span {
        position: relative;
        z-index: 2;
        filter: blur(2px);
        transition: filter 0.3s;
    }

    .equation-display:hover span {
        filter: blur(0);
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.85);
        backdrop-filter: blur(5px);
    }
    
    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 25px;
        border-radius: 12px;
        width: 450px;
        max-width: 95%;
        position: relative;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
        animation: modalFadeIn 0.3s ease-out;
    }
    
    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .close-modal {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 24px;
        color: #888;
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .close-modal:hover {
        color: #333;
    }
    
    .captcha-header {
        text-align: center;
        margin-bottom: 25px;
    }
    
    .captcha-icon {
        font-size: 32px;
        margin-bottom: 15px;
        color: #666;
    }
    
    .captcha-icon i {
        margin: 0 10px;
    }
    
    .captcha-header h3 {
        margin: 0;
        color: #333;
        font-size: 22px;
    }
    
    .captcha-header p {
        margin: 5px 0 0;
        color: #666;
        font-size: 14px;
    }
    
    .verification-methods {
        margin-top: 20px;
    }
    
    .verification-method {
        display: none;
    }
    
    .verification-method.active {
        display: block;
    }
    
    .method-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .method-header i {
        margin-right: 10px;
        color: #4a6baf;
        font-size: 20px;
    }
    
    .method-header h4 {
        margin: 0;
        font-size: 16px;
        color: #444;
    }
    
    .verification-box {
        margin: 20px 0;
    }
    
    .equation-display {
        font-size: 28px;
        text-align: center;
        margin: 20px 0;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }
    
    .captcha-input {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    
    .captcha-input input {
        flex: 1;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: border 0.2s;
    }
    
    .captcha-input input:focus {
        border-color: #4a6baf;
        outline: none;
    }
    
    .captcha-button {
        padding: 12px 20px;
        background-color: #4a6baf;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .captcha-button:hover {
        background-color: #3a5a9f;
    }
    
    .captcha-button:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }
    
    .image-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin: 15px 0;
    }
    
    .image-grid img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
    }
    
    .image-grid img:hover {
        opacity: 0.9;
    }
    
    .image-grid img.selected {
        border-color: #4a6baf;
        box-shadow: 0 0 0 2px rgba(74, 107, 175, 0.3);
    }
    
    .captcha-controls {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }
    
    .refresh-btn {
        background-color: #f1f1f1;
        color: #333;
    }
    
    .refresh-btn:hover {
        background-color: #e0e0e0;
    }
    
    .puzzle-container {
        position: relative;
        height: 150px;
        background: #f8f9fa;
        border-radius: 8px;
        margin: 15px 0;
        overflow: hidden;
        border: 1px solid #e0e0e0;
    }
    
    .puzzle-base {
        width: 100%;
        height: 100%;
        background-size: cover;
        opacity: 0.7;
    }
    
    .puzzle-piece {
        position: absolute;
        width: 60px;
        height: 60px;
        background-size: cover;
        cursor: move;
        z-index: 10;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    
    .verification-footer {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
    
    .error-message {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #d32f2f;
        font-size: 14px;
        margin-bottom: 15px;
    }
    
    .attempts-counter {
        text-align: right;
        font-size: 13px;
        color: #666;
    }
</style>

<script>
  // CAPTCHA Implementation
document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const modal = document.getElementById('humanVerifyModal');
    const closeBtn = document.querySelector('.close-modal');
    const verifyBtn = document.getElementById('verifyBtn');
    const verificationAnswer = document.getElementById('verificationAnswer');
    const errorElement = document.getElementById('verificationError');
    const attemptsElement = document.getElementById('attemptsLeft');
    
    // CAPTCHA variables
    let currentEquation = generateMathEquation();
    let attemptsLeft = 3;
    let selectedImages = [];
    let puzzlePosition = { x: 0, y: 0 };
    let correctPuzzlePosition = { x: 150, y: 80 }; // Example correct position
    
    // Initialize CAPTCHA methods
    initMathCaptcha();
    initImageCaptcha();
    initPuzzleCaptcha();
    
    // Show modal when Start Your Startup button is clicked
    document.getElementById("startStartupBtn").addEventListener("click", function() {
        showVerificationModal();
    });
    
    // Close modal
    closeBtn.addEventListener('click', closeModal);
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
    
    function showVerificationModal() {
        // Reset state
        attemptsLeft = 3;
        updateAttemptsDisplay();
        errorElement.style.display = 'none';
        
        // Show math CAPTCHA by default
        switchCaptchaMethod('mathMethod');
        
        // Generate new challenges
        currentEquation = generateMathEquation();
        loadImageCaptcha();
        setupPuzzleChallenge();
        
        // Show modal
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    function updateAttemptsDisplay() {
        attemptsElement.textContent = attemptsLeft;
    }
    
    function showError(message) {
        errorElement.querySelector('span').textContent = message;
        errorElement.style.display = 'flex';
    }
    
    function hideError() {
        errorElement.style.display = 'none';
    }
    
    // Math CAPTCHA functions
    function initMathCaptcha() {
        verifyBtn.addEventListener('click', verifyMathCaptcha);
        verificationAnswer.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                verifyMathCaptcha();
            }
        });
    }
    
    function generateMathEquation() {
        const operators = ['+', '-', '*'];
        const num1 = Math.floor(Math.random() * 10) + 1;
        const num2 = Math.floor(Math.random() * 10) + 1;
        const operator = operators[Math.floor(Math.random() * operators.length)];
        
        let answer;
        switch(operator) {
            case '+': answer = num1 + num2; break;
            case '-': answer = num1 - num2; break;
            case '*': answer = num1 * num2; break;
        }
        
        return { num1, num2, operator, answer };
    }
    
    function verifyMathCaptcha() {
        const userAnswer = parseInt(verificationAnswer.value);
        
        if (isNaN(userAnswer)) {
            showError("Please enter a valid number");
            return;
        }
        
        if (userAnswer === currentEquation.answer) {
            // Verification passed
            hideError();
            closeModal();
            document.getElementById('startupFormContainer').style.display = 'block';
        } else {
            attemptsLeft--;
            updateAttemptsDisplay();
            
            if (attemptsLeft <= 0) {
                showError("Too many failed attempts. Please try again later.");
                setTimeout(closeModal, 2000);
            } else {
                showError("Incorrect answer. Please try again.");
                currentEquation = generateMathEquation();
                updateMathDisplay();
                verificationAnswer.value = '';
                verificationAnswer.focus();
            }
        }
    }
    
    function updateMathDisplay() {
        document.getElementById('num1').textContent = currentEquation.num1;
        document.getElementById('num2').textContent = currentEquation.num2;
        document.getElementById('operator').textContent = currentEquation.operator;
    }
    
    // Image CAPTCHA functions
    function initImageCaptcha() {
        // This would be replaced with actual image loading in production
        const refreshBtn = document.querySelector('.refresh-btn');
        const imageVerifyBtn = document.querySelector('#imageMethod .verify-btn');
        
        refreshBtn.addEventListener('click', loadImageCaptcha);
        imageVerifyBtn.addEventListener('click', verifyImageCaptcha);
    }
    
    function loadImageCaptcha() {
        const imageGrid = document.getElementById('imageGrid');
        imageGrid.innerHTML = '';
        selectedImages = [];
        
        // In a real implementation, you would fetch these from a server
        const imageCategories = ['cars', 'traffic lights', 'buses', 'store fronts'];
        const currentCategory = imageCategories[Math.floor(Math.random() * imageCategories.length)];
        
        document.querySelector('.image-prompt').textContent = currentCategory;
        
        // Generate placeholder images (in real app, these would be actual images)
        for (let i = 0; i < 9; i++) {
            const img = document.createElement('div');
            img.className = 'captcha-image';
            img.innerHTML = `<img src="https://via.placeholder.com/100?text=Image+${i+1}" 
                              alt="Captcha image ${i+1}" 
                              data-correct="${i % 3 === 0}">`; // Every 3rd image is "correct"
            
            img.querySelector('img').addEventListener('click', function() {
                this.classList.toggle('selected');
                const index = selectedImages.indexOf(this);
                if (index === -1) {
                    selectedImages.push(this);
                } else {
                    selectedImages.splice(index, 1);
                }
                
                const verifyBtn = document.querySelector('#imageMethod .verify-btn');
                verifyBtn.disabled = selectedImages.length === 0;
            });
            
            imageGrid.appendChild(img);
        }
    }
    
    function verifyImageCaptcha() {
        // Check if selected images are correct
        const correctSelections = selectedImages.filter(img => img.dataset.correct === 'true').length;
        const incorrectSelections = selectedImages.filter(img => img.dataset.correct === 'false').length;
        
        if (incorrectSelections > 0 || correctSelections < 2) {
            attemptsLeft--;
            updateAttemptsDisplay();
            
            if (attemptsLeft <= 0) {
                showError("Too many failed attempts. Please try again later.");
                setTimeout(closeModal, 2000);
            } else {
                showError("Incorrect selection. Please try again.");
                loadImageCaptcha();
            }
        } else {
            // Verification passed
            hideError();
            closeModal();
            document.getElementById('startupFormContainer').style.display = 'block';
        }
    }
    
    // Puzzle CAPTCHA functions
    function initPuzzleCaptcha() {
        const puzzlePiece = document.querySelector('.puzzle-piece');
        const puzzleVerifyBtn = document.querySelector('#puzzleMethod .verify-btn');
        
        // Set up drag and drop
        puzzlePiece.addEventListener('mousedown', startDrag);
        puzzleVerifyBtn.addEventListener('click', verifyPuzzleCaptcha);
        
        // Set initial random position
        puzzlePiece.style.left = '50px';
        puzzlePiece.style.top = '20px';
        puzzlePosition = { x: 50, y: 20 };
    }
    
    function setupPuzzleChallenge() {
        // In a real implementation, this would set up an actual puzzle image
        const puzzlePiece = document.querySelector('.puzzle-piece');
        puzzlePiece.style.left = ${Math.random() * 200}px;
        puzzlePiece.style.top = ${Math.random() * 100}px;
    }
    
    function startDrag(e) {
        e.preventDefault();
        const puzzlePiece = e.target;
        
        function movePiece(e) {
            const containerRect = puzzlePiece.parentElement.getBoundingClientRect();
            let x = e.clientX - containerRect.left - (puzzlePiece.offsetWidth / 2);
            let y = e.clientY - containerRect.top - (puzzlePiece.offsetHeight / 2);
            
            // Constrain to container
            x = Math.max(0, Math.min(x, containerRect.width - puzzlePiece.offsetWidth));
            y = Math.max(0, Math.min(y, containerRect.height - puzzlePiece.offsetHeight));
            
            puzzlePiece.style.left = ${x}px;
            puzzlePiece.style.top = ${y}px;
            puzzlePosition = { x, y };
            
            // Enable verify button when close to correct position
            const verifyBtn = document.querySelector('#puzzleMethod .verify-btn');
            const distance = Math.sqrt(
                Math.pow(x - correctPuzzlePosition.x, 2) + 
                Math.pow(y - correctPuzzlePosition.y, 2)
            );
            verifyBtn.disabled = distance > 30;
        }
        
        function stopDrag() {
            document.removeEventListener('mousemove', movePiece);
            document.removeEventListener('mouseup', stopDrag);
        }
        
        document.addEventListener('mousemove', movePiece);
        document.addEventListener('mouseup', stopDrag);
    }
    
    function verifyPuzzleCaptcha() {
        const distance = Math.sqrt(
            Math.pow(puzzlePosition.x - correctPuzzlePosition.x, 2) + 
            Math.pow(puzzlePosition.y - correctPuzzlePosition.y, 2)
        );
        
        if (distance > 30) {
            attemptsLeft--;
            updateAttemptsDisplay();
            
            if (attemptsLeft <= 0) {
                showError("Too many failed attempts. Please try again later.");
                setTimeout(closeModal, 2000);
            } else {
                showError("Puzzle not completed correctly. Try again.");
            }
        } else {
            // Verification passed
            hideError();
            closeModal();
            document.getElementById('startupFormContainer').style.display = 'block';
        }
    }
    
    // Method switching
    function switchCaptchaMethod(methodId) {
        document.querySelectorAll('.verification-method').forEach(method => {
            method.classList.remove('active');
        });
        document.getElementById(methodId).classList.add('active');
    }
    
    // For demo purposes, add method switcher buttons
    function addMethodSwitchers() {
        const methods = ['mathMethod', 'imageMethod', 'puzzleMethod'];
        const switcher = document.createElement('div');
        switcher.className = 'method-switcher';
        switcher.style.display = 'flex';
        switcher.style.justifyContent = 'center';
        switcher.style.gap = '10px';
        switcher.style.marginBottom = '15px';
        
        methods.forEach(method => {
            const btn = document.createElement('button');
            btn.textContent = method.replace('Method', '');
            btn.className = 'method-btn';
            btn.style.padding = '5px 10px';
            btn.style.border = '1px solid #ddd';
            btn.style.borderRadius = '4px';
            btn.style.background = 'none';
            btn.style.cursor = 'pointer';
            
            btn.addEventListener('click', () => switchCaptchaMethod(method));
            switcher.appendChild(btn);
        });
        
        document.querySelector('.verification-methods').prepend(switcher);
    }
    
    // Uncomment to enable method switching in demo
    // addMethodSwitchers();
});
</script>




</body>
</html>



<?php
ob_end_flush();
?>