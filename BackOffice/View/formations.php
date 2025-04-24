<?php
require_once(__DIR__ . '/../Model/Formation.php'); // Include the model

//Formation model instantiation
$formationModel = new Formation();

// Submission Handling
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['edit'])) {
    $formationId = $_POST['formation_id'];
    $class = $_POST['class'];
    $date = $_POST['date'];
    $desc = $_POST['desc_form'];
    $price = $_POST['price_form'];
    $url = $_POST['url_form'];
    $duration = $_POST['duration_form'];
    $capacity = $_POST['capacity_form'];   

    $formationModel->addFormation($formationId, $class, $date, $desc,$price,$url,$duration,$capacity);

    header("Location: formations.php");
    exit();
}

// Handle the edit form submission
if (isset($_POST['edit'])) {
    $id_form = $_POST['id_form'];
    $class_form = $_POST['class_form'];
    $date_form = $_POST['date_form'];
    $desc_form = $_POST['desc_form'];
    $price_form = $_POST['price_form'];
    $url_form = $_POST['url_form'];
    $duration_form = $_POST['duration_form'];
    $capacity_form = $_POST['capacity_form'];      

    $formationModel->updateFormation($id_form, $class_form, $date_form, $desc_form,$price_form,$url_form,$duration_form,$capacity_form);  

    header("Location: formations.php");
    exit();
}

// Handle deletion of a formation
if (isset($_GET['delete_id'])) {
    $id_form = $_GET['delete_id'];

    $formationModel->deleteFormation($id_form);

    header("Location: formations.php");
    exit();
}

// Fetch formations and widgets
$formations = $formationModel->getAllFormations();
$widgets = $formationModel->getWidgets();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formations</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="formationstyle.css">
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
                <a href="#">
                    <span class="material-symbols-sharp">grid_view</span>
                    <h3>Dashboard</h3>
                </a>
                <a href="#" class="active" id="formations-btn">
                    <span class="material-symbols-sharp">school</span>
                    <h3>Formations</h3>
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
                <h1>Formations List</h1>

                <!-- Add New Formation Form -->
                <h2>Add New Formation</h2>
                <form action="formations.php" method="POST">
                    <input type="text" name="formation_id" placeholder="Formation ID" required><br>
                    <textarea name="class" placeholder="Class" required></textarea><br>
                    <textarea name="desc_form" placeholder="Description" required></textarea><br>
                    <input type="date" name="date" required><br>
                    <input type="text" name="price_form" placeholder="Formation price" required><br>
                    <input type="text" name="url_form" placeholder="URl-Link" required><br>
                    <input type="text" name="duration_form" placeholder="Duration" required><br>
                    <input type="text" name="capacity_form" placeholder="Formation Capacity" required><br>
                    <button type="submit">Add Formation</button>
                </form>

                <hr>

                <!-- Table of Existing Formations -->
                <h2>Existing Formations</h2>
                <table>
                    <tr>
                        <th>ID</th><th>Class</th><th>Date</th><th>Description</th><th>Price</th><th>URL</th><th>Duration</th><th>Capacity</th><th>Actions</th>
                    </tr>
                    <?php foreach ($formations as $formation): ?>
                        <tr>
                            <td><?= htmlspecialchars($formation['id_form']) ?></td>
                            <td><?= htmlspecialchars($formation['class_form']) ?></td>
                            <td><?= htmlspecialchars($formation['date_form']) ?></td>
                            <td><?= htmlspecialchars($formation['desc_form']) ?></td>
                            <td><?= htmlspecialchars($formation['price_form']) ?></td>
                            <td><?= htmlspecialchars($formation['url_form']) ?></td>
                            <td><?= htmlspecialchars($formation['duration_form']) ?></td>
                            <td><?= htmlspecialchars($formation['capacity_form']) ?></td>
                            <td>
                                <a href="formations.php?edit_id=<?= $formation['id_form'] ?>">Edit</a> |
                                <a href="formations.php?delete_id=<?= $formation['id_form'] ?>" onclick="return confirm('Delete this formation?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <?php
               
                if (isset($_GET['edit_id'])):
                    $id_form = $_GET['edit_id'];
                    $formation = $formationModel->getFormationById($id_form);
                ?>
                    <h2>Edit Formation</h2>
                    <form action="formations.php" method="POST">
                        <input type="text" name="id_form" value="<?= htmlspecialchars($formation['id_form']) ?>" ><br>
                        <input type="text" name="class_form" value="<?= htmlspecialchars($formation['class_form']) ?>" ><br>
                        <textarea name="desc_form" required><?= htmlspecialchars($formation['desc_form']) ?></textarea><br>
                        <input type="date" name="date_form" placeholder="Date" value="<?= htmlspecialchars($formation['date_form']) ?>" ><br>
                        <input type="text" name="price_form" placeholder="Price"  value="<?= htmlspecialchars($formation['price_form']) ?>" ><br>
                        <input type="text" name="url_form" placeholder="URL-Link"  value="<?= htmlspecialchars($formation['url_form']) ?>" ><br>
                        <input type="text" name="duration_form" placeholder="Duration"  value="<?= htmlspecialchars($formation['duration_form']) ?>" ><br>
                        <input type="text" name="capacity_form" placeholder="Capacity"  value="<?= htmlspecialchars($formation['capacity_form']) ?>" ><br>
                        <button type="submit" name="edit">Update Formation</button>
                    </form>
                <?php endif; ?>

                <hr>

                
                <h2>Formation Widgets</h2>
                <div class="widget-container">
                    <?php foreach ($widgets as $widget): ?>
                        <div class="widget-card">
                            <h3><?= htmlspecialchars($widget['title']) ?></h3>
                            <p><?= htmlspecialchars($widget['content']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="script.js">
        <script>
document.addEventListener('DOMContentLoaded', function () {
    // All forms on the page
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            let isValid = true;
            const inputs = form.querySelectorAll('input, textarea');

            inputs.forEach(input => {
                // Trim and check empty values
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.border = "2px solid red";

                    // Optionally, show a message near the field
                    if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('error-msg')) {
                        const msg = document.createElement('span');
                        msg.className = 'error-msg';
                        msg.style.color = 'red';
                        msg.style.fontSize = '0.9rem';
                        msg.textContent = 'Ce champ est requis.';
                        input.after(msg);
                    }
                } else {
                    input.style.border = "1px solid #ccc";
                    const next = input.nextElementSibling;
                    if (next && next.classList.contains('error-msg')) {
                        next.remove();
                    }
                }
            });

            if (!isValid) {
                e.preventDefault(); 
            }
        });
    });
});
</script>
<style>
</style>
    </script>
</body>
</html>