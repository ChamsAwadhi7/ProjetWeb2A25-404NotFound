<?php

session_start();
if (!isset($_SESSION['utilisateur'])) {
    header('Location: login_register.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NextStep</title>
    <link rel="website icon" type="PNG" href="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png">
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
    <link rel="stylesheet" href="index.css" />
    <style>
     
    </style>
  </head>
  <body>
    <!-- Navbar -->
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
      <a href="#"><i class="fas fa-home"></i> Home</a>
    </li>
    <li>
      <a href="#"><i class="fas fa-question-circle"></i> Why Us</a>
    </li>

    <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role'] === 'admin'): ?>
    <li>
      <a href="Back.php"><i class="fas fa-user-shield"></i> Admin</a>
    </li>
    <?php endif; ?>
  </ul>
</li>


        <li class="dropdown">
          <button class="dropbtn">
            Startup <i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li>
              <a href="STARTUP/FrontOffice/startup.php"><i class="fas fa-lightbulb"></i>Startup</a>
            </li>
          </ul>
        </li>
        <li class="dropdown">
                <button class="dropbtn">
                  Incubator <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="STARTUP/FrontOffice/incubator.php#nitro-section"><i class="fas fa-bolt"></i> Nitro Plans</a></li>
                  <li><a href="STARTUP/FrontOffice/incubator.php#workspace-section"><i class="fas fa-chair"></i> Working Space</a></li>
                  <li><a href="STARTUP/FrontOffice/incubator.php#workshop-section"><i class="fas fa-chalkboard-teacher"></i> Workshops</a></li>
                </ul>
              </li>

        <li class="dropdown">
          <button class="dropbtn">
            Our Courses <i class="fas fa-chevron-down"></i>
            <!-- Changed to 'Our Courses' -->
          </button>
          <ul class="dropdown-menu">
          
            <li>
              <a href="COURS/coursF.php">
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
              <a href="EVENT/FrontOffice/View/eventsF.php"
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
    <section class="home" id="home">
      <video autoplay muted loop class="background-video">
        <source src="image/video.mp4" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
      <div class="overlay"></div>
      <div class="content" id="content">
        <h3>Move Forward, Stay Ahead</h3>
        <p>
          Join us and take your Project to the next level with innovation and
          expertise.
        </p>
        <a href="#" class="btn">Get Ready with Us</a>
      </div>
    </section>
    <div class="counter-wrapper">
      <div class="counter">
          <i class="fas fa-users" style="color: #ff5733;"></i>
          <h1 class="count" data-target="1254">0</h1>
          <h4>New Visitors Every Week</h4>
      </div>
      <div class="counter">
          <i class="fas fa-smile" style="color: #f4c542;"></i>
          <h1 class="count" data-target="12168">0</h1>
          <h4>Happy Customers Every Year</h4>
      </div>
      <div class="counter">
          <i class="fas fa-trophy" style="color: #4caf50;"></i>
          <h1 class="count" data-target="2172">0</h1>
          <h4>Won Amazing Awards</h4>
      </div>
      <div class="counter">
          <i class="fas fa-building" style="color: #2196f3;"></i>
          <h1 class="count" data-target="732">0</h1>
          <h4>New Listings Every Week</h4>
      </div>
    </div>
    <br>
    <br>
    <br>

    <section id="about-us" class="about-section">
      <div class="container">
        <h2 data-aos="fade-up">About Us</h2>
        <p data-aos="fade-up">
          We are dedicated to fostering innovation and entrepreneurship...
        </p>
        <div class="about-content">
          <div class="about-item" data-aos="fade-up">
            <img src="image/pexels-pixabay-355952.jpg" alt="Innovation" />
            <h3>Innovation</h3>
            <p>We help turn groundbreaking ideas into successful businesses.</p>
            <a href="#" class="read-more-btn"
              >Read More <i class="fas fa-arrow-right"></i
            ></a>
          </div>
          <div class="about-item" data-aos="fade-up" data-aos-delay="200">
            <img src="image/pexels-fauxels-3184418.jpg" alt="Teamwork" />
            <h3>Collaboration</h3>
            <p>We believe in the power of partnerships and teamwork.</p>
            <a href="#" class="read-more-btn"
              >Read More <i class="fas fa-arrow-right"></i
            ></a>
          </div>
          <div class="about-item" data-aos="fade-up" data-aos-delay="400">
            <img src="image/pexels-weekendplayer-187041.jpg" alt="Growth" />
            <h3>Growth</h3>
            <p>Our platform helps entrepreneurs scale and succeed.</p>
            <a href="#" class="read-more-btn"
              >Read More <i class="fas fa-arrow-right"></i
            ></a>
          </div>
        </div>
      </div>
    </section>
    <div class="flip-row">
      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <h2>Front 1</h2>
          </div>
          <div class="flip-back">
            <h2>Back 1</h2>
          </div>
        </div>
      </div>
  
      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <h2>Front 2</h2>
          </div>
          <div class="flip-back">
            <h2>Back 2</h2>
          </div>
        </div>
      </div>
  
      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <h2>Front 3</h2>
          </div>
          <div class="flip-back">
            <h2>Back 3</h2>
          </div>
        </div>
      </div>
  
      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <h2>Front 4</h2>
          </div>
          <div class="flip-back">
            <h2>Back 4</h2>
          </div>
        </div>
      </div>
  
      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <h2>Front 5</h2>
          </div>
          <div class="flip-back">
            <h2>Back 5</h2>
          </div>
        </div>
      </div>
  
      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <h2>Front 6</h2>
          </div>
          <div class="flip-back">
            <h2>Back 6</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="ai-search-container" id="ai-search-container">
      <h2 data-aos="fade-up">Empowering visionaries to innovate, create, and lead the future of entrepreneurship.</h2>
      <p data-aos="fade-up">
        Our AI-powered search engine helps you find the right opportunities,
        courses, and resources to take your project to the next level.
      </p>
      <!-- AI Search Box -->
      <div class="ai-chat-container">
        <div class="ai-chat-box" id="chat-box">
          <div class="ai-message">👋 Hello! Ask me anything about our services, startups, or projects.</div>
        </div>
        <div class="ai-chat-input-horizontal">
          <i class="fas fa-robot ai-input-icon"></i>
          <input type="text" id="ai-chat-input" placeholder="Type your question..." />
          <button class="ai-chat-btn" id="send-ai-btn">
            <i class="fas fa-paper-plane"></i>
          </button>
        </div>
      </div>
      <!-- Images dispersées -->
      <img src="image/pexels-mart-production-7550310.jpg" class="floating-image img1" />
      <img src="image/pexels-thirdman-7181111.jpg" class="floating-image img2" />
      <img src="image/pexels-rdne-7414214.jpg" class="floating-image img3" />
      <img src="image/pexels-edmond-dantes-8069014.jpg" class="floating-image4 img4" />
    </div>
    <br><br><br>
    <hr>
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
          </div>
        </div>
      </div>
   </footer> 
    <script src="script.js"></script>
    <!-- AOS Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
      AOS.init({
        duration: 1000,
        once: false,
      });
    </script>
  </body>
</html>