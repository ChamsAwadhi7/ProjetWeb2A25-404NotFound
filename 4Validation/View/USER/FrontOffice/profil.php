<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controllers/userscontrollers.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: login_register.php');
    exit;
}

$pdo = Database::getInstance()->getConnection();
$userId = $_SESSION['user']; // Récupérer l'ID de l'utilisateur connecté

// Récupérer les informations de l'utilisateur depuis la base de données
$sql = "SELECT nom, prénom, email, tel FROM utilisateur WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe
if (!$user) {
    echo "Utilisateur non trouvé.";
    exit;
}

// Si le formulaire est soumis, mettre à jour les informations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];

    // Appeler la fonction pour mettre à jour l'utilisateur
    $success = updateUserInfo($pdo, $userId, $nom, $prenom, $email, $tel);

    if ($success) {
        // Rediriger vers la page de profil ou afficher un message de succès
        header('Location: profil.php?update=success');
        exit;
    } else {
        // Afficher une erreur si la mise à jour a échoué
        echo "Erreur lors de la mise à jour des informations.";
    }
}
?>
<!--Website: wwww.codingdung.com-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodingDung | Profile Template</title>
    <link rel="stylesheet" href="../../assets/css/profil.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .error {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
}

    </style>
</head>

<body>
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            Account settings
        </h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Change password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-info">Info</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-social-links">Social links</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-connections">Connections</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-notifications">Notifications</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <div class="card-body media align-items-center">
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt
                                    class="d-block ui-w-80">
                                <div class="media-body ml-4">
                                    <label class="btn btn-outline-primary">
                                        Upload new photo
                                        <input type="file" class="account-settings-fileinput">
                                    </label> &nbsp;
                                    <button type="button" class="btn btn-default md-btn-flat">Reset</button>
                                    <div class="text-light small mt-1">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                             <!-- Affichage du formulaire de modification de profil -->
                             <?php if (isset($_GET['update']) && $_GET['update'] === 'success'): ?>
    <div class="alert alert-success">
        Profil mis à jour avec succès.
    </div>
<?php endif; ?>


<form id="updateForm" action="profil.php" method="post">
    <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
    </div>

    <div class="form-group">
        <label for="prenom">Prénom</label>
        <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prénom']) ?>" required>
    </div>

    <div class="form-group">
        <label for="email">E-mail</label>
        <input type="text" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="form-group">
        <label for="tel">Téléphone</label>
        <input type="text" class="form-control" id="tel" name="tel" value="<?= htmlspecialchars($user['tel']) ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>

                            </div>
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                            <form action="../../Controllers/change_password.php" method="post">
                                <div class="form-group">
                                    <label class="form-label">ID</label>
                                    <input type="number" class="form-control" name="id" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Current password</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New password</label>
                                    <input type="password" class="form-control" name="new_password" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Repeat new password</label>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                            </form>
                        </div>

                        </div>
                        <div class="tab-pane fade" id="account-info">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label class="form-label">Bio</label>
                                    <textarea class="form-control"
                                        rows="5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nunc arcu, dignissim sit amet sollicitudin iaculis, vehicula id urna. Sed luctus urna nunc. Donec fermentum, magna sit amet rutrum pretium, turpis dolor molestie diam, ut lacinia diam risus eleifend sapien. Curabitur ac nibh nulla. Maecenas nec augue placerat, viverra tellus non, pulvinar risus.</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Birthday</label>
                                    <input type="text" class="form-control" value="May 3, 1995">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Country</label>
                                    <select class="custom-select">
                                        <option>USA</option>
                                        <option selected>Canada</option>
                                        <option>UK</option>
                                        <option>Germany</option>
                                        <option>France</option>
                                    </select>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body pb-2">
                                <h6 class="mb-4">Contacts</h6>
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" value="+0 (123) 456 7891">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Website</label>
                                    <input type="text" class="form-control" value>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-social-links">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label class="form-label">Twitter</label>
                                    <input type="text" class="form-control" value="https://twitter.com/user">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Facebook</label>
                                    <input type="text" class="form-control" value="https://www.facebook.com/user">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Google+</label>
                                    <input type="text" class="form-control" value>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">LinkedIn</label>
                                    <input type="text" class="form-control" value>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Instagram</label>
                                    <input type="text" class="form-control" value="https://www.instagram.com/user">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-connections">
                            <div class="card-body">
                                <button type="button" class="btn btn-twitter">Connect to
                                    <strong>Twitter</strong></button>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <h5 class="mb-2">
                                    <a href="javascript:void(0)" class="float-right text-muted text-tiny"><i
                                            class="ion ion-md-close"></i> Remove</a>
                                    <i class="ion ion-logo-google text-google"></i>
                                    You are connected to Google:
                                </h5>
                                <a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                                    data-cfemail="f9979498818e9c9595b994989095d79a9694">[email&#160;protected]</a>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <button type="button" class="btn btn-facebook">Connect to
                                    <strong>Facebook</strong></button>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <button type="button" class="btn btn-instagram">Connect to
                                    <strong>Instagram</strong></button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-notifications">
                            <div class="card-body pb-2">
                                <h6 class="mb-4">Activity</h6>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when someone comments on my article</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when someone answers on my forum
                                            thread</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input">
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when someone follows me</span>
                                    </label>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body pb-2">
                                <h6 class="mb-4">Application</h6>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">News and announcements</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input">
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Weekly product updates</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Weekly blog digest</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mt-3">
            <button type="button" class="btn btn-primary">Save changes</button>&nbsp;
            <button type="button" class="btn btn-default">Cancel</button>
        </div>
    </div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

    </script>
    <script>
document.getElementById("updateForm").addEventListener("submit", function(event) {
    let hasError = false;

    // Récupérer les champs
    let nom = document.getElementById("nom");
    let prenom = document.getElementById("prenom");
    let email = document.getElementById("email");

    // Effacer les anciens messages
    document.getElementById("nomError").textContent = "";
    document.getElementById("prenomError").textContent = "";
    document.getElementById("emailError").textContent = "";

    // Vérification du nom
    let nameRegex = /^[a-zA-Z\s]+$/;
    if (!nom.value.trim() || !nameRegex.test(nom.value)) {
        document.getElementById("nomError").textContent = "Le nom est invalide (lettres uniquement).";
        hasError = true;
    }

    // Vérification du prénom
    if (!prenom.value.trim() || !nameRegex.test(prenom.value)) {
        document.getElementById("prenomError").textContent = "Le prénom est invalide (lettres uniquement).";
        hasError = true;
    }

    // Vérification de l'e-mail
    let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!email.value.trim() || !emailRegex.test(email.value)) {
        document.getElementById("emailError").textContent = "L’e-mail n’est pas valide.";
        hasError = true;
    }

    // Empêcher la soumission s'il y a des erreurs
    if (hasError) {
        event.preventDefault();
    }
});
</script>


</body>

</html>