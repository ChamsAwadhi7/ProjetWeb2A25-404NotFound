<?php
require_once(__DIR__ . '/../Config/db.php');
require_once(__DIR__ . '/../Model/Participation.php');

class ParticipationController {
    private $participationModel;

    public function __construct($pdo) {
        $this->participationModel = new Participation($pdo);
    }

    // Get all participations along with user details
    public function getAllParticipations() {
        return $this->participationModel->getAllParticipations();
    }

    // Add new participation
    public function addParticipation($id_user) {
        return $this->participationModel->addParticipation($id_user);
    }

    // Handle AJAX subscription request
    public function handleAjaxSubscription() {
        // Ensure you have the correct data sent in the request
        if (isset($_POST['title']) && isset($_POST['date'])) {
            $title = $_POST['title'];
            $date = $_POST['date'];

            // Simulate getting user ID, this can be adjusted to your actual user logic
            $id_user = 1;  // This should be the actual user ID from session or other source

            // Add the participation to the database
            $isAdded = $this->participationModel->addParticipation($id_user);

            // Return a JSON response
            if ($isAdded) {
                echo json_encode(['status' => 'success', 'message' => 'Subscription successful']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save subscription']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        }
    }
}

// Handling the AJAX request when this script is accessed directly
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = config::getConnexion();  // Get the database connection
    $controller = new ParticipationController($pdo);  // Instantiate the controller
    $controller->handleAjaxSubscription();  // Call the method to handle the subscription
}
?>
