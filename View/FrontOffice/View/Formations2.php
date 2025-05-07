<?php
require_once(__DIR__ . '/../../../Model/Formation.php');

$formationModel = new Formation();
$formations = $formationModel->getAllFormations();
usort($formations, function ($a, $b) {
  return strcmp($a['class_form'], $b['class_form']);
});
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
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <script src="script.js" defer></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
      <section class="home" id="home">
      <video autoplay muted loop class="background-video">
        <source src="image/Professorteaching.mp4" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
      <div class="overlay"></div>
      <div class="content" id="content">
        <h3>Formations</h3>
        <p>As humble experts of your domain we offer you Live formations about Business and entrepneurship with a set of programs that will skyrocket your ambitions to success 😀</p>
        <a href="#" class="btn">Get Ready with Us</a>
      </div>
    </section>
<div class="formations-header">
    <!-- Widgets Section -->
     <p> <p>
    <div class="static-widgets-container">
      <!-- Widget 1 -->
      <div class="static-widget" onclick="window.location.href='#';">
        <div class="static-widget-icon">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <h3>Business Intitiation</h3>
        <p class="static-WidgetDesc">Turn your idea into a real business. Learn step-by-step setup, legal basics, and planning.</p>
      </div>
      
      <!-- Widget 2 -->
      <div class="static-widget" onclick="window.location.href='#';">
        <div class="static-widget-icon">
          <i class="fas fa-certificate"></i>
        </div>
        <h3>Project Handling & Management</h3>
        <p class="static-WidgetDesc">Run projects smoothly—plan tasks, track progress, and hit deadlines without stress.</p>
      </div>
      
      <!-- Widget 3 -->
      <div class="static-widget" onclick="window.location.href='#';">
        <div class="static-widget-icon">
          <i class="fas fa-hands-helping"></i>
        </div>
        <h3>Entrepreneurship Maintenance</h3>
        <p class="static-WidgetDesc">Keep your business growing. Manage finances, improve systems, and avoid common pitfalls</p>
      </div>
    </div>
    <div class="sorting-container">
    <label for="sortOptions">Sort By:</label>
    <select id="sortOptions">
        <option value="default">Default</option>
        <option value="az">A-Z</option>
    </select>
    
    <label for="searchBox" style="margin-left: 20px;">Search:</label>
    <input type="text" id="searchBox" placeholder="Enter class name..." />
    <button id="searchButton" type="button" style="margin-left: 10px;">Search</button>
    <button id="exportPDF" class="orange-button">Export PDF</button>
</div>
</div>
<div class="widgets-container">
    <?php foreach ($formations as $formation): ?>
        <div class="widget" data-capacity="<?= htmlspecialchars($formation['capacity_form']) ?>">
            <div class="widget-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3><?= htmlspecialchars($formation['class_form']) ?></h3>
            <p class="WidgetDesc"><?= htmlspecialchars($formation['desc_form']) ?></p>
            <p class="capacity">
                <span class="slots-available"><?= htmlspecialchars($formation['capacity_form']) ?></span>/<?= htmlspecialchars($formation['capacity_form']) ?> Slots available
            </p>
            <br><br><br><br><br>
            <button class="see-more"
                    data-title="<?= htmlspecialchars($formation['class_form']) ?>" 
                    data-desc="<?= htmlspecialchars($formation['desc_form']) ?>"
                    data-price="<?= htmlspecialchars($formation['price_form']) ?>"
                    data-image="<?= htmlspecialchars($formation['image_form']) ?>">
                See more...
            </button>
        </div>
    <?php endforeach; ?>
</div>
<div class="custom-popup" id="successPopup" style="display: none;">
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

      </section>
    <div id="calendarPDF">
      <section class="calendar-section">
  <h2>📅 Upcoming Formations Calendar</h2>
  <div id="calendar"></div>
</section>
    </div>
    <script>
   const widgetsContainer = document.querySelector('.widgets-container');
const widgets = Array.from(widgetsContainer.children); // Store original widgets

// Set to default order on page load
widgets.forEach(widget => widgetsContainer.appendChild(widget));

document.getElementById('sortOptions').addEventListener('change', function() {
    if (this.value === 'az') {
        // Sort widgets based on title
        const sortedWidgets = [...widgets].sort((a, b) => {
            const titleA = a.querySelector('h3').textContent.toLowerCase();
            const titleB = b.querySelector('h3').textContent.toLowerCase();
            return titleA.localeCompare(titleB);
        });

        // Clear the container and append sorted widgets
        widgetsContainer.innerHTML = '';
        sortedWidgets.forEach(widget => widgetsContainer.appendChild(widget));
    } else {
        // Reset to original order (Default)
        widgetsContainer.innerHTML = ''; // Clear the container
        widgets.forEach(widget => widgetsContainer.appendChild(widget)); // Append original widgets
    }
});
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('searchButton').addEventListener('click', function() {
      const searchTerm = document.getElementById('searchBox').value.toLowerCase();
      const widgets = document.querySelectorAll('.widgets-container .widget');

      widgets.forEach(widget => {
          const title = widget.querySelector('h3').textContent.toLowerCase();
          if (title.includes(searchTerm)) {
              widget.style.display = 'block'; // Show matching widget
          } else {
              widget.style.display = 'none'; // Hide non-matching widget
          }
      });
  });

  document.getElementById('searchBox').addEventListener('input', function() {
      if (this.value === '') {
          const widgets = document.querySelectorAll('.widgets-container .widget');
          widgets.forEach(widget => {
              widget.style.display = 'block'; // Show all widgets
          });
      }
  });
});
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,listWeek'
    },
    events: [
      <?php foreach ($formations as $formation): ?>
        {
          title: "<?= htmlspecialchars($formation['class_form']) ?>",
          start: "<?= htmlspecialchars($formation['date_form']) ?>",
          description: "<?= htmlspecialchars($formation['desc_form']) ?>"
        },
      <?php endforeach; ?>
    ],
    eventClick: function(info) {
      alert(`Formation: ${info.event.title}\nDetails: ${info.event.extendedProps.description}`);
    }
  });
  calendar.render();
});
document.getElementById('exportPDF').addEventListener('click', function () {
    console.log("Export PDF button clicked");

    // Select the specific container for export
    const element = document.getElementById('calendarPDF');  // Export the specific section

    if (!element) {
        console.error("Element not found");
        return;
    }

    // Set options for html2pdf
    const opt = {
        margin:       0.5,
        filename:     'full_page_export.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    // Export the calendar section to PDF
    html2pdf().from(element).set(opt).save();
});
const seeMoreButtons = document.querySelectorAll(".see-more");
const popup = document.getElementById("successPopup");
const popupTitle = popup.querySelector(".popup-title");
const popupText = popup.querySelector(".popup-text");
const popupImage = popup.querySelector(".popup-image");
const subscribeButton = popup.querySelector(".btn-contact");
const subscriptionDateEl = document.getElementById("subscriptionDate");

seeMoreButtons.forEach(button => {
    button.addEventListener("click", () => {
        const title = button.getAttribute("data-title");
        const desc = button.getAttribute("data-desc");
        const price = button.getAttribute("data-price");
        const image = button.getAttribute("data-image");
        const selectedFormationId = button.getAttribute("data-formation-id") || null; // Adjust attribute name as needed

        // Populate popup content
        popupTitle.textContent = title;
        popupText.textContent = desc;
        popupImage.src = image;
        subscribeButton.textContent = `Subscribe for ${price} TND`;

        // Load stored subscription date from localStorage
        const storedDate = localStorage.getItem(`subscriptionDate_${title}`);
        if (subscriptionDateEl) {
            subscriptionDateEl.textContent = storedDate ? `Subscribed on: ${storedDate}` : "";
        }

        // Show/hide subscribe button based on subscription status
        subscribeButton.style.display = storedDate ? "none" : "inline-block";

        // Show the popup
        popup.style.display = "flex";
        document.body.style.overflow = "hidden"; // Prevent scrolling
    });
});

// Subscription logic
subscribeButton.addEventListener("click", function () {
    const currentDate = new Date().toLocaleDateString(); // Get current date
    const title = popupTitle.textContent;

    // Store subscription date in localStorage
    localStorage.setItem(`subscriptionDate_${title}`, currentDate);
    subscriptionDateEl.textContent = `Subscribed on: ${currentDate}`;

    // Hide subscribe button
    subscribeButton.style.display = "none";
    alert("Subscription successful!");
});

// Close popup functionality
const closePopup = popup.querySelector(".close-popup");
closePopup.addEventListener("click", function () {
    popup.style.display = "none";
    document.body.style.overflow = ""; // Restore scrolling
});
// Create and append cursor elements
const cursor = document.createElement('div');
cursor.classList.add('cursor');
document.body.appendChild(cursor);

const cursorDot = document.createElement('div');
cursorDot.classList.add('cursor-dot');
document.body.appendChild(cursorDot);

let mouseX = 0;
let mouseY = 0;
let cursorX = 0;
let cursorY = 0;
let dotX = 0;
let dotY = 0;
const delay = 0.1; // The delay factor for the dot's movement

function updateCursorPosition() {
    // Move the orange circle cursor directly to the mouse position
    cursorX += (mouseX - cursorX) * 0.1;
    cursorY += (mouseY - cursorY) * 0.1;

    // Move the black dot with a delay
    dotX += (mouseX - dotX) * delay;
    dotY += (mouseY - dotY) * delay;

    cursor.style.left = `${cursorX}px`;
    cursor.style.top = `${cursorY}px`;
    cursorDot.style.left = `${dotX}px`;
    cursorDot.style.top = `${dotY}px`;

    requestAnimationFrame(updateCursorPosition);
}

// Track mouse position
document.addEventListener('mousemove', (event) => {
    mouseX = event.pageX;
    mouseY = event.pageY;
});

// Make sure the cursor shows on popups
document.querySelectorAll('.custom-popup').forEach(popup => {
    popup.style.cursor = 'none';  // Ensure no default cursor
});

// Start the animation
updateCursorPosition();

    </script>
          </div>
        </div>
      </div>
      <p>Have questions or need more details? Reach out to us at NextStep.corp@gmail.com — we’d love to hear from you!</p>
  </body>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  

</html>