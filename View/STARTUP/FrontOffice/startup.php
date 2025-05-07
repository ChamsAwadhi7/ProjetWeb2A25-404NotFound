<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header('Location: ../../login.php');
    exit;
}
$utilisateur = isset($_SESSION['id_utilisateur']) ? $_SESSION['id_utilisateur'] : 'non connecté';

echo "Bienvenue, " . htmlspecialchars($_SESSION['utilisateur']['nom']) . "!";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Startups</title>
    <link rel="stylesheet" href="incubator.css" />
    <link rel="stylesheet" href="startup.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="website icon" type="PNG" href="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png">
</head>
<body>
    <header>
    </header>
    <!-- Startup Page Section -->
    <section class="startups">
        <div class="container">
          <br><br>
            <h1>OUR STARTUPS</h1>
            <br><br>
            <h3>Here are some of the exciting startups we're working with. Get inspired and join us!</h3>
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
            <br><br>
            <div class="startup-cards">
                <?php
                require 'C:/xampp/htdocs/4Validation/config.php';
                
                require 'C:/xampp/htdocs/4Validation/Model/startup.php';
                require 'C:/xampp/htdocs/4Validation/Model/incubator.php';
                require 'C:/xampp/htdocs/4Validation/Controller/startupC.php';
                require 'C:/xampp/htdocs/4Validation/Controller/incubatorC.php';
                $startupC = new startupC();
                $nitroC = new nitroC();
                $startups = $startupC->liststartup(); 
                foreach ($startups as $startup) {
                  echo "<div class='startup-card'>";
                  echo "<img src='" . htmlspecialchars($startup['img_startup']) . "' alt='" . htmlspecialchars($startup['nom_startup']) . "' />";
                  echo "<h3>" . htmlspecialchars($startup['nom_startup']) . "</h3>";
                  echo "<h5><strong>Id:</strong> " . htmlspecialchars($startup['utilisateur_id']) . "</h5>";
                  echo "<h5><strong>Purpose:</strong> " . htmlspecialchars($startup['but_startup']) . "</h5>";
                  echo "<h5><strong>Description:</strong> " . htmlspecialchars($startup['desc_startup']) . "</h5>";
                  echo "<h5><strong>Launch Date:</strong> " . htmlspecialchars($startup['date_startup']) . "</h5>";
              
                  $nitro = $nitroC->listnitrobyid($startup['nitro']);
                  if ($nitro && isset($nitro['nitro_name'])) {
                      echo "<h5><strong>Nitro:</strong> " . htmlspecialchars($nitro['nitro_name']) . "</h5>";
                  } else {
                      echo "<h5><strong>Nitro:</strong> No Nitro</h5>";
                  }
                  
                  echo "<button class='invest-btn '><i class='fas fa-money-bill-wave'></i> Invest</button>";
                  echo "<form method='POST' action='startup.php' style='display:inline;'>
                          <input type='hidden' name='delete_id' value='" . htmlspecialchars($startup['startup_id_id']) . "' />
                          
                        </form>";
                  echo "<a href='incubator.php?id=" . $startup['startup_id_id'] . "' 
                  style='background-color: blue; color: white; border: none; padding: 10px 15px; cursor: pointer; text-decoration: none; display: inline-block;'>
                 <i class='fas fa-bolt'></i> Boost
               </a>";
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
    <script>
    const userId = <?php echo json_encode($utilisateur); ?>;
    document.querySelector("#startupFormContainer .form-container").insertAdjacentHTML('afterbegin', `<p><strong>User ID:</strong> ${userId}</p>`);
</script>

    <center>
    <div class="startup-form-container" id="startupFormContainer" style="display:none; max-height: 80vh; overflow-y: auto; padding: 20px; margin-bottom: 20px; border: 1px solid #ccc; background-color: #f9f9f9;">
    <div class="form-container" style="max-width: 500px; width: 100%;">
        <p><strong>User ID:</strong> <?php echo htmlspecialchars($utilisateur); ?></p> <!-- L'ID s'affiche ici -->
        <h2 id="formTitle">Start Your Startup</h2>
        <form id="startupForm" method="POST" action="startup.php" enctype="multipart/form-data">
            <input type="hidden" id="startup_id_id" name="startup_id_id" />
            <input type="hidden" name="utilisteur_id" value="<?php echo htmlspecialchars($utilisateur); ?>" />
            <h1>Welcome</h1>
            <label for="nom_startup">Startup Name</label>
            <input type="text" id="nom_startup" name="nom_startup" />

            <label for="but_startup">Purpose</label>
            <input type="text" id="but_startup" name="but_startup" />

            <label for="desc_startup">Description</label>
            <textarea id="desc_startup" name="desc_startup" ></textarea>

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
        include_once '../../../controller/startupC.php';
        include_once '../../../model/startup.php';

        $startup = new startup_id ; 
       
        if (isset($_POST['delete_id'])) {
            $delete_id = $_POST['delete_id'];
            $startupC->deletestartup($delete_id);
            header("Location: startup.php");
            exit();
        }

       
        if (isset($_POST['nom_startup'])) {
            $startup_id_id = $_POST['startup_id_id'] ?? null;
            $nom_startup = $_POST['nom_startup'];
            $but_startup = $_POST['but_startup'];
            $desc_startup = $_POST['desc_startup'];
            $date_startup = $_POST['date_startup'];
            $img_startup = null;
            $utilisteur_id = $_POST['utilisteur_id '];


            
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

           
            $startup = new startup_id($startup_id_id, $nom_startup, $but_startup, $desc_startup, $date_startup, $img_startup,$nitro=null, $utilisteur_id );

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
            document.getElementById("but_startup").value = startup.but_startup;
            document.getElementById("desc_startup").value = startup.desc_startup;
            document.getElementById("date_startup").value = startup.date_startup;
            document.getElementById("utilisateur_id").value = startup.utilisateur_id;
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("startupForm"); 
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let isValid = true;
        let messages = [];

        const startupName = document.getElementById("nom_startup").value.trim();
        const purpose = document.getElementById("but_startup").value.trim();
        const description = document.getElementById("desc_startup").value.trim();
        const utilisateur_id = document.getElementById("utilisateur_id").value.trim();

        // Check if Startup Name starts with uppercase
        if (!/^[A-Z]/.test(startupName)) {
            isValid = false;
            messages.push("Startup name must start with an uppercase letter.");
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
        puzzlePiece.style.left = `${Math.random() * 200}px`;
        puzzlePiece.style.top = `${Math.random() * 100}px`;
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
            
            puzzlePiece.style.left = `${x}px`;
            puzzlePiece.style.top = `${y}px`;
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

