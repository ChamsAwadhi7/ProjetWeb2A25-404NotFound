<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Database connection
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../model/startup.php';
require_once __DIR__ . '/../../model/incubator.php';
require_once __DIR__ . '/../../controller/startupC.php';
require_once __DIR__ . '/../../controller/incubatorC.php';

// Initialize controller
$startupC = new startupC();

// Get all startups
$startups = $startupC->liststartup();
// Search functionality
if (isset($_GET['search'])) {
  $searchTerm = htmlspecialchars($_GET['search']);
  $startups = array_filter($startups, function($startup) use ($searchTerm) {
      return stripos($startup['nom_startup'], $searchTerm) !== false || 
             stripos($startup['nom_hoster'], $searchTerm) !== false ||
             stripos($startup['prenom_hoster'], $searchTerm) !== false;
  });
}

// Sorting functionality
if (isset($_GET['sort'])) {
  usort($startups, function($a, $b) {
      $order = $_GET['sort'] === 'asc' ? 1 : -1;
      return $order * strcmp($a['nom_startup'], $b['nom_startup']);
  });
}
// Process form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Startup submission
        if (isset($_POST['submit_startup'])) {
            $startup = new startup_id(
                $_POST['startup_id_id'] ?? null,
                htmlspecialchars($_POST['nom_startup']),
                htmlspecialchars($_POST['nom_hoster']),
                htmlspecialchars($_POST['prenom_hoster']),
                htmlspecialchars($_POST['but_startup']),
                htmlspecialchars($_POST['desc_startup']),
                htmlspecialchars($_POST['date_startup']),
                $_FILES['img_startup']['name'] ? '/View/BackOffice/uploads/' . basename($_FILES['img_startup']['name']) : null
            );
            
            // Handle file upload
            if (isset($_FILES['img_startup'])) {
                $uploads_dir = '/View/BackOffice/uploads/';
                if (!is_dir($uploads_dir)) {
                    mkdir($uploads_dir, 0777, true);
                }
                
                if ($_FILES['img_startup']['error'] == 0) {
                    $tmp_name = $_FILES['img_startup']['tmp_name'];
                    $name = basename($_FILES['img_startup']['name']);
                    move_uploaded_file($tmp_name, "$uploads_dir/$name");
                }
            }
            
            if (!empty($_POST['startup_id_id'])) {
                $startupC->updatestartup($startup, $_POST['startup_id_id']);
                $_SESSION['message'] = "Startup updated successfully!";
            } else {
                $startupC->addstartup($startup);
                $_SESSION['message'] = "Startup added successfully!";
            }
        }
        
        // Delete action
        elseif (isset($_POST['delete_startup'])) {
            $startupC->deletestartup($_POST['startup_id_id']);
            $_SESSION['message'] = "Startup deleted successfully!";
        }
        
        header("Location: startup.php");
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: startup.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NextStep - Startups</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
  <link rel="stylesheet" href="incubator.css">
  
  <style>
    .items-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }
    .item-card {
      background: var(--clr-white);
      border-radius: var(--border-radius-2);
      padding: 20px;
      box-shadow: var(--box-shadow);
    }
    .item-card img {
      max-width: 100%;
      height: auto;
      border-radius: var(--border-radius-1);
      margin-bottom: 15px;
    }
    .item-actions {
      display: flex;
      gap: 10px;
      margin-top: 15px;
    }
    .btn {
      padding: 8px 12px;
      border-radius: var(--border-radius-1);
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .btn-edit {
      background: var(--clr-primary);
      color: white;
    }
    .btn-delete {
      background: var(--clr-danger);
      color: white;
    }
    .btn-add {
      background: var(--clr-success);
      color: white;
      margin-bottom: 20px;
    }
    .btn-invest {
      background: var(--clr-warning);
      color: white;
    }
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }
    .alert.success {
      background-color: #dff0d8;
      color: #3c763d;
    }
    .alert.error {
      background-color: #f2dede;
      color: #a94442;
    }
    .form {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      margin-top: 20px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
    }
    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    .form-group textarea {
      min-height: 100px;
    }
  </style>
</head>
<body>
   <?php if (isset($_SESSION['message'])): ?>
     <div class="alert success"><?= $_SESSION['message'] ?></div>
     <?php unset($_SESSION['message']); ?>
   <?php endif; ?>
   
   <?php if (isset($_SESSION['error'])): ?>
     <div class="alert error"><?= $_SESSION['error'] ?></div>
     <?php unset($_SESSION['error']); ?>
   <?php endif; ?>
   
   <div class="container">
      <aside>
         <div class="top">
           <div class="logo">
             <h2>C <span class="danger">NextStep</span> </h2>
           </div>
           <div class="close" id="close_btn">
            <span class="material-symbols-sharp">close</span>
           </div>
         </div>
         

         <div class="sidebar">
            <a href="#">
              <span class="material-symbols-sharp">grid_view</span>
              <h3>Dashboard</h3>
           </a>
           <a href="#" class="active">
              <span class="material-symbols-sharp">rocket_launch</span>
              <h3>Startups</h3>
           </a>
           <a href="incubator.php">
            <span class="material-symbols-sharp">business</span>
            <h3>Incubators</h3>
           </a>
           <a href="#">
              <span class="material-symbols-sharp">settings</span>
              <h3>Settings</h3>
           </a>
           <a href="#">
              <span class="material-symbols-sharp">logout</span>
              <h3>Logout</h3>
           </a>
         </div>
      </aside>

      <main id="main-content">
        <div class="incubator-content">
          <h1>Startups Management</h1>
          <div class="controls" style="margin-bottom: 20px; display: flex; gap: 15px; align-items: center;">
    <!-- Search Box -->
    <div class="search-box" style="flex-grow: 1;">
        <form method="GET" action="startup.php" style="display: flex; gap: 10px;">
            <input type="text" name="search" placeholder="Search startups..." 
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" 
                   style="padding: 8px; border-radius: 4px; border: 1px solid #ddd; flex-grow: 1;">
            <button type="submit" class="btn" style="padding: 8px 15px;">
                <span class="material-symbols-sharp">search</span> Search
            </button>
            <?php if (isset($_GET['search'])): ?>
                <a href="startup.php" class="btn" style="padding: 8px 15px;">
                    <span class="material-symbols-sharp">clear</span> Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Sort Dropdown -->
    <div class="sort-dropdown">
        <form method="GET" action="startup.php" style="display: flex; gap: 10px;">
            <select name="sort" onchange="this.form.submit()" style="padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                <option value="">Sort by</option>
                <option value="asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'asc' ? 'selected' : '' ?>>A-Z</option>
                <option value="desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'desc' ? 'selected' : '' ?>>Z-A</option>
            </select>
            <?php if (isset($_GET['search'])): ?>
                <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
            <?php endif; ?>
        </form>
    </div>
</div>
<style>
  .controls {
    background: var(--clr-white);
    padding: 15px;
    border-radius: var(--border-radius-2);
    box-shadow: var(--box-shadow);
    margin-bottom: 20px;
}

.search-box input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: var(--border-radius-1);
}

.sort-dropdown select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: var(--border-radius-1);
    background: white;
    cursor: pointer;
}
</style>
          
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Optional: Debounce function for search input
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let timer;
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }
});
</script>
          <!-- Startups Section -->
          <section>
            <h2>Startups
              <button class="btn btn-add" onclick="showForm('startup-form')">
                <span class="material-symbols-sharp">add</span> Add Startup
              </button>
            </h2>
            <div class="items-container">
              <?php foreach ($startups as $startup): ?>
                <div class="item-card">
                  <?php if ($startup['img_startup']): ?>
                    <img src="../view/frontOffice/<?= $startup['img_startup']?>" alt="<?= htmlspecialchars($startup['img_startup']) ?>">
                  <?php endif; ?>
                  <h3><?= htmlspecialchars($startup['nom_startup']) ?></h3>
                  <p><strong>Hoster:</strong> <?= htmlspecialchars($startup['nom_hoster']) ?> <?= htmlspecialchars($startup['prenom_hoster']) ?></p>
                  <p><strong>Purpose:</strong> <?= htmlspecialchars($startup['but_startup']) ?></p>
                  <p><strong>Launch Date:</strong> <?= htmlspecialchars($startup['date_startup']) ?></p>
                  <div class="item-actions">
                    <button class="btn btn-edit" onclick="editStartup(<?= htmlspecialchars(json_encode($startup)) ?>);">
                      <span class="material-symbols-sharp">edit</span> Edit
                    </button>
                    <form method="post" style="display:inline;">
                      <input type="hidden" name="startup_id_id" value="<?= $startup['startup_id_id'] ?>">
                      <button type="submit" name="delete_startup" class="btn btn-delete">
                        <span class="material-symbols-sharp">delete</span> Delete
                      </button>
                    </form>
                    
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </section>
          
          <!-- Startup Form (initially hidden) -->
          <div id="forms-container" style="margin-top: 40px; display: none;">
            <form id="startup-form" class="form" method="post" enctype="multipart/form-data">
              <input type="hidden" name="startup_id_id" value="">
              <h3>Startup Details</h3>
              <div class="form-group">
                <label>Startup Name:</label>
                <input type="text" name="nom_startup" required>
              </div>
              <div class="form-group">
                <label>Hoster Name:</label>
                <input type="text" name="nom_hoster" required>
              </div>
              <div class="form-group">
                <label>Hoster Surname:</label>
                <input type="text" name="prenom_hoster" required>
              </div>
              <div class="form-group">
                <label>Purpose:</label>
                <input type="text" name="but_startup" required>
              </div>
              <div class="form-group">
                <label>Description:</label>
                <textarea name="desc_startup" required></textarea>
              </div>
              <div class="form-group">
                <label>Launch Date:</label>
                <input type="date" name="date_startup" required>
              </div>
              <div class="form-group">
                <label>Image:</label>
                <input type="file" name="img_startup">
              </div>
              <button type="submit" name="submit_startup" class="btn">Save</button>
              <button type="button" onclick="hideForms()" class="btn">Cancel</button>
            </form>
          </div>
        </div>
      </main>

      <div class="right">
        <div class="top">
          <button id="menu_bar">
            <span class="material-symbols-sharp">menu</span>
          </button>

          <div class="theme-toggler">
            <span class="material-symbols-sharp active">light_mode</span>
            <span class="material-symbols-sharp">dark_mode</span>
          </div>

          <div class="profile">
            <div class="info">
                <p><b>IBRI YOUSSEF SOULAIMEN</b></p>
                <p>UX/UI designer</p>
            </div>
            <div class="profile-photo">
              <img src="./images/profile-1.jpg" alt=""/>
            </div>
          </div>
        </div>
      </div>
   </div>

   <script>
     // Form handling functions
     function showForm(formId) {
       document.getElementById('forms-container').style.display = 'block';
       document.querySelectorAll('.form').forEach(f => f.style.display = 'none');
       document.getElementById(formId).style.display = 'block';
       document.getElementById(formId).reset();
     }
     
     function hideForms() {
       document.getElementById('forms-container').style.display = 'none';
       document.querySelectorAll('.form').forEach(f => f.reset());
     }
     
     // Edit function for startups
     function editStartup(startup) {
       showForm('startup-form');
       const form = document.getElementById('startup-form');
       form.startup_id_id.value = startup.startup_id_id;
       form.nom_startup.value = startup.nom_startup;
       form.nom_hoster.value = startup.nom_hoster;
       form.prenom_hoster.value = startup.prenom_hoster;
       form.but_startup.value = startup.but_startup;
       form.desc_startup.value = startup.desc_startup;
       form.date_startup.value = startup.date_startup;
     }

     // Menu toggle functionality
     document.getElementById('menu_bar').addEventListener('click', function() {
       document.querySelector('aside').classList.toggle('active');
     });

     // Theme toggler
     const themeToggler = document.querySelector('.theme-toggler');
     themeToggler.addEventListener('click', () => {
       document.body.classList.toggle('dark-theme');
       themeToggler.querySelector('span:nth-child(1)').classList.toggle('active');
       themeToggler.querySelector('span:nth-child(2)').classList.toggle('active');
     });
   </script>
   
   <script>
   // Form validation
   document.getElementById('startup-form').addEventListener('submit', function(e) {
       const startupName = this.nom_startup.value.trim();
       const hosterName = this.nom_hoster.value.trim();
       const hosterSurname = this.prenom_hoster.value.trim();
       const purpose = this.but_startup.value.trim();
       const description = this.desc_startup.value.trim();
       let isValid = true;
       
       // Check if Startup Name starts with uppercase
       if (!/^[A-Z]/.test(startupName)) {
           alert('Startup name must start with an uppercase letter.');
           isValid = false;
       }
       
       // Check if Hoster Name has no numbers
       if (/\d/.test(hosterName)) {
           alert('Hoster name must not contain numbers.');
           isValid = false;
       }
       
       // Check if Hoster Surname has no numbers
       if (/\d/.test(hosterSurname)) {
           alert('Hoster surname must not contain numbers.');
           isValid = false;
       }
       
       // Check if Purpose contains no letters (only numbers or symbols)
       if (/[a-zA-Z]/.test(purpose)) {
           alert('Purpose must not contain letters.');
           isValid = false;
       }
       
       // Check if Description starts with uppercase
       if (!/^[A-Z]/.test(description)) {
           alert('Description must start with an uppercase letter.');
           isValid = false;
       }
       
       if (!isValid) {
           e.preventDefault();
       }
   });
   </script>
</body>
</html>