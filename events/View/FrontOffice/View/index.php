<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modern Smooth Navbar</title>
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
  </head>
  
  <body style="background: url('image/bgimg.jpg') no-repeat center center fixed; background-size: 100%;">
    <nav class="navbar" style="display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px 30px;
  background-color: rgba(255, 255, 255, 0.3); /* semi-transparent */
  backdrop-filter: blur(10px);               /* flou */
  -webkit-backdrop-filter: blur(10px);       /* compatibilité Safari */
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);  /* ombre douce */
  position: sticky;
  top: 0;
  width: 100%;
  z-index: 1000;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2); /* légère bordure */
  transition: background-color 0.3s ease;">
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
    <style>
      .flip-row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
  margin-top: 30px;
}

.flip-card-box {
  width: 350px;
  height: 420px;
  perspective: 1000px;
}

.flip-card-wrap {
  width: 100%;
  height: 100%;
  transition: transform 0.8s;
  transform-style: preserve-3d;
  position: relative;
}

.flip-card-box:hover .flip-card-wrap {
  transform: rotateY(180deg);
}

.flip-front, .flip-back {
  position: absolute;
  width: 100%;
  height: 100%;
  padding: 20px;
  box-sizing: border-box;
  border-radius: 15px;
  backface-visibility: hidden;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.flip-front {
  background: #fff;
  border: 1px solid #eaeaea;
}

.flip-front p {
  font-size: 16px;
  line-height: 1.6;
  color: #444;
  margin-top: 8px;
  margin-bottom: 0;
  font-weight: 500;
  letter-spacing: 0.3px;
  text-align: center;
  opacity: 1;
}


.flip-front img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 15px;
  border: 3px solid #007BFF;
}

.flip-front h3 {
  font-size: 20px;
  margin: 10px 0 5px;
  color: #333;
  opacity: 1;
}

.role {
  color: #666 !important;
  font-size: 14px;
  margin: 10px 0 5px;
  font-size: 14px;
  opacity: 1 !important;
}

.flip-back {
  color: white;
  transform: rotateY(180deg);
  text-align: center;
}

.flip-back h3 {
  font-size: 20px;
  margin-bottom: 10px;
}

.flip-back p {
  font-size: 14px;
  line-height: 1.4;
  color: #fff;
  opacity: 1;
  border-radius: 20px;
  background: rgba(0, 0, 0, 0.1); /* Light glass */
  border: 1px solid rgba(255, 255, 255, 0.2); /* Subtle border for glass edge */
  border-radius: 12px;

  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px); /* Safari support */
  
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Softer shadow */
}
.team-header {
  text-align: center;
  padding: 60px 20px 30px;
  background: transparent;
}

.team-header h1 {
  font-size: 42px;
  font-weight: 700;
  color: #222;
  margin-bottom: 15px;
  text-transform: uppercase;
  letter-spacing: 1.2px;
  position: relative;
}

.team-header h1::after {
  content: '';
  width: 60px;
  height: 4px;
  background-color: #3498db;
  display: block;
  margin: 12px auto 0;
  border-radius: 2px;
}

.team-header p {
  font-size: 18px;
  font-weight: 400;
  color:#fff ;
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.6;
  opacity: 1;
}

    </style>
    <section class="team-header">
      <h1>Notre Équipe</h1>
      <p>Meet our dedicated team of professionals who are here to support you every step of the way.</p>
    </section>
    

    <br><br>
    <center><div class="flip-row">
      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <img src="image/bgr.png" alt="Admin 1" style="width: 100px; height: 100px; border-radius: 50%;">
            <h3>Alice Smith</h3>
            <p style="opacity: 1;">Project Manager</p>
          </div>
          <div class="flip-back">
            <h3>Alice Smith</h3>
            <p>Oversees tech development and leads cross-functional teams. Background in Agile and Scrum methodologies.</p>
          </div>
        </div>
      </div>
      
      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <img src="images/admin2.jpg" alt="Admin 2" style="width: 100px; height: 100px; border-radius: 50%;">
            <h3>Mohamed Zied</h3>
            <p>Lead Developer</p>
          </div>
          <div class="flip-back">
            <h3>Mohamed Zied</h3>
            <p>Expert in PHP, JavaScript, and scalable web architecture. Loves mentoring junior devs.</p>
          </div>
        </div>
      </div>

      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <img src="images/admin2.jpg" alt="Admin 2" style="width: 100px; height: 100px; border-radius: 50%;">
            <h3>Mohamed Zied</h3>
            <p>Lead Developer</p>
          </div>
          <div class="flip-back">
            <h3>Mohamed Zied</h3>
            <p>Expert in PHP, JavaScript, and scalable web architecture. Loves mentoring junior devs.</p>
          </div>
        </div>
      </div>

      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <img src="images/admin2.jpg" alt="Admin 2" style="width: 100px; height: 100px; border-radius: 50%;">
            <h3>Mohamed Zied</h3>
            <p>Lead Developer</p>
          </div>
          <div class="flip-back">
            <h3>Mohamed Zied</h3>
            <p>Expert in PHP, JavaScript, and scalable web architecture. Loves mentoring junior devs.</p>
          </div>
        </div>
      </div>

      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <img src="images/admin2.jpg" alt="Admin 2" style="width: 100px; height: 100px; border-radius: 50%;">
            <h3>Mohamed Zied</h3>
            <p>Lead Developer</p>
          </div>
          <div class="flip-back">
            <h3>Mohamed Zied</h3>
            <p>Expert in PHP, JavaScript, and scalable web architecture. Loves mentoring junior devs.</p>
          </div>
        </div>
      </div>

      <div class="flip-card-box">
        <div class="flip-card-wrap">
          <div class="flip-front">
            <img src="images/admin2.jpg" alt="Admin 2" style="width: 100px; height: 100px; border-radius: 50%;">
            <h3>Mohamed Zied</h3>
            <p>Lead Developer</p>
          </div>
          <div class="flip-back">
            <h3>Mohamed Zied</h3>
            <p style="color: #eaeaea !important;">Expert in PHP, JavaScript, and scalable web architecture. Loves mentoring junior devs.</p>
          </div>
        </div>
      </div>
    </div></center>
    <br><br>
    <div class="ai-search-container" id="ai-search-container">
      <h2 data-aos="fade-up">Empowering visionaries to innovate, create, and lead the future of entrepreneurship.</h2>
      <p data-aos="fade-up" style="color: #eaeaea;">
        Our AI-powered search engine helps you find the right opportunities,
        courses, and resources to take your project to the next level.
      </p>
      <!-- AI Search Box -->
      <div class="ai-chat-container">
        <div class="ai-chat-box" id="chat-box">
          <div class="ai-message">👋 Hello! Ask me anything about our services, startups, or projects.</div>
        </div>
        <br><br>
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
