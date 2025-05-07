<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nextstep";


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Récupérer le paramètre de tri
$sort = $_GET['sort'] ?? '';

// Fonction de tri pour les tableaux
function sortAlphabetically(&$array, $key, $order = 'asc') {
    usort($array, function($a, $b) use ($key, $order) {
        if ($order === 'asc') {
            return strcmp($a[$key], $b[$key]);
        } else {
            return strcmp($b[$key], $a[$key]);
        }
    });
}

// Fetch Nitro Plans
$stmtNitro = $conn->query("SELECT * FROM nitro");
$nitroPlans = $stmtNitro->fetchAll(PDO::FETCH_ASSOC);

// Fetch Working Spaces
$stmtWorkspace = $conn->query("SELECT * FROM workingspace");
$workspaces = $stmtWorkspace->fetchAll(PDO::FETCH_ASSOC);

// Fetch Workshops
$stmtWorkshop = $conn->query("SELECT * FROM workshop");
$workshops = $stmtWorkshop->fetchAll(PDO::FETCH_ASSOC);

// Appliquer le tri si demandé
if ($sort === 'asc') {
    sortAlphabetically($workspaces, 'nom_workingspace', 'asc');
    sortAlphabetically($workshops, 'nom_workshop', 'asc');
} elseif ($sort === 'desc') {
    sortAlphabetically($workspaces, 'nom_workingspace', 'desc');
    sortAlphabetically($workshops, 'nom_workshop', 'desc');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NextStep | Incubator</title>
  <link rel="stylesheet" href="incubator.css" />
  <link rel="website icon" type="PNG" href="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
  <style>
    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      padding-top: 60px;
    }

    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 25px;
      border: 1px solid #888;
      width: 90%;
      max-width: 500px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .close {
      color: #aaa;
      font-size: 28px;
      font-weight: bold;
      position: absolute;
      top: 10px;
      right: 25px;
      cursor: pointer;
    }

    .close:hover {
      color: #333;
    }

    /* Form styles */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-bottom: 20px;
    }
    
    .full-width {
      grid-column: span 2;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 600;
      color: #333;
    }
    
    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
    }
    
    .form-group textarea {
      resize: vertical;
      min-height: 80px;
    }
    
    input[type="number"] {
      -moz-appearance: textfield;
    }
    
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    
    /* Button styles */
    .form-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
    
    .confirm-btn {
      background-color: #4CAF50;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      flex: 1;
      margin-right: 10px;
    }
    .confirme-btn {
      background-color: #4CAF50;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      flex: 1;
      margin-right: 10px;
    }
    .decline-btn {
      background-color: #f44336;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      flex: 1;
      margin-left: 10px;
    }
    
    .confirm-btn:hover {
      background-color: #45a049;
    }
    
    .decline-btn:hover {
      background-color: #d32f2f;
    }
    
    /* Table button styles */
    .rent-btn, .participate-btn {
      background-color: rgb(26, 92, 225);
      color: white;
      padding: 8px 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    
    .rent-btn:hover, .participate-btn:hover {
      background-color: rgb(26, 92, 225);
    }

    /* Search and sort styles */
    .search-and-sort {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      max-width: 500px;
      margin-left: auto;
      margin-right: auto;
    }

    .search-input {
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      width: 60%;
    }

    .sort-buttons {
      display: flex;
      gap: 10px;
    }

    .sort-btn {
      padding: 8px 12px;
      background-color:rgb(26, 92, 225);
      color: white;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.3s;
    }

    .sort-btn:hover {
      background-color:rgb(26, 92, 225);
    }
  </style>
</head>

<body>
  <!-- Navigation and other sections remain the same -->
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
            Incubator <i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li><a href="incubator.php#nitro-section"><i class="fas fa-bolt"></i> Nitro Plans</a></li>
            <li><a href="incubator.php#workspace-section"><i class="fas fa-chair"></i> Working Space</a></li>
            <li><a href="incubator.php#workshop-section"><i class="fas fa-chalkboard-teacher"></i> Workshops</a></li>
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
        <li class="dropdown">
          <button class="dropbtn">
            Startup <i class="fas fa-chevron-down"></i>
          </button>
          <ul class="dropdown-menu">
            <li>
              <a href="startup.php"><i class="fas fa-cogs"></i> Startup</a>
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

  <!-- Nitro Section -->
  <center>
  <section id="nitro-section" class="py-12 px-4 bg-gray-100" 
         style="background-image: url(''); background-size: cover; background-position: center; background-repeat: no-repeat;">

  <style>
    .container pre {
      font-size: 3rem;
      text-align: center;
      color: #fff;
      background: linear-gradient(to right, #ff6b6b, #5f27cd);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 30px;
      text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
      white-space: pre-wrap; /* Allows wrapping like normal text */
    }
  </style>
</head>
<body>
  <div class="container">
    <br><br><br>
    <pre>Make Your Startup Reach The Stars</pre>
  </div>
    <div class="rocket" style="float: left;">
      <img src="image/rocket2.gif" alt="beginner" width="700">
    </div>
    <br><br><br>
    <div class="flex flex-wrap justify-center gap-8">
      <?php foreach($nitroPlans as $plan): ?>
        <div class="plan start">
          
            <img src="image/fire.gif" width="30">
            
          
          <div class="title"><?= htmlspecialchars($plan['nitro_name']) ?></div>
          <ul class="features">
            <li>Period: <?= htmlspecialchars($plan['nitro_period']) ?></li>
          </ul>
          <button class="pricing nitro-btn" style=" background: linear-gradient(135deg, #6a11cb, #2575fc); /* Beautiful gradient */
color: #fff;
padding: 12px 24px;
border: none; /* Remove the harsh solid border */
border-radius: 8px;
font-size: 16px;
font-weight: bold;
cursor: pointer;
transition: background 0.3s ease, transform 0.2s ease;
box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Adds depth */ " data-id="<?= $plan['id_nitro'] ?>"><?= htmlspecialchars($plan['nitro_price']) ?> TND<sup></sup></button>
          
         
        </div>
      <?php endforeach; ?>
    </div>
  </section>
  </center>
  <style>
       

       .nitro-btn:hover {
         background-color:rgb(25, 46, 208);
         transform: scale(1.05);
       }
       
       .nitro-btn:active {
         background-color:rgb(110, 10, 204);
         transform: scale(0.98);
       }
       </style>
  <br><br>

  <!-- Working Space Section -->
  <style>
      .bg-workspace {
  background-image: url('https://cdn.dribbble.com/users/3458281/screenshots/6557426/workspace4-3.gif');
  background-size: cover;
  background-position: center;
}

    </style>
  

  <section id="workspace-section" class="py-12 px-4 bg-workspace">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Working Space</h2>
    <center>
    
    
    </center>
    <style>.gradient-text {
  font-size: 2rem; /* optional, for better appearance */
  color: #fff;
  background: linear-gradient(to right, #ff6b6b, #5f27cd);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
}</style>
    

    <div class="search-and-sort">
      <input type="text" id="workspaceSearch" placeholder="Rechercher par nom..." class="search-input">
      <div class="sort-buttons">
        <a href="?sort=asc#workspace-section" class="sort-btn">A-Z</a>
        <a href="?sort=desc#workspace-section" class="sort-btn">Z-A</a>
      </div>
    </div>

    <div class="overflow-x-auto max-w-5xl mx-auto">
      <table class="min-w-full border border-gray-200 shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-gray-700 text-left">
          <tr>
            <th class="py-3 px-6">Name</th>
            <th class="py-3 px-6">Location</th>
            <th class="py-3 px-6">Surface (m²)</th>
            <th class="py-3 px-6">Price (TND)</th>
            <th class="py-3 px-6">Capacity</th>
            <th class="py-3 px-6 text-center">Action</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <?php foreach($workspaces as $workspace): ?>
            <tr class="border-t">
              <td class="py-3 px-6"><?= htmlspecialchars($workspace['nom_workingspace']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($workspace['localisation']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($workspace['surface']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($workspace['prix_workingspace']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($workspace['capacite_workingspace']) ?></td>
              <td class="py-3 px-6 text-center space-x-2">
  <button class="rent-btn">Rent</button>
  <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($workspace['localisation']) ?>" target="_blank" class="map-btn" title="View on Google Maps">
    🗺️
  </a>
</td>
<style>.map-btn {
  padding: 6px 10px;
  background-color:rgb(30, 11, 238);
  color: white;
  border-radius: 4px;
  text-decoration: none;
  font-size: 0.875rem;
  transition: background-color 0.3s;
}

.map-btn:hover {
  background-color: rgb(30, 11, 238);
}</style>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
 <br>

  <!-- Workshop Section -->
  <center>
    <style>
      .bg-workshop {
  background-image: url('https://videos.openai.com/vg-assets/assets%2Ftask_01jtn0d0kwfx89bnpty8ck85cs%2F1746609306_img_3.webp?st=2025-05-07T08%3A10%3A44Z&se=2025-05-13T09%3A10%3A44Z&sks=b&skt=2025-05-07T08%3A10%3A44Z&ske=2025-05-13T09%3A10%3A44Z&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skoid=3d249c53-07fa-4ba4-9b65-0bf8eb4ea46a&skv=2019-02-02&sv=2018-11-09&sr=b&sp=r&spr=https%2Chttp&sig=j7cn9NrcWC4vdmHvNsd%2BTPKKECIdi36Ug4P4Z31DLSw%3D&az=oaivgprodscus');
  background-size: cover;
  background-position: center;
}

    </style>
  <section id="workshop-section" class="py-12 px-4 bg-workshop">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Workshops</h2>
    

    <div class="search-and-sort">
      <input type="text" id="workshopSearch" placeholder="Rechercher par nom..." class="search-input">
      <div class="sort-buttons">
        <a href="?sort=asc#workshop-section" class="sort-btn">Z-A</a>
        <a href="?sort=desc#workshop-section" class="sort-btn">A-Z</a>
      </div>
    </div>

    <div style="display: flex; justify-content: center; align-items: flex-start; gap: 40px; flex-wrap: wrap;">
      <div class="overflow-x-auto max-w-5xl mx-auto">
      <table class="min-w-full border border-gray-200 shadow-md rounded-lg overflow-hidden backdrop-blur-md" style="background-color: rgba(255, 255, 255, 0.3);">

          <thead class="bg-gray-100 text-gray-700 text-left">
            <tr>
              <th class="py-3 px-6">Name</th>
              <th class="py-3 px-6">Date</th>
              <th class="py-3 px-6">Place</th>
              <th class="py-3 px-6">Subject</th>
              <th class="py-3 px-6">Hoster</th>
              <th class="py-3 px-6 text-center">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php foreach($workshops as $workshop): ?>
              <tr class="border-t">
                <td class="py-3 px-6"><?= htmlspecialchars($workshop['nom_workshop']) ?></td>
                <td class="py-3 px-6"><?= htmlspecialchars($workshop['date_workshop']) ?></td>
                <td class="py-3 px-6"><?= htmlspecialchars($workshop['lieu_workshop']) ?></td>
                <td class="py-3 px-6"><?= htmlspecialchars($workshop['sujet_workshop']) ?></td>
                <td class="py-3 px-6"><?= htmlspecialchars($workshop['responsable']) ?></td>
                <td class="py-3 px-6 text-center">
                  <button class="participate-btn">Participate</button>
                  <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($workshop['lieu_workshop']) ?>" target="_blank" class="map-btn" title="View on Google Maps">
    🗺️
  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
  </center>
  

 <!-- Startup Name Form Modal -->
<div id="startupNameModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 style="margin-bottom: 20px; text-align: center;">Startup Information</h2>
    <form id="startupForm">
      <div class="form-group full-width">
        <label for="startupName">Startup Name</label>
        <input type="text" id="startupName" name="startupName" placeholder="Your startup name">
      </div>
      
      
      <div class="form-buttons">
        <button type="button" class="confirme-btn" id="submitStartup">Continue</button>
        <button type="button" class="decline-btn">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <pre><i class="fas fa-check-circle" style="color: green; margin-right: 10px;"></i>Nitro bought successfully!</pre>
  </div>
</div>



<!-- Add this with your other script includes -->
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

<!-- Success Modal for Workshop -->
<div id="workshopSuccessModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div style="text-align: center;">
      <img src="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png" 
           alt="Success Icon" 
           style="height: 200px; width: 200px;">
      <h2>Workshop Registration Successful!</h2>
      <pre>Your registration ID: <span id="registrationId"></span></pre>
      <div id="qrcode" style="margin: 20px auto; width:200px; height:200px;"></div>
      <pre>Show this QR code at the workshop entrance</pre>

      <!-- Beautiful Info Card -->
      <div id="workshopInfoCard" style="text-align: left; margin: 20px auto; padding: 15px; border: 1px solid #ddd; border-radius: 10px; max-width: 400px; background-color: #f9f9f9;">
        <h3 style="text-align: center; margin-bottom: 10px;">Workshop Details</h3>
        <p><strong>Workshop:</strong> <span id="displayWorkshopName"></span></p>
        <p><strong>Date:</strong> <span id="displayWorkshopDate"></span></p>
        <p><strong>Registration ID:</strong> <span id="displayRegistrationId"></span></p>
        <p><strong>Participant:</strong> <span id="displayParticipantName">User Name</span></p>
        <p><em>📌 Show this QR code at the workshop entrance</em></p>
      </div>

      <button id="downloadQR" class="confirm-btn" style="margin-top: 15px;">
        <i class="fas fa-download"></i> Download QR Code
      </button>
    </div>
  </div>
</div>

<script>
  // Workshop Participation with QR Code
  var participateButtons = document.querySelectorAll(".participate-btn");
  var closeWorkshopModal = workshopSuccessModal.querySelector(".close");

  participateButtons.forEach(function(button) {
    button.addEventListener("click", function() {
      const workshopRow = this.closest('tr');
      const workshopName = workshopRow.cells[0].textContent;
      const workshopDate = workshopRow.cells[1].textContent;

      // Generate a unique registration ID
      const registrationId = 'NEXTSTEP-' + Math.random().toString(36).substr(2, 9).toUpperCase();

      // Display registration ID in modal
      document.getElementById("registrationId").textContent = registrationId;

      // QR data
      
      const qrData = 
      `TITLE: NextStep Workshops\n` +
  `WORKSHOP: ${workshopName}\n` +
  `DATE: ${workshopDate}\n` +
  `REGISTRATION ID: ${registrationId}\n` +
  `PARTICIPANT: User Name\n` +
  `Show this QR code at the workshop entrance!`;
  
      // Clear previous QR
      document.getElementById("qrcode").innerHTML = "";
      
      // Generate new QR code
      new QRCode(document.getElementById("qrcode"), {
        text: qrData,
        width: 200,
        height: 200,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
      });

      // Fill beautiful info card
      document.getElementById("displayWorkshopName").textContent = workshopName;
      document.getElementById("displayWorkshopDate").textContent = workshopDate;
      document.getElementById("displayRegistrationId").textContent = registrationId;
      // If you have a real participant name, set it here:
      // document.getElementById("displayParticipantName").textContent = currentUserName;

      workshopSuccessModal.style.display = "block";
    });
  });

  // Download QR Code functionality
  document.getElementById("downloadQR").addEventListener("click", function() {
    const canvas = document.querySelector("#qrcode canvas");
    const dataURL = canvas.toDataURL("image/png");

    const link = document.createElement("a");
    link.download = "workshop-registration.png";
    link.href = dataURL;
    link.click();
  });

  // Close modal
  closeWorkshopModal.onclick = function() {
    workshopSuccessModal.style.display = "none";
  };
</script>

  <style>
  /* Add to your existing styles */
#qrcode {
  border: 1px solid #ddd;
  padding: 10px;
  background: white;
  border-radius: 5px;
}

#qrcode img {
  margin: 0 auto;
}

#downloadQR {
  background-color: #2196F3;
}

#downloadQR:hover {
  background-color: #0b7dda;
}
</style>
<!-- Rent Modal Form -->
<div id="rentModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 style="margin-bottom: 20px; text-align: center;">Workspace Rental Request</h2>
    <form id="rentForm">
      <div class="form-grid">
        <div class="form-group">
          <label for="fullName">Name *</label>
          <input type="text" id="fullName" name="fullName" required placeholder="Your Name">
        </div>
        <div class="form-group">
          <label for="fullName">SurnameName *</label>
          <input type="text" id="fullName" name="fullName" required placeholder="Your  Surname">
        </div>
        
        
        <div class="form-group">
          <label for="rentDuration">Duration (days) *</label>
          <input type="number" id="rentDuration" name="rentDuration" min="1" max="365" required placeholder="1-365 days">
        </div>
        <div class="form-group full-width">
          <label for="rentReason">Purpose of Rental *</label>
          <textarea id="rentReason" name="rentReason" rows="3" required placeholder="Describe your intended use of the workspace"></textarea>
        </div>
        <div class="form-group full-width">
          <label for="specialRequests">Special Requests</label>
          <textarea id="specialRequests" name="specialRequests" rows="2" placeholder="Any additional requirements"></textarea>
        </div>
      </div>
      <div class="form-buttons">
        <button type="button" class="confirm-btn">Submit Request</button>
        <button type="button" class="decline-btn">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Nitro Modal functionality
  var modal = document.getElementById("successModal");
  var buttons = document.querySelectorAll(".pricing");
  var spanList = document.getElementsByClassName("close");

  // Startup Name Modal
  var startupModal = document.getElementById("startupNameModal");
  var nitroButtons = document.querySelectorAll(".nitro-btn");
  var submitStartupBtn = document.getElementById("submitStartup");
  var closeStartupModal = startupModal.querySelector(".close");

  buttons.forEach(function(button) {
    button.onclick = function() {
      startupModal.style.display = "block";
    };
  });

  let selectedNitroId = null; // Global variable to store selected nitro ID

var nitroButtons = document.querySelectorAll(".nitro-btn");

nitroButtons.forEach(function(button) {
  button.addEventListener("click", function() {
    selectedNitroId = this.getAttribute("data-id");
    
    startupModal.style.display = "block";
  });
});

submitStartupBtn.onclick = function () {
  const startupName = document.getElementById("startupName").value;
  const nitro = selectedNitroId;

  const formData = new FormData();
  formData.append("startupName", startupName);
  formData.append("selectedNitroId", nitro);

  fetch("affectNitroHandler.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
      console.log("Server response:", data);

      // Show the success modal
      startupModal.style.display = "none";
      modal.style.display = "block";

      // Optional: show server message in modal
      document.getElementById("modal-message").innerText = data;

      setTimeout(function () {
        modal.style.display = "none";
      }, 3000);
    })
};


  closeStartupModal.onclick = function() {
    startupModal.style.display = "none";
  };

  document.querySelector("#startupNameModal .decline-btn").onclick = function() {
    startupModal.style.display = "none";
  };

  // Close all modals when clicking on their (x) span
  Array.from(spanList).forEach(function(span) {
    span.onclick = function() {
      span.parentElement.parentElement.style.display = "none";
    };
  });

  // Rent Modal functionality
  var rentModal = document.getElementById("rentModal");
  var rentButtons = document.querySelectorAll(".rent-btn");
  var confirmBtn = document.querySelector(".confirm-btn");
  var declineBtn = document.querySelector(".decline-btn");
  var closeRentModal = rentModal.querySelector(".close");
  var rentDuration = document.getElementById("rentDuration");

  // Validate duration input
  rentDuration.addEventListener('input', function() {
    if (this.value > 365) {
      this.value = 365;
      alert("Maximum rental duration is 365 days");
    }
  });

  // Open rent modal with workspace details
  rentButtons.forEach(function(button) {
    button.addEventListener("click", function() {
      // Clear form on open
      document.getElementById("rentForm").reset();
      rentModal.style.display = "block";
    });
  });

  // Confirm button action with validation
  confirmBtn.onclick = function() {
    const form = document.getElementById("rentForm");
    const duration = rentDuration.value;
    
    if (!form.checkValidity()) {
      alert("Please fill in all required fields (marked with *)");
      return;
    }
    
    if (duration > 365) {
      alert("Rental duration cannot exceed 365 days");
      return;
    }
    
    // Collect all form data
    const formData = {
      name: document.getElementById("fullName").value,
      email: document.getElementById("email").value,
      phone: document.getElementById("phone").value,
      duration: duration,
      reason: document.getElementById("rentReason").value,
      requests: document.getElementById("specialRequests").value
    };
    
    // Here you would typically send formData to your server
    console.log("Form submission:", formData);
    alert("Rental request submitted successfully!\n\nWe'll contact you shortly to confirm your booking.");
    rentModal.style.display = "none";
  };

  // Decline button action
  declineBtn.onclick = function() {
    rentModal.style.display = "none";
  };

  // Close rent modal when X is clicked
  closeRentModal.onclick = function() {
    rentModal.style.display = "none";
  };

  // Workshop Success Modal
  var workshopSuccessModal = document.getElementById("workshopSuccessModal");
  var participateButtons = document.querySelectorAll(".participate-btn");
  var closeWorkshopModal = workshopSuccessModal.querySelector(".close");

  participateButtons.forEach(function(button) {
    button.addEventListener("click", function() {
      const workshopName = this.getAttribute('data-workshop');
      workshopSuccessModal.style.display = "block";
      
      // Here you would typically send the participation request to your server
      console.log("Participating in workshop:", workshopName);
    });
  });

  closeWorkshopModal.onclick = function() {
    workshopSuccessModal.style.display = "none";
  };

  // Fonction de recherche lettre par lettre
  function setupSearch() {
    // Pour les espaces de travail
    const workspaceSearch = document.getElementById('workspaceSearch');
    const workspaceRows = document.querySelectorAll('#workspace-section tbody tr');
    
    if (workspaceSearch) {
      workspaceSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        workspaceRows.forEach(row => {
          const name = row.cells[0].textContent.toLowerCase();
          if (name.includes(searchTerm)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    }
    
    // Pour les ateliers
    const workshopSearch = document.getElementById('workshopSearch');
    const workshopRows = document.querySelectorAll('#workshop-section tbody tr');
    
    if (workshopSearch) {
      workshopSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        workshopRows.forEach(row => {
          const name = row.cells[0].textContent.toLowerCase();
          if (name.includes(searchTerm)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    }
  }

  // Appeler la fonction au chargement
  document.addEventListener('DOMContentLoaded', setupSearch);

  // Close modal when clicking outside
  window.onclick = function(event) {
    if (event.target == rentModal) {
      rentModal.style.display = "none";
    }
    if (event.target == modal) {
      modal.style.display = "none";
    }
    if (event.target == workshopSuccessModal) {
      workshopSuccessModal.style.display = "none";
    }
    if (event.target == startupModal) {
      startupModal.style.display = "none";
    }
  };
</script>
</body>
</html>