<?php
// Charger la classe User AVANT la session
require_once __DIR__.'/../../Models/Users.php';

session_start();

// Empêcher la mise en cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Vérification de la session
if (!isset($_SESSION['user'])) {
    header('Location: login_register.php');
    exit;
}

// Désérialiser l'objet User
$user = unserialize($_SESSION['user']);

// Vérifier que la désérialisation a fonctionné
if (!($user instanceof User)) {
    session_destroy();
    header('Location: login_register.php');
    exit;
}
?>

<!-- Le reste du HTML -->

  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Modern Smooth Navbar</title>
      <link rel="stylesheet" href="../../assets/css/styles_dash.css" />
      <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      />
      <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        rel="stylesheet"
      />
      <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
      />
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
              <button class="dropbtn">Explore Opportunities ▼</button>
              <ul class="dropdown-menu">
                <li>
                    <a href="#">
                        💡 Innovative Projects
                        <span class="subtitle">Explore new cutting-edge ideas</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        👥 Collaborative Ventures
                        <span class="subtitle">Work together with great minds</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        💰 Funding Opportunities
                        <span class="subtitle">Find the best investment options</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        🤝 Partnerships
                        <span class="subtitle">Build strong business relationships</span>
                    </a>
                </li>
            </ul>
          </li>
          <li class="dropdown">
              <button class="dropbtn">Our Courses ▼</button>
              <ul class="dropdown-menu">
                  <li><a href="#">📚 Course 1</a></li>
                  <li><a href="#">📖 Course 2</a></li>
                  <li><a href="#">🎓 Course 3</a></li>
              </ul>
          </li>
          <li class="dropdown">
              <button class="dropbtn">Why Us ▼</button>
              <ul class="dropdown-menu">
                  <li><a href="#">⭐ About Us</a></li>
                  <li><a href="#">🛠 Services</a></li>
                  <li><a href="#">📢 Testimonials</a></li>
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
        <div class="user-session">
        <?php if ($user) : ?>
  <div class="welcome-message">
  <?= htmlspecialchars($user->getNom()) ?> <?= htmlspecialchars($user->getPrénom()) ?>
<?php endif; ?>
        <form action="../../Controllers/logout.php" method="post" style="display:inline;">
    <button type="submit" class="login-btn">
      <i class="fas fa-user"></i> Log out
    </button>
  </form>
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
      <div class="ai-search-container">
        <h2 data-aos="fade-up">Empowering visionaries to innovate, create, and lead the future of entrepreneurship.</h2>
        <p data-aos="fade-up">
          Our AI-powered search engine helps you find the right opportunities,
          courses, and resources to take your project to the next level.
        </p>
        <!-- AI Search Box -->
        <div class="ai-search-box">
          <input type="text" id="ai-input" placeholder="Ask AI Anything..." />
          <button class="ai-btn">
            <i class="fas fa-paper-plane"></i>
          </button>
        </div>
        <!-- Images dispersées -->
        <img src="image/pexels-mart-production-7550310.jpg" class="floating-image img1" />
        <img src="image/pexels-thirdman-7181111.jpg" class="floating-image img2" />
        <img src="image/pexels-rdne-7414214.jpg" class="floating-image img3" />
        <img src="image/pexels-edmond-dantes-8069014.jpg" class="floating-image4 img4" />
      </div>
      <hr>
      
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
