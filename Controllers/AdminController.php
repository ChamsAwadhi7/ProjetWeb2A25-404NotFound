<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/EventModel.php';
require_once __DIR__ . '/../models/ProjectModel.php';

class AdminController {
    private $userModel;
    private $eventModel;
    private $projectModel;

    public function __construct($db) {
        $this->userModel = new UserModel($db);
        $this->eventModel = new EventModel($db);
        $this->projectModel = new ProjectModel($db);
    }

    public function dashboard() {
        // Vérification de l'authentification
        if (!isset($_SESSION['admin_id'])) {
            $_SESSION['error'] = "Veuillez vous connecter";
            header("Location: /NextStep/index.php?action=login");
            exit();
        }

        try {
            // Récupération des données
            $data = [
                'stats' => [
                    'users' => $this->userModel->countUsers(),
                    'events' => $this->eventModel->countEvents(),
                    'projects' => $this->projectModel->countProjects()
                ],
                'recentUsers' => $this->userModel->getRecentUsers(5),
                'upcomingEvents' => $this->eventModel->getUpcomingEvents(3),
                'activeProjects' => $this->projectModel->getActiveProjects()
            ];

            // Chargement de la vue
            require_once __DIR__ . '/../view/BackOffice/dashboard.php';

        } catch (PDOException $e) {
            error_log("Dashboard error: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement des données";
            header("Location: /NextStep/index.php?action=login");
            exit();
        }
    }

    public function usersManagement() {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: /NextStep/index.php?action=login");
            exit();
        }

        $page = $_GET['page'] ?? 1;
        $perPage = 10;
        
        try {
            $data = [
                'users' => $this->userModel->getPaginatedUsers($page, $perPage),
                'totalUsers' => $this->userModel->countUsers(),
                'currentPage' => $page,
                'perPage' => $perPage
            ];

            require_once __DIR__ . '/../view/BackOffice/users.php';

        } catch (PDOException $e) {
            error_log("Users management error: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement des utilisateurs";
            header("Location: /NextStep/index.php?action=dashboard");
            exit();
        }
    }

    public function addAdmin() {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: /NextStep/index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'nom' => htmlspecialchars($_POST['nom']),
                    'prenom' => htmlspecialchars($_POST['prenom']),
                    'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'tel' => htmlspecialchars($_POST['tel'])
                ];

                $this->userModel->createAdmin($data);
                
                $_SESSION['success'] = "Nouvel administrateur ajouté avec succès";
                header("Location: /NextStep/index.php?action=users");
                exit();

            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header("Location: /NextStep/index.php?action=addAdmin");
                exit();
            }
        }

        require_once __DIR__ . '/../view/BackOffice/add_admin.php';
    }
}
?>