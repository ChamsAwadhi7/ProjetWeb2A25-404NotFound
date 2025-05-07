<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Database connection
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../Model/startup.php';
require_once __DIR__ . '/../../../Model/incubator.php';
require_once __DIR__ . '/../../../Controller/startupC.php';
require_once __DIR__ . '/../../../Controller/incubatorC.php';
// Initialize controllers
$nitroC = new nitroC();
$workshopC = new workshopC();
$workingspaceC = new workingspaceC();

// Get all items
$nitros = $nitroC->listnitro();
$workshops = $workshopC->listworkshop();
$workspaces = $workingspaceC->listworkingspace();

// Process form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Workshop submission
        if (isset($_POST['submit_workshop'])) {
            $workshop = new workshop_id(
                $_POST['id_workshop'] ?? null,
                htmlspecialchars($_POST['nom_workshop']),
                htmlspecialchars($_POST['date_workshop']),
                htmlspecialchars($_POST['lieu_workshop']),
                htmlspecialchars($_POST['sujet_workshop']),
                htmlspecialchars($_POST['responsable'])
            );
            
            if (!empty($_POST['id_workshop'])) {
                $workshopC->updateworkshop($workshop, $_POST['id_workshop']);
                $_SESSION['message'] = "Workshop updated successfully!";
            } else {
                $workshopC->addworkshop($workshop);
                $_SESSION['message'] = "Workshop added successfully!";
            }
        }
        
        // Nitro submission
        elseif (isset($_POST['submit_nitro'])) {
            $nitro = new nitro_id(
                $_POST['id_nitro'] ?? null,
                htmlspecialchars($_POST['nitro_name']),
                floatval($_POST['nitro_price']),
                htmlspecialchars($_POST['nitro_period'])
            );
            
            if (!empty($_POST['id_nitro'])) {
                $nitroC->updatenitro($nitro, $_POST['id_nitro']);
                $_SESSION['message'] = "Nitro plan updated successfully!";
            } else {
                $nitroC->addnitro($nitro);
                $_SESSION['message'] = "Nitro plan added successfully!";
            }
        }
        
        // WorkingSpace submission
        elseif (isset($_POST['submit_workspace'])) {
            $workingspace = new workingspace_id(
                $_POST['id_workingspace'] ?? null,
                htmlspecialchars($_POST['nom_workingspace']),
                floatval($_POST['surface']),
                floatval($_POST['prix_workingspace']),
                intval($_POST['capacite_workingspace']),
                htmlspecialchars($_POST['localisation'])
            );
            
            if (!empty($_POST['id_workingspace'])) {
                $workingspaceC->updateworkingspace($workingspace, $_POST['id_workingspace']);
                $_SESSION['message'] = "Workspace updated successfully!";
            } else {
                $workingspaceC->addworkingspace($workingspace);
                $_SESSION['message'] = "Workspace added successfully!";
            }
        }
        
        // Delete actions
        elseif (isset($_POST['delete_nitro'])) {
            $nitroC->deletenitro($_POST['id_nitro']);
            $_SESSION['message'] = "Nitro plan deleted successfully!";
        }
        elseif (isset($_POST['delete_workshop'])) {
            $workshopC->deleteworkshop($_POST['id_workshop']);
            $_SESSION['message'] = "Workshop deleted successfully!";
        }
        elseif (isset($_POST['delete_workspace'])) {
            $workingspaceC->deleteworkingspace($_POST['id_workingspace']);
            $_SESSION['message'] = "Workspace deleted successfully!";
        }
        
        header("Location: incubator.php");
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: incubator.php");
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
  <title>NextStep</title>
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
    .form-group input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    /* New styles for search, sort and stats */
    .search-sort-container {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      align-items: center;
    }
    .search-box {
      padding: 8px 15px;
      border-radius: 20px;
      border: 1px solid #ddd;
      width: 250px;
    }
    .sort-dropdown {
      padding: 8px;
      border-radius: 4px;
      border: 1px solid #ddd;
    }
    .stats-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }
    .stat-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      text-align: center;
    }
    .stat-value {
      font-size: 2rem;
      font-weight: bold;
      color: var(--clr-primary);
      margin: 10px 0;
    }
    .stat-label {
      color: var(--clr-dark-light);
    }
    .no-results {
      text-align: center;
      padding: 20px;
      color: var(--clr-dark-light);
      grid-column: 1 / -1;
    }
    .form-group {
      position: relative;
      margin-bottom: 20px;
    }
    .error {
      color: red;
      font-size: 0.8em;
      margin-top: 5px;
    }
  </style>
</head>
<body>
   <?php if (isset($_SESSION['message'])): ?>
     <div class="alert success"><?= htmlspecialchars($_SESSION['message']) ?></div>
     <?php unset($_SESSION['message']); ?>
   <?php endif; ?>
   
   <?php if (isset($_SESSION['error'])): ?>
     <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
     <?php unset($_SESSION['error']); ?>
   <?php endif; ?>
   
   <div class="container">
   <aside>
           
           <div class="top">
             <div class="logo">
               <h2>C <span class="danger">NextStep</span> </h2>
             </div>
             <div class="close" id="close_btn">
              <span class="material-symbols-sharp">
                close
                </span>
             </div>
           </div>
           <!-- end top -->
            <div class="sidebar">
              <a href="index.html" class="active">
                <span class="material-symbols-sharp">grid_view </span>
                <h3>Dashbord</h3>
             </a>
             <a href="#">
                <span class="material-symbols-sharp">person_outline </span>
                <h3>custumers</h3>
             </a>
             <a href="Event.html" class="close">
                <span class="material-symbols-sharp">receipt_long </span>
                <h3>Events</h3>
             </a>
             <a href="COURS/cours.php" class="close">
              <span class="material-symbols-sharp">receipt_long </span>
              <h3>Cours</h3>
             </a>
             <a href="STARTUP/BackOffice/startup.php" class="close">
              <span class="material-symbols-sharp">business </span>
              <h3>startups</h3>
             </a>
             <a href="STARTUP/BackOffice/incubator.php" id="incubators-btn">
              <span class="material-symbols-sharp">rocket_launch</span>
              <h3>Incubators</h3>
             </a>
             <a href="logout.php">
                <span class="material-symbols-sharp">logout </span>
                <h3>logout</h3>
             </a>
              <a href="#">
                  <span class="material-symbols-sharp">settings</span>
                  <h3>Settings</h3>
              </a>
            </div>
        </aside>

      <main id="main-content">
        <div class="incubator-content">
          <h1>Incubators Management</h1>
          
          <!-- Statistics Section -->
          <section class="stats-container">
            <div class="stat-card">
              <div class="stat-value"><?= count($nitros) ?></div>
              <div class="stat-label">Nitro Plans</div>
            </div>
            <div class="stat-card">
              <div class="stat-value"><?= count($workshops) ?></div>
              <div class="stat-label">Workshops</div>
            </div>
            <div class="stat-card">
              <div class="stat-value"><?= count($workspaces) ?></div>
              <div class="stat-label">Working Spaces</div>
            </div>
          </section>
          
          <!-- Nitro Plans Section -->
          <section>
            <div class="search-sort-container">
              <h2>Nitro Plans</h2>
              <div>
                <input type="text" class="search-box" placeholder="Search nitro plans..." data-search="nitro">
                <select class="sort-dropdown" data-sort="nitro">
                  <option value="name_asc">Name (A-Z)</option>
                  <option value="name_desc">Name (Z-A)</option>
                  <option value="price_asc">Price (Low-High)</option>
                  <option value="price_desc">Price (High-Low)</option>
                </select>
              </div>
            </div>
            <button class="btn btn-add" onclick="showForm('nitro-form')">
              <span class="material-symbols-sharp">add</span> Add Nitro Plan
            </button>
            
            <div class="items-container" id="nitro-container">
              <?php foreach ($nitros as $nitro): ?>
                <div class="item-card" data-name="<?= htmlspecialchars(strtolower($nitro['nitro_name'])) ?>" data-price="<?= $nitro['nitro_price'] ?>">
                  <h3><?= htmlspecialchars($nitro['nitro_name']) ?></h3>
                  <p>Price: $<?= number_format($nitro['nitro_price'], 2) ?></p>
                  <p>Period: <?= htmlspecialchars($nitro['nitro_period']) ?></p>
                  <div class="item-actions">
                  <button class="btn btn-edit"
  onclick="editNitro(
    <?= $nitro['id_nitro'] ?>,
    '<?= htmlspecialchars(addslashes($nitro['nitro_name'])) ?>',
    <?= $nitro['nitro_price'] ?>,
    '<?= htmlspecialchars(addslashes($nitro['nitro_period'])) ?>'
  )">
  <span class="material-symbols-sharp">edit</span> Edit
</button>
                    <form method="post" style="display:inline;">
                      <input type="hidden" name="id_nitro" value="<?= $nitro['id_nitro'] ?>">
                      <button type="submit" name="delete_nitro" class="btn btn-delete">
                        <span class="material-symbols-sharp">delete</span> Delete
                      </button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="no-results" style="display: none;">No nitro plans found matching your search.</div>
            </div>
          </section>
          
          <!-- Workshops Section -->
          <section style="margin-top: 40px;">
            <div class="search-sort-container">
              <h2>Workshops</h2>
              <div>
                <input type="text" class="search-box" placeholder="Search workshops..." data-search="workshop">
                <select class="sort-dropdown" data-sort="workshop">
                  <option value="name_asc">Name (A-Z)</option>
                  <option value="name_desc">Name (Z-A)</option>
                  <option value="date_asc">Date (Oldest)</option>
                  <option value="date_desc">Date (Newest)</option>
                </select>
              </div>
            </div>
            <button class="btn btn-add" onclick="showForm('workshop-form')">
              <span class="material-symbols-sharp">add</span> Add Workshop
            </button>
            
            <div class="items-container" id="workshop-container">
              <?php foreach ($workshops as $workshop): ?>
                <div class="item-card" data-name="<?= htmlspecialchars(strtolower($workshop['nom_workshop'])) ?>" data-date="<?= strtotime($workshop['date_workshop']) ?>">
                  <h3><?= htmlspecialchars($workshop['nom_workshop']) ?></h3>
                  <p>Date: <?= date('M d, Y', strtotime($workshop['date_workshop'])) ?></p>
                  <p>Location: <?= htmlspecialchars($workshop['lieu_workshop']) ?></p>
                  <div class="item-actions">
                  <button class="btn btn-edit"
  onclick="editWorkshop(
    <?= $workshop['id_workshop'] ?>,
    '<?= htmlspecialchars(addslashes($workshop['nom_workshop'])) ?>',
    '<?= $workshop['date_workshop'] ?>',
    '<?= htmlspecialchars(addslashes($workshop['lieu_workshop'])) ?>',
    '<?= htmlspecialchars(addslashes($workshop['sujet_workshop'])) ?>',
    '<?= htmlspecialchars(addslashes($workshop['responsable'])) ?>'
  )">
  <span class="material-symbols-sharp">edit</span> Edit
</button>
                    <form method="post" style="display:inline;">
                      <input type="hidden" name="id_workshop" value="<?= $workshop['id_workshop'] ?>">
                      <button type="submit" name="delete_workshop" class="btn btn-delete">
                        <span class="material-symbols-sharp">delete</span> Delete
                      </button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="no-results" style="display: none;">No workshops found matching your search.</div>
            </div>
          </section>
          
          <!-- Working Spaces Section -->
          <section style="margin-top: 40px;">
            <div class="search-sort-container">
              <h2>Working Spaces</h2>
              <div>
                <input type="text" class="search-box" placeholder="Search workspaces..." data-search="workspace">
                <select class="sort-dropdown" data-sort="workspace">
                  <option value="name_asc">Name (A-Z)</option>
                  <option value="name_desc">Name (Z-A)</option>
                  <option value="price_asc">Price (Low-High)</option>
                  <option value="price_desc">Price (High-Low)</option>
                  <option value="capacity_asc">Capacity (Small-Large)</option>
                  <option value="capacity_desc">Capacity (Large-Small)</option>
                </select>
              </div>
            </div>
            <button class="btn btn-add" onclick="showForm('workspace-form')">
              <span class="material-symbols-sharp">add</span> Add Space
            </button>
            
            <div class="items-container" id="workspace-container">
              <?php foreach ($workspaces as $workspace): ?>
                <div class="item-card" data-name="<?= htmlspecialchars(strtolower($workspace['nom_workingspace'])) ?>" 
                     data-price="<?= $workspace['prix_workingspace'] ?>" 
                     data-capacity="<?= $workspace['capacite_workingspace'] ?>">
                  <h3><?= htmlspecialchars($workspace['nom_workingspace']) ?></h3>
                  <p>Location: <?= htmlspecialchars($workspace['localisation']) ?></p>
                  <p>Capacity: <?= $workspace['capacite_workingspace'] ?> people</p>
                  <div class="item-actions">
                  <button class="btn btn-edit"
  onclick="editWorkspace(
    <?= $workspace['id_workingspace'] ?>,
    '<?= htmlspecialchars(addslashes($workspace['nom_workingspace'])) ?>',
    <?= $workspace['surface'] ?>,
    <?= $workspace['prix_workingspace'] ?>,
    <?= $workspace['capacite_workingspace'] ?>,
    '<?= htmlspecialchars(addslashes($workspace['localisation'])) ?>'
  )">
  <span class="material-symbols-sharp">edit</span> Edit
</button>

                    <form method="post" style="display:inline;">
                      <input type="hidden" name="id_workingspace" value="<?= $workspace['id_workingspace'] ?>">
                      <button type="submit" name="delete_workspace" class="btn btn-delete">
                        <span class="material-symbols-sharp">delete</span> Delete
                      </button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="no-results" style="display: none;">No workspaces found matching your search.</div>
            </div>
          </section>
          
          <!-- Forms (initially hidden) -->
          <div id="forms-container" style="margin-top: 40px; display: none;">
            <!-- Workshop Form -->
            <form id="workshop-form" class="form" method="post">
              <input type="hidden" name="id_workshop" value="">
              <h3>Workshop Details</h3>
              <div class="form-group">
                <label>Name:</label>
                <input type="text" name="nom_workshop" required>
              </div>
              <div class="form-group">
                <label>Date:</label>
                <input type="date" name="date_workshop" required>
              </div>
              <div class="form-group">
                <label>Location:</label>
                <input type="text" name="lieu_workshop" required>
              </div>
              <div class="form-group">
                <label>Subject:</label>
                <input type="text" name="sujet_workshop" required>
              </div>
              <div class="form-group">
                <label>Responsible:</label>
                <input type="text" name="responsable" required>
              </div>
              <button type="submit" name="submit_workshop" class="btn">Save</button>
              <button type="button" onclick="hideForms()" class="btn">Cancel</button>
            </form>
            
            <!-- Nitro Form -->
            <form id="nitro-form" class="form" method="post">
              <input type="hidden" name="id_nitro" value="">
              <h3>Nitro Details</h3>
              <div class="form-group">
                <label>Plan Name:</label>
                <input type="text" name="nitro_name" required>
              </div>
              <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" name="nitro_price" required>
              </div>
              <div class="form-group">
                <label>Period:</label>
                <input type="text" name="nitro_period" required>
              </div>
              <button type="submit" name="submit_nitro" class="btn">Save</button>
              <button type="button" onclick="hideForms()" class="btn">Cancel</button>
            </form>
            
            <!-- Workspace Form -->
            <form id="workspace-form" class="form" method="post">
              <input type="hidden" name="id_workingspace" value="">
              <h3>Workspace Details</h3>
              <div class="form-group">
                <label>Name:</label>
                <input type="text" name="nom_workingspace" required>
              </div>
              <div class="form-group">
                <label>Surface Area (m²):</label>
                <input type="number" step="0.01" name="surface" required>
              </div>
              <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" name="prix_workingspace" required>
              </div>
              <div class="form-group">
                <label>Capacity:</label>
                <input type="number" name="capacite_workingspace" required>
              </div>
              <div class="form-group">
                <label>Location:</label>
                <input type="text" name="localisation" required>
              </div>
              <button type="submit" name="submit_workspace" class="btn">Save</button>
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
                <p><b>NextStep</b></p>
                <p>Admin</p>
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
     }
     
     function hideForms() {
       document.getElementById('forms-container').style.display = 'none';
       document.querySelectorAll('.form').forEach(f => f.reset());
     }
     
     // Edit functions would fetch data and populate forms
     function editNitro(id, name, price, period) {
    showForm('nitro-form');
    const form = document.getElementById('nitro-form');
    form.querySelector('[name="id_nitro"]').value = id;
    form.querySelector('[name="nitro_name"]').value = name;
    form.querySelector('[name="nitro_price"]').value = price;
    form.querySelector('[name="nitro_period"]').value = period;
  }
     
  function editWorkshop(id, name, date, location, subject, responsible) {
    showForm('workshop-form');
    const form = document.getElementById('workshop-form');
    form.querySelector('[name="id_workshop"]').value = id;
    form.querySelector('[name="nom_workshop"]').value = name;
    form.querySelector('[name="date_workshop"]').value = date;
    form.querySelector('[name="lieu_workshop"]').value = location;
    form.querySelector('[name="sujet_workshop"]').value = subject;
    form.querySelector('[name="responsable"]').value = responsible;
  }
     
  function editWorkspace(id, name, surface, price, capacity, location) {
    showForm('workspace-form');
    const form = document.getElementById('workspace-form');
    form.querySelector('[name="id_workingspace"]').value = id;
    form.querySelector('[name="nom_workingspace"]').value = name;
    form.querySelector('[name="surface"]').value = surface;
    form.querySelector('[name="prix_workingspace"]').value = price;
    form.querySelector('[name="capacite_workingspace"]').value = capacity;
    form.querySelector('[name="localisation"]').value = location;
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

     // Search functionality
     document.querySelectorAll('.search-box').forEach(searchBox => {
       searchBox.addEventListener('input', function() {
         const searchTerm = this.value.toLowerCase();
         const containerId = this.getAttribute('data-search') + '-container';
         const items = document.querySelectorAll(`#${containerId} .item-card`);
         let hasResults = false;
         
         items.forEach(item => {
           const name = item.getAttribute('data-name');
           if (name.includes(searchTerm)) {
             item.style.display = 'block';
             hasResults = true;
           } else {
             item.style.display = 'none';
           }
         });
         
         // Show/hide no results message
         const noResults = document.querySelector(`#${containerId} .no-results`);
         noResults.style.display = hasResults ? 'none' : 'block';
       });
     });

     // Sort functionality
     document.querySelectorAll('.sort-dropdown').forEach(dropdown => {
       dropdown.addEventListener('change', function() {
         const sortValue = this.value;
         const containerId = this.getAttribute('data-sort') + '-container';
         const container = document.getElementById(containerId);
         const items = Array.from(container.querySelectorAll('.item-card'));
         
         items.sort((a, b) => {
           if (sortValue === 'name_asc') {
             return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
           } else if (sortValue === 'name_desc') {
             return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
           } else if (sortValue === 'price_asc') {
             return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
           } else if (sortValue === 'price_desc') {
             return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
           } else if (sortValue === 'date_asc') {
             return parseInt(a.getAttribute('data-date')) - parseInt(b.getAttribute('data-date'));
           } else if (sortValue === 'date_desc') {
             return parseInt(b.getAttribute('data-date')) - parseInt(a.getAttribute('data-date'));
           } else if (sortValue === 'capacity_asc') {
             return parseInt(a.getAttribute('data-capacity')) - parseInt(b.getAttribute('data-capacity'));
           } else if (sortValue === 'capacity_desc') {
             return parseInt(b.getAttribute('data-capacity')) - parseInt(a.getAttribute('data-capacity'));
           }
           return 0;
         });
         
         // Re-append sorted items
         items.forEach(item => container.appendChild(item));
       });
     });

     // Form validation
     document.getElementById('nitro-form').addEventListener('submit', function(e) {
         const name = this.nitro_name.value.trim();
         const price = parseFloat(this.nitro_price.value.trim());

         if (!/^[A-Z]/.test(name)) {
             alert('Nitro Plan Name must start with an uppercase letter.');
             e.preventDefault();
             return;
         }
         if (isNaN(price) || price <= 2) {
             alert('Nitro Price must be a number greater than 2.');
             e.preventDefault();
             return;
         }
     });

     document.getElementById('workshop-form').addEventListener('submit', function(e) {
         const name = this.nom_workshop.value.trim();
         const location = this.lieu_workshop.value.trim();
         const subject = this.sujet_workshop.value.trim();
         const responsible = this.responsable.value.trim();

         if (!/^[A-Z]/.test(name) || !/^[A-Za-z\s]+$/.test(name)) {
             alert('Workshop Name must start with an uppercase letter and only contain letters.');
             e.preventDefault();
             return;
         }
         if (!/,/.test(location)) {
             alert('Workshop Location must contain a comma.');
             e.preventDefault();
             return;
         }
         if (!/^[A-Z]/.test(subject) || !/^[A-Za-z\s]+$/.test(subject)) {
             alert('Workshop Subject must start with an uppercase letter and only contain letters.');
             e.preventDefault();
             return;
         }
         if (!/^[A-Z]/.test(responsible) || !/^[A-Za-z\s]+$/.test(responsible)) {
             alert('Responsible must start with an uppercase letter and only contain letters.');
             e.preventDefault();
             return;
         }
     });

     document.getElementById('workspace-form').addEventListener('submit', function(e) {
         const name = this.nom_workingspace.value.trim();
         const surface = this.surface.value.trim();
         const price = this.prix_workingspace.value.trim();
         const capacity = this.capacite_workingspace.value.trim();
         const location = this.localisation.value.trim();

         if (!/^[A-Z]/.test(name)) {
             alert('Workspace Name must start with an uppercase letter.');
             e.preventDefault();
             return;
         }
         if (!/^\d+(\.\d+)?$/.test(surface)) {
             alert('Surface must be a number.');
             e.preventDefault();
             return;
         }
         if (!/^\d+(\.\d+)?$/.test(price)) {
             alert('Price must be a number.');
             e.preventDefault();
             return;
         }
         if (!/^\d+$/.test(capacity)) {
             alert('Capacity must be a number.');
             e.preventDefault();
             return;
         }
         if (!/^[A-Z]/.test(location) || !/,/.test(location)) {
             alert('Location must start with an uppercase letter and contain a comma.');
             e.preventDefault();
             return;
         }
     });
   </script>
</body>
</html>