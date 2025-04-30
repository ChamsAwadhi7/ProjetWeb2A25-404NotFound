<?php
require_once(__DIR__ . '/../../BackOffice/Model/Formation.php');


$formationModel = new Formation();
$formations = $formationModel->getAllFormations();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formations & ParticipantsShip</title>
    <link rel="stylesheet" href="styles.css"/>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <script src="script.js" defer></script> 
  </head>
  <body>
    <nav class="navbar">
      <div class="logo">
        <a href="index.html">
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
            Incubator <i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li><a href="incubator.html#nitro-section"><i class="fas fa-bolt"></i> Nitro Plans</a></li>
            <li><a href="incubator.html#workspace-section"><i class="fas fa-chair"></i> Working Space</a></li>
            <li><a href="incubator.html#workshop-section"><i class="fas fa-chalkboard-teacher"></i> Workshops</a></li>
          </ul>
        </li>
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
              <li>
                <a href="#"
                  ><i class="fas fa-users"></i> Collaborative Ventures</a
                >
              </li>
              <li>
                <a href="#"
                  ><i class="fas fa-dollar-sign"></i> Funding Opportunities</a
                >
              </li>
              <li>
                <a href="#"><i class="fas fa-handshake"></i> Partnerships</a>
              </li>
            </ul>
          </li>
          <li class="dropdown">
            <button class="dropbtn">
              Our Courses <i class="fas fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu">
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
              Formations & ParticipantsShip<i class="fas fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu">
              <li>
                <a href="#"><i class="fas fa-desktop"></i>Formations</a>
              </li>
              <li>
                <a href="#"><i class="fa-solid fa-photo-film"></i>Gallery</a>
              </li>
              <li>
                <a href="#"><i class="fas fa-user"></i>ParticipantsShip Information</a>
              </li>
              <li>
                <a href="#"><i class="fas fa-info-circle"></i>Additional Info</a>
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
<div class="formations-header">
    <h1>Formations</h1>
     <p>As humble experts of your domain we offer you Live formations about Business and entrepneurship with a set of programs that will skyrocket your ambitions to success 😀</p></div>
    <!-- Widgets Section -->
     
    <div class="widgets-container">
      <!-- Widget 1 -->
      <div class="widget" onclick="window.location.href='#';">
        <div class="widget-icon">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <h3>Business Intitiation</h3>
        <p class="WidgetDesc">Turn your idea into a real business. Learn step-by-step setup, legal basics, and planning.</p>
      </div>
      
      <!-- Widget 2 -->
      <div class="widget" onclick="window.location.href='#';">
        <div class="widget-icon">
          <i class="fas fa-certificate"></i>
        </div>
        <h3>Project Handling & Management</h3>
        <p class="WidgetDesc">Run projects smoothly—plan tasks, track progress, and hit deadlines without stress.</p>
      </div>
      
      <!-- Widget 3 -->
      <div class="widget" onclick="window.location.href='#';">
        <div class="widget-icon">
          <i class="fas fa-hands-helping"></i>
        </div>
        <h3>Entrepreneurship Maintenance</h3>
        <p class="WidgetDesc">Keep your business growing. Manage finances, improve systems, and avoid common pitfalls</p>
      </div>
    </div>

    <div class="widgets-container">
    <?php foreach ($formations as $formation): ?>
        <div class="widget">
            <div class="widget-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3><?= htmlspecialchars($formation['class_form']) ?></h3>
            <p class="WidgetDesc"><?= htmlspecialchars($formation['desc_form']) ?></p>
            <br><br><br><br>
            <button class="see-more"
                    data-title="<?= htmlspecialchars($formation['class_form']) ?>" 
                    data-desc="<?= htmlspecialchars($formation['desc_form']) ?>"
                    data-price="<?= htmlspecialchars($formation['price_form']) ?>"
                    data-image="image/Business-growth.jpg">
                    

                See more...
            </button>
        </div>
    <?php endforeach; ?>
</div>
<div class="custom-popup" id="successPopup">
    <div class="popup-content">
        <span class="close-popup">&times;</span>
        <img class="popup-image" src="" alt="">
        <h3 class="popup-title"></h3>
        <p class="popup-text"></p>
        <button class="btn-contact">Subscribe for 2.99TND</button>
        <div class="subscribed-date">
        <p id="subscriptionDate"></p>
</div>
    </div>
</div>
<style>
  .see-more {
  background-color: #ffd500; 
  border-radius: 5px; 
  color: #2563eb; 
  text-decoration: underline; 
  font-size: 0.9rem; 
  padding: 10px 20px; 
  cursor: pointer; 
  display: inline-block; 
  margin-top: 10px; 
  transition: color 0.3s ease; 
  
}
.see-more:hover {
  color: #1a4dbb;
}
.widgets-container {
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
  gap : 20px
  padding: 40px 20px;
  opacity:1  !important;
  max-width: 1200px;
  margin: 0 auto;
}

.widget {
  width: 340px;
  min-height: 380px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  padding:25px;
  margin: 15px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
}

.widget:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.widget-icon {
  font-size: 2.5rem;
  margin-bottom: 15px;
  color: #fb943b;
}

.widget h3 {
  margin-bottom: 15px;
  color: #333;
}

.widget p {
  color: #666;
  line-height: 1.6;
}
.participation-date {
    margin-top: 20px;
    font-size: 1.1rem;
    color: #333;
    font-weight: bold;
}
</style>
      </section>
    <script>
document.querySelectorAll('.see-more').forEach(button => {
    button.addEventListener('click', function () {
        const title = this.getAttribute('data-title');
        const description = this.getAttribute('data-desc');
        const price = this.getAttribute('data-price');
        const imageSrc = this.getAttribute('data-image');

        console.log('See more clicked for widget: ' + title);

        // Populate the popup BEFORE referencing title
        document.querySelector('.popup-title').textContent = title;
        document.querySelector('.popup-text').textContent = description;
        document.querySelector('.popup-image').setAttribute('src', imageSrc);

        const subscriptionDateEl = document.getElementById('subscriptionDate');
        const subscribeBtn = document.querySelector('.btn-contact');

        console.log('Stored date from localStorage for ' + title + ': ' + localStorage.getItem(`subscriptionDate_${title}`));

        // Load stored date from localStorage
        const storedDate = localStorage.getItem(`subscriptionDate_${title}`);
        if (subscriptionDateEl) {
            if (storedDate) {
                console.log('Displaying stored date: ' + storedDate);
                subscriptionDateEl.textContent = `Subscribed on: ${storedDate}`;
            } else {
                console.log('No stored date available.');
                subscriptionDateEl.textContent = '';
            }
        }

        // Replace old click listeners
        const newBtn = subscribeBtn.cloneNode(true);
        subscribeBtn.parentNode.replaceChild(newBtn, subscribeBtn);

        // Show/hide button based on subscription
        newBtn.style.display = storedDate ? 'none' : 'inline-block';

        // Add working click listener for this widget
        newBtn.addEventListener('click', function () {
            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('en-GB');

            console.log('Subscription clicked, saving date: ' + formattedDate);
            localStorage.setItem(`subscriptionDate_${title}`, formattedDate);

            if (subscriptionDateEl) {
                subscriptionDateEl.textContent = `Subscribed on: ${formattedDate}`;
                console.log('Updated subscription date in popup: ' + formattedDate);
            }

            newBtn.style.display = 'none';

            // AJAX Request to send subscription data to the server
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../../BackOffice/Controller/ParticipationController.php', true);  // Adjust this to your path
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log('Subscription saved successfully.');
                    // Handle any server response if necessary
                } else {
                    console.error('Failed to save subscription.');
                }
            };
            xhr.send('title=' + encodeURIComponent(title) + '&date=' + encodeURIComponent(formattedDate));
        });

        // Show popup
        document.getElementById('successPopup').style.display = 'block';
    });
});

// Close popup logic
document.querySelector('.close-popup').addEventListener('click', function () {
    document.getElementById('successPopup').style.display = 'none';
});


    </script>
          </div>
        </div>
      </div>
      <p>Have questions or need more details? Reach out to us at NextStep.corp@gmail.com — we’d love to hear from you!</p>
  </body>
</html>