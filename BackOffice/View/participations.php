<?php
require_once(__DIR__ . '/../Controller/ParticipationController.php');

// Instantiate the controller
$pdo = config::getConnexion();
$participationController = new ParticipationController($pdo);

// Submission Handling
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['edit'])) {
    $date_Part = $_POST['date_Part'];

    $participationController->addParticipation($date_Part);

    header("Location: participations.php");
    exit();
}

// Handle the edit form submission
if (isset($_POST['edit'])) {
    $id_Part = $_POST['id_Part'];
    $date_Part = $_POST['date_Part'];

    $participationController->updateParticipation($id_Part, $date_Part);  

    header("Location: participations.php");
    exit();
}

// Handle deletion of participation
if (isset($_GET['delete_id'])) {
    $id_Part = $_GET['delete_id'];

    $participationController->deleteParticipation($id_Part);

    header("Location: participations.php");
    exit();
}

// Fetch participations
$participations = $participationController->getAllParticipations();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UI/UX</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
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
           <a href="#" class="active" id="startups-btn">
              <span class="material-symbols-sharp">person_outline</span>
              <h3>Startups</h3>
           </a>
           <a href="#" id="incubators-btn">
            <span class="material-symbols-sharp">business</span>
            <h3>Incubators</h3>
           </a>
           <a href="#" id="participations-btn">
            <span class="material-symbols-sharp">business</span>
            <h3>Participations</h3>
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
        <div class="startup-content">
           <h1>Dashboard</h1>
           <div class="date">
             <input type="date">
           </div>
           <div class="insights">
             <div class="sales">
               <span class="material-symbols-sharp">trending_up</span>
               <div class="middle">
                 <div class="left">
                   <h3>Total Sales</h3>
                   <h1>$25,024</h1>
                 </div>
                  <div class="progress">
                      <svg>
                         <circle r="30" cy="40" cx="40"></circle>
                      </svg>
                      <div class="number"><p>80%</p></div>
                  </div>
               </div>
               <small>Last 24 Hours</small>
            </div>
        </div>
        </div>

        <center>
        <div class="incubator-content" style="display: none;">
          <h1>Incubators</h1>
          <br><br>
          <div class="actions">
            <button id="add-btn">
                <span class="material-symbols-sharp">add_circle</span> Add Incubator
            </button>
            <button id="modify-btn">
                <span class="material-symbols-sharp">edit</span> Modify Incubator
            </button>
            <button id="delete-btn">
                <span class="material-symbols-sharp">delete</span> Delete Incubator
            </button>
            
        </div>
        </center>
          
          <div id="add-modal" class="modal">
            <div class="modal-content">
               <span id="close-modal" class="close">&times;</span>
               <center>
                <h2>Select What to Add</h2>
              <button class="choice-btn" id="workshop-btn">Workshop</button>
              <button class="choice-btn" id="nitro-btn">Nitro</button>
              <button class="choice-btn" id="workspace-btn">WorkingSpace</button>
            </center>
            </div>
          </div>

          <div id="workshop-form" class="form" style="display: none;">
            <h3>Workshop Details</h3>
            <label for="workshop-name">Name:</label>
            <input type="text" id="workshop-name" placeholder="Workshop Name">
            <label for="workshop-date">Date:</label>
            <input type="date" id="workshop-date">
            <label for="workshop-place">Place:</label>
            <input type="text" id="workshop-place" placeholder="Workshop Place">
            <label for="workshop-subject">Subject:</label>
            <input type="text" id="workshop-subject" placeholder="Workshop Subject">
            <label for="workshop-hoster">Hoster:</label>
            <input type="text" id="workshop-hoster" placeholder="Workshop Hoster">
            <button id="submit-workshop">Submit</button>
          </div>

          <div id="nitro-form" class="form" style="display: none;">
            <h3>Nitro Details</h3>
            
            <label for="nitro-name">Name:</label>
            <select id="nitro-name">
              <option value="beginner" data-price="9.99" data-period="1 month">Beginner</option>
              <option value="silver" data-price="19.99" data-period="2 months">Silver</option>
              <option value="gold" data-price="49.99" data-period="6 months">Gold</option>
            </select>
          
            <label for="nitro-price">Price:</label>
            <input type="number" id="nitro-price" placeholder="Price" readonly>
          
            <label for="nitro-period">Period:</label>
            <input type="text" id="nitro-period" placeholder="Period" readonly>
          
            <button id="submit-nitro">Submit</button>
          </div>
          

          <div id="workspace-form" class="form" style="display: none;">
            <h3>WorkingSpace Details</h3>
            <label for="workspace-name">Name:</label>
            <input type="text" id="workspace-name" placeholder="WorkingSpace Name">
            <label for="workspace-location">Location:</label>
            <input type="text" id="workspace-location" placeholder="Location">
            <label for="workspace-surface">Surface:</label>
            <input type="number" id="workspace-surface" placeholder="Surface (in sq.m.)">
            <label for="workspace-price">Price:</label>
            <input type="number" id="workspace-price" placeholder="Price">
            <label for="workspace-capacity">Capacity:</label>
            <input type="number" id="workspace-capacity" placeholder="Capacity (people)">
            <button id="submit-workspace">Submit</button>
          </div>
        </center>

        <!-- Participation Section -->
        <div class="participation-content">
            <h1>Participation List</h1>

            <!-- Add New Participation Form -->
            <h2>Add New Participation</h2>
            <form action="participations.php" method="POST">
                <input type="date" name="date_Part" required><br>
                <button type="submit">Add Participation</button>
            </form>

            <hr>

            <!-- Table of Existing Participations -->
            <h2>Existing Participations</h2>
            <table>
                <tr>
                    <th>ID</th><th>Date</th><th>Actions</th>
                </tr>
                <?php foreach ($participations as $participation): ?>
                    <tr>
                        <td><?= htmlspecialchars($participation['id_Part']) ?></td>
                        <td><?= htmlspecialchars($participation['date_Part']) ?></td>
                        <td>
                            <a href="participations.php?edit_id=<?= $participation['id_Part'] ?>">Edit</a> |
                            <a href="participations.php?delete_id=<?= $participation['id_Part'] ?>" onclick="return confirm('Delete this participation?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <?php
            if (isset($_GET['edit_id'])):
                $id_Part = $_GET['edit_id'];
                $participation = $participationController->getParticipationById($id_Part);
            ?>
                <h2>Edit Participation</h2>
                <form action="participations.php" method="POST">
                    <input type="text" name="id_Part" value="<?= htmlspecialchars($participation['id_Part']) ?>" required><br>
                    <input type="date" name="date_Part" value="<?= htmlspecialchars($participation['date_Part']) ?>" required><br>
                    <button type="submit" name="edit">Update Participation</button>
                </form>
            <?php endif; ?>
        </div>
        </main>
    </div>

   <script src="script.js">
    const participationBtn = document.getElementById("participations-btn");
const participationContent = document.querySelector(".participation-content");

participationBtn.addEventListener("click", () => {
  incubatorContent.style.display = "none";
  startupContent.style.display = "none";
  formationContent.style.display = "none";
  participationContent.style.display = "block";
});

   </script>
   <style>
    .participation-content {
  background: #ffffff;
  padding: 2rem;
  margin: 2rem auto;
  border-radius: 16px;
  max-width: 900px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.participation-content h1, .participation-content h2 {
  color: #2d3436;
  text-align: center;
  margin-bottom: 1.5rem;
}

.participation-content form {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
  margin-bottom: 2rem;
}

.participation-content input[type="date"],
.participation-content input[type="text"] {
  padding: 0.7rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  min-width: 220px;
}

.participation-content button {
  padding: 0.7rem 1.5rem;
  background-color: #0984e3;
  border: none;
  border-radius: 8px;
  color: white;
  font-weight: bold;
  font-size: 1rem;
  cursor: pointer;
  transition: 0.3s ease;
}

.participation-content button:hover {
  background-color: #74b9ff;
}

.participation-content table {
  width: 100%;
  border-collapse: collapse;
  text-align: center;
  margin-top: 1rem;
}

.participation-content th,
.participation-content td {
  padding: 0.9rem;
  border: 1px solid #dfe6e9;
}

.participation-content th {
  background-color: #dfe6e9;
  color: #2d3436;
}

.participation-content tr:nth-child(even) {
  background-color: #f5f6fa;
}

.participation-content tr:hover {
  background-color: #e0f7fa;
}

.participation-content a {
  color: #0984e3;
  text-decoration: none;
  font-weight: 500;
}

.participation-content a:hover {
  text-decoration: underline;
}
   </style>
</body>
</html>
