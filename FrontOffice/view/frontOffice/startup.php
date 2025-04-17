<?php
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Startups</title>
    <link rel="stylesheet" href="incubator.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <header>
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
                    <a href="startup.html"><i class="fas fa-cogs"></i> Startup</a>
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
    </header>

    <!-- Startup Page Section -->
    <section class="startups">
        <div class="container">
          <br><br>
            <h3>Our Startups</h3>
            <h3>Here are some of the exciting startups we're working with. Get inspired and join us!</h3>
            <br><br>
            <div class="startup-cards">
                <?php
                include_once '../../controller/startupC.php'; 
                include_once '../../model/startup.php'; 

                $startupC = new startupC(); 
                $startups = $startupC->liststartup(); 

                foreach ($startups as $startup) {
                    echo "<div class='startup-card'>";
                    echo "<img src='" . htmlspecialchars($startup['img_startup']) . "' alt='" . htmlspecialchars($startup['nom_startup']) . "' />";
                    echo "<h3>" . htmlspecialchars($startup['nom_startup']) . "</h3>";
                    echo "<h5><strong>Hoster Name:</strong> " . htmlspecialchars($startup['nom_hoster']) . "</h5>";
                    echo "<h5><strong>Hoster Surname:</strong> " . htmlspecialchars($startup['prenom_hoster']) . "</h5>";
                    echo "<h5><strong>Purpose:</strong> " . htmlspecialchars($startup['but_startup']) . "</h5>";
                    echo "<h5><strong>Description:</strong> " . htmlspecialchars($startup['desc_startup']) . "</h5>";
                    echo "<h5><strong>Launch Date:</strong> " . htmlspecialchars($startup['date_startup']) . "</h5>";
                    echo "<button class='invest-btn'><i class='fas fa-money-bill-wave'></i> Invest</button>";
                    echo "<form method='POST' action='startup.php' style='display:inline;'>
                            <input type='hidden' name='delete_id' value='" . htmlspecialchars($startup['startup_id_id']) . "' />
                            <button type='submit' class='delete-btn' style='background-color: red; color: white; border: none; padding: 10px 15px; cursor: pointer;' onclick='return confirm(\"Are you sure you want to delete this startup?\")'>
                                <i class='fas fa-trash'></i> Delete
                            </button>
                          </form>";
                    echo "<button class='edit-btn' style='background-color: blue; color: white; border: none; padding: 10px 15px; cursor: pointer;' onclick='editStartup(" . json_encode($startup) . ")'>
                            <i class='fas fa-edit'></i> Edit
                          </button>";
                    echo "</div>";
                }
                ?>
            </div>

            <!-- Startup Button -->
            <div class="start-your-startup">
                <button id="startStartupBtn">Start Your Startup</button>
            </div>
        </div>
    </section>

    <!-- Startup-->
    <center>
    <div class="startup-form-container" id="startupFormContainer" style="display:none;">
        <div class="form-container">
            <h2 id="formTitle">Start Your Startup</h2>
            <form id="startupForm" method="POST" action="startup.php" enctype="multipart/form-data">
                <input type="hidden" id="startup_id_id" name="startup_id_id" />
                <label for="nom_startup">Startup Name</label>
                <input type="text" id="nom_startup" name="nom_startup" required />

                <label for="nom_hoster">Hoster Name</label>
                <input type="text" id="nom_hoster" name="nom_hoster" required />

                <label for="prenom_hoster">Hoster Surname</label>
                <input type="text" id="prenom_hoster" name="prenom_hoster" required />

                <label for="but_startup">Purpose</label>
                <input type="text" id="but_startup" name="but_startup" required />

                <label for="desc_startup">Description</label>
                <textarea id="desc_startup" name="desc_startup" required></textarea>

                <label for="date_startup">Launch Date</label>
                <input type="date" id="date_startup" name="date_startup" required />

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

           
            $startup = new startup_id($startup_id_id, $nom_startup, $nom_hoster, $prenom_hoster, $but_startup, $desc_startup, $date_startup, $img_startup);

            if ($startup_id_id) {
                
                $startupC->updatestartup($startup, $startup_id_id);
            } else {
                
                $startupC->addstartup($startup);
            }

            
            header("Location: startup.php");
            exit();
        }
    }
    ?>

    <script src="incubator.js"></script>
    <script>
        
        document.getElementById("startStartupBtn").addEventListener("click", function() {
            const formContainer = document.getElementById("startupFormContainer");
            document.getElementById("formTitle").innerText = "Start Your Startup";
            document.getElementById("formSubmitButton").innerText = "Submit";
            document.getElementById("startupForm").reset();
            formContainer.style.display = "block";
        });

        
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

  form.addEventListener("submit", function (e) {
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
</body>
</html>

<?php
ob_end_flush();
?>
