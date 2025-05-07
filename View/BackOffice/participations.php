<?php
require_once(__DIR__ . '/../../Controller/ParticipationController.php');


$pdo = config::getConnexion();
$participationController = new ParticipationController($pdo);

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
  <title>UI/UX</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <aside>
    <div class="top">
      <div class="logo">
        <h2>C <span class="danger">NextStep</span></h2>
      </div>
      <div class="close" id="close_btn">
        <span class="material-symbols-sharp">close</span>
      </div>
    </div>
    <div class="sidebar">
      <a href="#" id="dashboard-btn">
        <span class="material-symbols-sharp">grid_view</span>
        <h3>Dashboard</h3>
      </a>
      <a href="#" id="startups-btn">
        <span class="material-symbols-sharp">person_outline</span>
        <h3>Startups</h3>
      </a>
      <a href="#" id="incubators-btn">
        <span class="material-symbols-sharp">business</span>
        <h3>Incubators</h3>
      </a>
      <a href="#" class="active" id="participations-btn">
        <span class="material-symbols-sharp">event</span>
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
    <!-- Dashboard -->
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
              <svg><circle r="30" cy="40" cx="40"></circle></svg>
              <div class="number"><p>80%</p></div>
            </div>
          </div>
          <small>Last 24 Hours</small>
        </div>
      </div>
    </div>

    <!-- Incubator -->
    <div class="incubator-content" style="display: none;">
      <center>
        <h1>Incubators</h1>
        <div class="actions">
          <button id="add-btn"><span class="material-symbols-sharp">add_circle</span> Add Incubator</button>
          <button id="modify-btn"><span class="material-symbols-sharp">edit</span> Modify Incubator</button>
          <button id="delete-btn"><span class="material-symbols-sharp">delete</span> Delete Incubator</button>
        </div>
      </center>
    </div>

    <!-- Participation Section -->
    <div class="participation-content" style="display: none;">
      <h1>Participation List</h1>

      <table>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Last Name</th>
          <th>Date</th>
          <th>Formation ID</th>
          <th>Actions</th>
        </tr>
        <?php foreach ($participations as $participation): ?>
          <tr>
            <td><?= htmlspecialchars($participation['id_Part']) ?></td>
            <td><?= htmlspecialchars($participation['nom_user']) ?></td>
            <td><?= htmlspecialchars($participation['prenom_user']) ?></td>
            <td><?= htmlspecialchars($participation['date_Part']) ?></td>
            <td><?= htmlspecialchars($participation['id_form']) ?></td>
            <td>
              <a href="participations.php?delete_id=<?= $participation['id_Part'] ?>" onclick="return confirm('Delete this participation?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </main>
</div>

<script>
  const dashboardContent = document.querySelector(".startup-content");
  const incubatorContent = document.querySelector(".incubator-content");
  const participationContent = document.querySelector(".participation-content");

  document.getElementById("dashboard-btn").addEventListener("click", () => {
    dashboardContent.style.display = "block";
    incubatorContent.style.display = "none";
    participationContent.style.display = "none";
  });

  document.getElementById("incubators-btn").addEventListener("click", () => {
    dashboardContent.style.display = "none";
    incubatorContent.style.display = "block";
    participationContent.style.display = "none";
  });

  document.getElementById("participations-btn").addEventListener("click", () => {
    dashboardContent.style.display = "none";
    incubatorContent.style.display = "none";
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

  .participation-content h1 {
    color: #2d3436;
    text-align: center;
    margin-bottom: 1.5rem;
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
