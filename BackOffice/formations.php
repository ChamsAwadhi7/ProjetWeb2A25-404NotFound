<?php
require_once(__DIR__ . '/../../Model/Formation.php');

$formationModel = new Formation();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['edit'])) {
    // Collect form data excluding the formation_id
    $class = $_POST['class'];
    $date = $_POST['date'];
    $desc = $_POST['desc_form'];
    $price = $_POST['price_form'];
    $url = $_POST['url_form'];
    $duration = $_POST['duration_form'];
    $capacity = $_POST['capacity_form'];
    $image = $_FILES['image_form'];   

    // Validate if required fields are not empty
    if ( empty($class) || empty($date) || empty($desc) || empty($price) || empty($url) || empty($duration) || empty($capacity)|| empty($image)) {
        echo "<script>alert('Please fill all the required fields.');</script>";
    } else {
        // Add the formation if all fields are valid
        $formationModel->addFormation($class, $date, $desc, $price, $url, $duration, $capacity,$image);
        header("Location: formations.php");
        exit();
    }
}

// Handle update request if 'edit' is set
if (isset($_POST['edit'])) {
    $id_form = $_POST['id_form'];
    $class_form = $_POST['class_form'];
    $date_form = $_POST['date_form'];
    $desc_form = $_POST['desc_form'];
    $price_form = $_POST['price_form'];
    $url_form = $_POST['url_form'];
    $duration_form = $_POST['duration_form'];
    $capacity_form = $_POST['capacity_form'];
    $image_form = $_POST['image_form'];
    

    // Validate if required fields are not empty
    if (empty($id_form) || empty($class_form) || empty($date_form) || empty($desc_form) || empty($price_form) || empty($url_form) || empty($duration_form) || empty($capacity_form)|| empty($image_form)) {
        echo "<script>alert('Please fill all the required fields.');</script>";
    } else {
        // Update formation if all fields are valid
        $formationModel->updateFormation($id_form, $class_form, $date_form, $desc_form, $price_form, $url_form, $duration_form, $capacity_form, $image_form);
        header("Location: formations.php");
        exit();
    }
}

// Handle deletion of a formation
if (isset($_GET['delete_id'])) {
    $id_form = $_GET['delete_id'];
    $formationModel->deleteFormation($id_form);
    header("Location: formations.php");
    exit();
}

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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp">
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
            <div class="formation-content">
                <h1>Formations List</h1>

                <!-- Add New Formation Form -->
                <h2>Add New Formation</h2>
                <form id="addFormationForm" action="formations.php" method="POST" enctype="multipart/form-data">
    <textarea name="class" placeholder="Type"></textarea><br>
    <textarea name="desc_form" placeholder="Description"></textarea><br>
    <input type="date" name="date"><br>
    <input type="text" name="price_form" placeholder="Formation price"><br>
    <input type="text" name="url_form" placeholder="URL-Link"><br>
    <input type="text" name="duration_form" placeholder="Duration"><br>
    <input type="text" name="capacity_form" placeholder="Formation Capacity"><br>
    <input type="file" name="image_form" accept="image/*"><br> <!-- Image upload field -->
    <button type="submit">Add Formation</button>
</form>

                <hr>

                <!-- Table of Existing Formations -->
                <h2>Existing Formations</h2>
                <table>
                    <tr>
                        <th>ID</th><th>Class</th><th>Date</th><th>Description</th><th>Price</th><th>URL</th><th>Duration</th><th>Capacity</th><th>Image</th><th>Actions</th>
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
        <img src="<?= htmlspecialchars($formation['image_form']) ?>" alt="Formation Image" style="max-width: 100px; max-height: 100px;">
    </td>
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
                        <input type="text" name="id_form" value="<?= htmlspecialchars($formation['id_form']) ?>"><br>
                        <input type="text" name="class_form" value="<?= htmlspecialchars($formation['class_form']) ?>"><br>
                        <textarea name="desc_form"><?= htmlspecialchars($formation['desc_form']) ?></textarea><br>
                        <input type="date" name="date_form" placeholder="Date" value="<?= htmlspecialchars($formation['date_form']) ?>"><br>
                        <input type="text" name="price_form" placeholder="Price" value="<?= htmlspecialchars($formation['price_form']) ?>"><br>
                        <input type="text" name="url_form" placeholder="URL-Link" value="<?= htmlspecialchars($formation['url_form']) ?>"><br>
                        <input type="text" name="duration_form" placeholder="Duration" value="<?= htmlspecialchars($formation['duration_form']) ?>"><br>
                        <input type="text" name="capacity_form" placeholder="Capacity" value="<?= htmlspecialchars($formation['capacity_form']) ?>"><br>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const addFormationForm = document.getElementById('addFormationForm');
    const classFormInput = addFormationForm.querySelector('textarea[name="class"]');
    const dateInput = addFormationForm.querySelector('input[name="date"]');
    const urlInput = addFormationForm.querySelector('input[name="url_form"]');

    if (!addFormationForm || !classFormInput || !dateInput || !urlInput) return; // Ensure form, class input, date input, and URL input exist

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);

    // Real-time validation for class_form (letters and spaces only)
    classFormInput.addEventListener('input', function () {
        validateClassField(classFormInput);
    });

    // On form submit, validate all fields
    addFormationForm.addEventListener('submit', function (e) {
        let isValid = true;
        const inputs = addFormationForm.querySelectorAll('input, textarea');

        // Remove old error messages
        addFormationForm.querySelectorAll('.error-msg').forEach(msg => msg.remove());

        inputs.forEach(input => {
            const value = input.value.trim();
            const name = input.getAttribute('name');

            input.style.border = ""; // Reset border

            // Check for empty fields
            if (value === '') {
                isValid = false;
                showError(input, "This field is required");
            } else {
                // Additional validations
                if (name === 'price_form' && isNaN(value)) {
                    isValid = false;
                    showError(input, "Price can only be a number");
                }

                if (name === 'capacity_form' && (!Number.isInteger(Number(value)) || Number(value) < 1)) {
                    isValid = false;
                    showError(input, "Capacity must be a positive number");
                }

                // Validate date input (must be present or future)
                if (name === 'date') {
                    const selectedDate = new Date(value);
                    const currentDate = new Date();
                    currentDate.setHours(0, 0, 0, 0); // Set to the start of the day for comparison

                    if (selectedDate < currentDate) {
                        isValid = false;
                        showError(input, "Set date cannot be older");
                    }
                }

                // Validate URL input (specific format)
                if (name === 'url_form') {
                    const urlRegex = /^www\.google\.meet\/[A-Za-z0-9]+$/; // Updated URL format validation for AAAAA
                    if (!urlRegex.test(value)) {
                        isValid = false;
                        showError(input, "The URL link must be in the  www.google.meet/AAAAA format .");
                    }
                }

                // Validate class_form (only letters and spaces)
                if (name === 'class') {
                    if (!/^[A-Za-zÀ-ÿ\s]+$/.test(value)) { // Letters and spaces only
                        isValid = false;
                        showError(input, "Le champ classe ne peut contenir que des lettres et des espaces.");
                    }
                }
            }
        });

        if (!isValid) {
            e.preventDefault(); // Prevent form submission if any field is invalid
        }
    });

    // Function to show error messages
    function showError(input, message) {
        input.style.border = "2px solid red"; // Highlight field with invalid input
        const error = document.createElement('span');
        error.classList.add('error-msg');
        error.style.color = 'red';
        error.style.fontSize = '0.9rem';
        error.textContent = message;
        input.after(error);
    }

    // Function to validate class_form field (only letters and spaces)
    function validateClassField(input) {
        const value = input.value.trim();
        const regex = /^[A-Za-zÀ-ÿ\s]*$/; // Allow only letters and spaces

        if (!regex.test(value)) {
            input.style.border = "2px solid red"; // Highlight field with invalid input
            let errorMsg = input.nextElementSibling;
            if (!errorMsg || !errorMsg.classList.contains('error-msg')) {
                showError(input, "The class field can only contain Letters and Spaces");
            }
        } else {
            input.style.border = ""; // Reset border if valid
            let errorMsg = input.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('error-msg')) {
                errorMsg.remove(); // Remove error message
            }
        }
    }
});
    </script>

</body>
</html>
