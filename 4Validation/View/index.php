<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modern Smooth Navbar</title>
    <!-- Single Font Awesome import -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('image/bgimg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            line-height: 1.6;
        }

        /* Navbar Styles */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 30px;
            background-color: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            transition: background-color 0.3s ease;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .logo span {
            color: #4a6bff;
        }

        .logo-img {
            height: 40px;
            margin-right: 10px;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin: 0 10px;
            position: relative;
        }

        .dropbtn {
            background: none;
            border: none;
            color: #333;
            font-size: 16px;
            cursor: pointer;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .dropbtn:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            z-index: 1;
            list-style: none;
            padding: 10px 0;
        }

        .dropdown-menu li a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .dropdown-menu li a:hover {
            background-color: #f5f5f5;
            color: #4a6bff;
        }

        .dropdown-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .search-box {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 30px;
            padding: 5px 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .search-box input {
            border: none;
            outline: none;
            padding: 8px;
            width: 200px;
        }

        .search-category {
            border: none;
            outline: none;
            background: none;
            cursor: pointer;
            margin-left: 5px;
            padding-left: 5px;
            border-left: 1px solid #eee;
        }

        .login-btn {
            background-color: #4a6bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background-color: #3a5bef;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .home {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            overflow: hidden;
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
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .content h3 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            max-width: 600px;
        }

        .btn {
            display: inline-block;
            background-color: #4a6bff;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #3a5bef;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Counter Styles */
        .counter-wrapper {
            display: flex;
            justify-content: space-around;
            padding: 50px 20px;
            background-color: white;
            flex-wrap: wrap;
        }

        .counter {
            text-align: center;
            padding: 20px;
            margin: 10px;
        }

        .counter i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .count {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .counter h4 {
            font-size: 1.2rem;
            color: #666;
        }

        /* About Section */
        .about-section {
            padding: 80px 20px;
            background-color: #f9f9f9;
            text-align: center;
        }

        .about-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
        }

        .about-section p {
            font-size: 1.1rem;
            color: #666;
            max-width: 800px;
            margin: 0 auto 40px;
        }

        .about-content {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }

        .about-item {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            max-width: 350px;
        }

        .about-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .about-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .about-item h3 {
            font-size: 1.5rem;
            margin: 20px 0 10px;
            color: #333;
        }

        .about-item p {
            font-size: 1rem;
            color: #666;
            padding: 0 20px;
            margin-bottom: 20px;
        }

        .read-more-btn {
            display: inline-block;
            color: #4a6bff;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .read-more-btn:hover {
            color: #3a5bef;
        }

        /* Team Section */
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
            color: #fff;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

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
        }

        .role {
            color: #666;
            font-size: 14px;
            margin: 10px 0 5px;
        }

        .flip-back {
            color: white;
            transform: rotateY(180deg);
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .flip-back h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .flip-back p {
            font-size: 14px;
            line-height: 1.4;
            color: #fff;
            padding: 15px;
            background: rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* AI Search Section */
        .ai-search-container {
            position: relative;
            padding: 80px 20px;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            margin: 50px 0;
        }

        .ai-search-container h2 {
            font-size: 2.2rem;
            margin-bottom: 20px;
        }

        .ai-search-container p {
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto 40px;
        }

        .ai-chat-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .ai-chat-box {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            min-height: 150px;
            text-align: left;
            margin-bottom: 20px;
            backdrop-filter: blur(5px);
        }

        .ai-message {
            font-size: 1rem;
        }

        .ai-chat-input-horizontal {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 10px 20px;
        }

        .ai-input-icon {
            font-size: 1.5rem;
            margin-right: 15px;
            color: #4a6bff;
        }

        #ai-chat-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: white;
            font-size: 1rem;
            padding: 10px 0;
        }

        #ai-chat-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .ai-chat-btn {
            background: #4a6bff;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .ai-chat-btn:hover {
            background: #3a5bef;
            transform: scale(1.1);
        }

        .floating-image {
            position: absolute;
            width: 150px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.5s ease;
            z-index: -1;
        }

        .floating-image:hover {
            transform: scale(1.05);
        }

        .img1 {
            top: 10%;
            left: 5%;
        }

        .img2 {
            top: 30%;
            right: 5%;
        }

        .img3 {
            bottom: 15%;
            left: 10%;
        }

        .img4 {
            bottom: 20%;
            right: 10%;
        }

        /* Footer Styles */
        .footer {
            background-color: #24262b;
            padding: 70px 0;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .footer-col {
            width: 25%;
            padding: 0 15px;
        }

        .footer-col h4 {
            font-size: 18px;
            margin-bottom: 30px;
            position: relative;
        }

        .footer-col h4::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            background-color: #4a6bff;
            height: 2px;
            box-sizing: border-box;
            width: 50px;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            color: #ddd;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-col ul li a:hover {
            color: #4a6bff;
            padding-left: 5px;
        }

        .social-links a {
            display: inline-block;
            height: 40px;
            width: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            margin-right: 10px;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            color: white;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: #24262b;
            background-color: white;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .footer-col {
                width: 50%;
                margin-bottom: 30px;
            }
            
            .floating-image {
                width: 120px;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 15px;
            }
            
            .nav-links {
                flex-direction: column;
                width: 100%;
                margin-top: 15px;
            }
            
            .search-box {
                margin: 15px 0;
                width: 100%;
            }
            
            .footer-col {
                width: 100%;
            }
            
            .floating-image {
                display: none;
            }
            
            .content h3 {
                font-size: 2rem;
            }
        }
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

    <!-- Hero Section -->
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

    <!-- Counter Section -->
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

    <!-- About Section -->
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
                    <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="about-item" data-aos="fade-up" data-aos-delay="200">
                    <img src="image/pexels-fauxels-3184418.jpg" alt="Teamwork" />
                    <h3>Collaboration</h3>
                    <p>We believe in the power of partnerships and teamwork.</p>
                    <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="about-item" data-aos="fade-up" data-aos-delay="400">
                    <img src="image/pexels-weekendplayer-187041.jpg" alt="Growth" />
                    <h3>Growth</h3>
                    <p>Our platform helps entrepreneurs scale and succeed.</p>
                    <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-header">
        <h1>Notre Équipe</h1>
        <p>Meet our dedicated team of professionals who are here to support you every step of the way.</p>
    </section>

    <div class="flip-row">
        <div class="flip-card-box">
            <div class="flip-card-wrap">
                <div class="flip-front">
                    <img src="image/bgr.png" alt="Admin 1" />
                    <h3>Alice Smith</h3>
                    <p>Project Manager</p>
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
                    <img src="image/admin2.jpg" alt="Admin 2" />
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
                    <img src="image/admin2.jpg" alt="Admin 2" />
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
                    <img src="image/admin2.jpg" alt="Admin 2" />
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
                    <img src="image/admin2.jpg" alt="Admin 2" />
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
                    <img src="image/admin2.jpg" alt="Admin 2" />
                    <h3>Mohamed Zied</h3>
                    <p>Lead Developer</p>
                </div>
                <div class="flip-back">
                    <h3>Mohamed Zied</h3>
                    <p>Expert in PHP, JavaScript, and scalable web architecture. Loves mentoring junior devs.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Search Section -->
    <div class="ai-search-container" id="ai-search-container">
        <h2 data-aos="fade-up">Empowering visionaries to innovate, create, and lead the future of entrepreneurship.</h2>
        <p data-aos="fade-up">
            Our AI-powered search engine helps you find the right opportunities,
            courses, and resources to take your project to the next level.
        </p>
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
        <img src="image/pexels-mart-production-7550310.jpg" class="floating-image img1" />
        <img src="image/pexels-thirdman-7181111.jpg" class="floating-image img2" />
        <img src="image/pexels-rdne-7414214.jpg" class="floating-image img3" />
        <img src="image/pexels-edmond-dantes-8069014.jpg" class="floating-image img4" />
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
  const chatBox = document.getElementById("chat-box");
  const inputField = document.getElementById("ai-chat-input");
  const sendButton = document.getElementById("send-ai-btn");

  function addMessage(content, isUser = false) {
    const messageDiv = document.createElement("div");
    messageDiv.classList.add(isUser ? "user-message" : "ai-message");
    messageDiv.textContent = content;
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function simulateTyping(callback) {
    const typingDiv = document.createElement("div");
    typingDiv.classList.add("ai-message", "typing");
    typingDiv.textContent = "AI is typing...";
    chatBox.appendChild(typingDiv);
    chatBox.scrollTop = chatBox.scrollHeight;

    setTimeout(() => {
      chatBox.removeChild(typingDiv);
      callback();
    }, 2000);
  }

  sendButton.addEventListener("click", function () {
    const userText = inputField.value.trim();
    if (userText === "") return;
  
    addMessage(userText, true);
    inputField.value = "";
  
    simulateTyping(() => {
      getAIResponse(userText, (reply) => {
        addMessage(reply);
      });
    });
  });
  

  function getAIResponse(question, callback) {
fetch('./chatbot.php?q=' + encodeURIComponent(question))
      .then(res => res.json())
      .then(data => callback(data.response))
      .catch(() => callback("⚠️ Sorry, something went wrong while fetching info."));
  }
  
});
    </script>

    <!-- Footer -->
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

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialize AOS animation
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 1000,
                once: false,
            });

            // Counter animation
            const counters = document.querySelectorAll('.count');
            const speed = 200;
            
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const increment = target / speed;
                    
                    if(count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target;
                    }
                };
                
                updateCount();
            });
        });
    </script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
  const dropdowns = document.querySelectorAll(".dropdown");

  dropdowns.forEach((dropdown) => {
    const button = dropdown.querySelector(".dropbtn");
    const menu = dropdown.querySelector(".dropdown-menu");

    button.addEventListener("click", function (event) {
      event.stopPropagation(); // Prevent closing when clicking inside
      closeAllDropdowns();
      menu.classList.toggle("active");
    });
  });

  document.addEventListener("click", closeAllDropdowns);
  function closeAllDropdowns() {
    document.querySelectorAll(".dropdown-menu").forEach((menu) => {
      menu.classList.remove("active");
    });
  }
});
document.addEventListener("DOMContentLoaded", function () {
  const elements = document.querySelectorAll(".about-item, h2, p");

  function revealOnScroll() {
    elements.forEach((el) => {
      const rect = el.getBoundingClientRect();
      if (rect.top < window.innerHeight - 100) {
        el.classList.add("show");
      } else {
        el.classList.remove("show");
      }
    });
  }

  window.addEventListener("scroll", revealOnScroll);
  revealOnScroll(); // Trigger on page load
});
// JavaScript pour déclencher l'animation lorsque les images entrent dans la vue
document.addEventListener('DOMContentLoaded', function () {
    const images = document.querySelectorAll('.image');

    // Initialisation de l'IntersectionObserver
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show'); // Ajoute la classe 'show' pour animer l'image
                observer.unobserve(entry.target); // Arrête d'observer l'élément une fois qu'il est visible
            }
        });
    }, {
        threshold: 0.5, // L'animation est déclenchée lorsque 50% de l'image est visible
    });

    // Observation de chaque image
    images.forEach(image => {
        observer.observe(image);
    });
});

const cards = document.querySelectorAll('.card');
const totalCards = cards.length;
cards.forEach((card, index) => {
  card.style.setProperty('--index', index);
  card.style.setProperty('--quantity', totalCards);
});
document.addEventListener("DOMContentLoaded", function () {
  const counters = document.querySelectorAll(".count");

  function animateCounter(counter) {
      const target = +counter.getAttribute("data-target");
      let count = 0;
      const speed = target / 100; // Contrôle la vitesse de l'animation

      function updateCount() {
          if (count < target) {
              count += speed;
              counter.innerText = Math.floor(count);
              requestAnimationFrame(updateCount);
          } else {
              counter.innerText = target;
          }
      }
      updateCount();
  }

  const counterWrapper = document.querySelector(".counter-wrapper");
  let statsAnimated = false;

  function checkStats() {
      const position = counterWrapper.getBoundingClientRect().top;
      const screenHeight = window.innerHeight;
      if (position < screenHeight * 0.9 && !statsAnimated) {
          counters.forEach(counter => animateCounter(counter));
          statsAnimated = true;
      }
  }

  window.addEventListener("scroll", checkStats);
});



let next = document.querySelector('.custom-next');
let prev = document.querySelector('.custom-prev');

next.addEventListener('click', function() {
    let items = document.querySelectorAll('.custom-slide-item');
    document.querySelector('.custom-slider').appendChild(items[0]);
});

prev.addEventListener('click', function() {
    let items = document.querySelectorAll('.custom-slide-item');
    document.querySelector('.custom-slider').prepend(items[items.length - 1]);
});





document.addEventListener('DOMContentLoaded', function () {
  const seeMoreButtons = document.querySelectorAll('.seeMoreBtn');
  const modal = document.getElementById('eventModal');
  const closeModalBtn = document.querySelector('.close-btn');

  const eventTitle = document.getElementById('eventTitle');
  const eventLocation = document.getElementById('eventLocation');
  const eventTime = document.getElementById('eventTime');
  const eventDescription = document.getElementById('eventDescription');
  const eventImage = document.getElementById('eventImage');

  seeMoreButtons.forEach(button => {
    button.addEventListener('click', () => {
      eventTitle.textContent = button.dataset.title;
      eventLocation.textContent = button.dataset.location;
      eventTime.textContent = button.dataset.time; // Customize as needed
      eventDescription.textContent = button.dataset.description;

      // Set image source directly from data-image attribute
      eventImage.src = button.dataset.image;

      modal.style.display = 'block';
    });
  });

  closeModalBtn.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  window.addEventListener('click', event => {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  });
});
button.addEventListener('click', () => {
  console.log("Title from dataset: ", button.dataset.title); // Debug
  eventTitle.textContent = button.dataset.title;
  eventLocation.textContent = button.dataset.location;
  eventTime.textContent = button.dataset.time; // Customize as needed
  eventDescription.textContent = button.dataset.description;
  eventImage.src = button.dataset.image; // Set image source directly from data-image attribute
});

    </script>
</body>
</html>