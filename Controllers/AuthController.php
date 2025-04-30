<?php
// require '../../../Models/users.php';

class AuthController {
    private $User;
    
    public function __construct($db) {
        $this->User = new User($db);
        session_start();
    }

    public function handleRegister() {
        $errors = [];
        foreach (['nom','prenom','email','password','role'] as $f) {
            if (empty($_POST[$f])) $errors[] = "$f is required";
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
        if (!$errors && $this->User->findByEmail($_POST['email'])) $errors[] = "Email taken";
        if ($errors) return $errors;
        $this->User->create($_POST);
        header('Location: login_register.php'); exit;
    }

    public function handleLogin() {
        $errors = [];
    
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $errors[] = "Email & password required";
        } else {
            $user = $this->User->findByEmail($_POST['email']);
            if (!$user || $_POST['password'] !== $user['password']) {
                $errors[] = "Invalid credentials";
            }
        }
    
        if ($errors) return $errors;
    
        $_SESSION['user'] = $user;
        header('Location: dashboard.php');
        exit;
    }
    public function logout() {
        session_destroy();
        header('Location: ../View/FrontOffice/login_register.php'); exit;
    }
}
?>