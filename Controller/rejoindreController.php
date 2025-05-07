<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../Model/rejoindreModel.php');
// Include PHPMailer classes manually



// Activer l'affichage des erreurs PHP
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../View/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../View/PHPMailer/SMTP.php';
require_once __DIR__ . '/../View/PHPMailer/Exception.php';

// Utiliser PHPMailer pour envoyer l'email
$mail = new PHPMailer(true);


class RejoindreController {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    /**
     * Add new participation with validation
     */
    /**
 * Add new participation (updated version)
 */
public function addParticipation($eventId, $userId)
{
    $db = config::getConnexion();

    try {
        // Vérifier si la participation existe déjà
        $checkSql = "SELECT COUNT(*) FROM rejoindre WHERE id_event = :id_event AND id_user = :id_user";
        $checkQuery = $db->prepare($checkSql);
        $checkQuery->bindParam(':id_event', $eventId, PDO::PARAM_INT);
        $checkQuery->bindParam(':id_user', $userId, PDO::PARAM_INT);
        $checkQuery->execute();
        $count = $checkQuery->fetchColumn();

        if ($count > 0) {
            throw new Exception('Vous êtes déjà inscrit à cet événement.');
        }

        // Ajouter la participation
        $insertSql = "INSERT INTO rejoindre (id_event, id_user, date_participation, statut_participation) 
                      VALUES (:id_event, :id_user, NOW(), 'en attente')";
        $insertQuery = $db->prepare($insertSql);
        $insertQuery->bindParam(':id_event', $eventId, PDO::PARAM_INT);
        $insertQuery->bindParam(':id_user', $userId, PDO::PARAM_INT);
        $insertQuery->execute();
    } catch (Exception $e) {
        throw new Exception('Erreur lors de l\'ajout de la participation : ' . $e->getMessage());
    }
}


    /**
     * Get participation by ID
     */
    public function getParticipation(int $id): ?array {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, e.nom_event, 
                CONCAT(u.prenom_user, ' ', u.nom_user) AS user_name
                FROM rejoindre r
                JOIN events e ON r.id_event = e.id_event
                JOIN user u ON r.id_user = u.id_user
                WHERE r.id_participation = ?
            ");
            $stmt->execute([$id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (PDOException $e) {
            error_log("Error fetching participation: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Update participation status
     */
    public function updateStatus(int $id, string $status): bool {
        try {
            $validStatuses = ['en attente', 'confirmé', 'annulé'];
            if (!in_array($status, $validStatuses)) {
                throw new InvalidArgumentException("Invalid status value");
            }

            $stmt = $this->db->prepare("
                UPDATE rejoindre 
                SET statut_participation = ? 
                WHERE id_participation = ?
            ");
            return $stmt->execute([$status, $id]);

        } catch (PDOException $e) {
            error_log("Error updating status: " . $e->getMessage());
            return false;
        }
    }



    public function sendConfirmationEmailForConfirmedParticipation($id_participation) {
        
    
        // Récupérer les informations de la participation confirmée
        $stmt = $this->db->prepare("
            SELECT u.email_user, u.nom_user, e.nom_event, e.date_event, e.lieu
            FROM rejoindre p
            JOIN user u ON p.id_user = u.id_user
            JOIN events e ON p.id_event = e.id_event
            WHERE p.id_participation = :id_participation
        ");
        $stmt->execute([':id_participation' => $id_participation]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($data) {
            $email = $data['email_user'];
            $nomUser = $data['nom_user'];
            $eventName = $data['nom_event'];
            $eventDate = date('d/m/Y', strtotime($data['date_event']));
            $eventLocation = $data['lieu'];
    
            // Utiliser PHPMailer pour envoyer l'email
            $mail = new PHPMailer(true);
            try {
                // Paramètres de serveur SMTP
                ini_set('display_errors', 1);
error_reporting(E_ALL);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';  

$mail->SMTPAuth = true;
$mail->Username = 'ghribimedaziz007@gmail.com';  // Ton email
$mail->Password = 'tpwxzuxnmmbmssqi';  //mot de passe d'application
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
$mail->Port = 587;  // Port SMTP pour Outlook (STARTTLS)
    
                // Expéditeur et destinataire
                $mail->setFrom('ghribimedaziz007@gmail.com', 'Equipe NextStep');
                $mail->addAddress($email, $nomUser);
    
                // Contenu de l'email (HTML)
                $mail->isHTML(true);
                $mail->Subject = "Confirmation de votre participation à l'evenement \"$eventName\"";
                $mail->Body = "
                    <div>
                        <h2>Confirmation de votre inscription</h2>
                        <p>Bonjour <strong>$nomUser</strong>,</p>
                        <p>Nous vous confirmons votre inscription à :</p>
                        <ul>
                            <li><strong>Événement :</strong> $eventName</li>
                            <li><strong>Date :</strong> $eventDate</li>
                            <li><strong>Lieu :</strong> $eventLocation</li>
                        </ul>
                        <p>À très bientôt !</p>
                    </div>
                ";
                // Envoi de l'email
                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    error_log("Mailer Error: " . $mail->ErrorInfo); // Log the error in the server log
                }
                
        }
    }
    

    /**
     * Delete participation
     */
    public function deleteParticipation(int $id): bool {
        try {
            $stmt = $this->db->prepare("
                DELETE FROM rejoindre 
                WHERE id_participation = ?
            ");
            return $stmt->execute([$id]);

        } catch (PDOException $e) {
            error_log("Error deleting participation: " . $e->getMessage());
            return false;
        }
    }
    //listParticipations
    public function listParticipations(): array {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, e.nom_event, u.nom_user, u.prenom_user 
                FROM rejoindre r
                JOIN events e ON r.id_event = e.id_event
                JOIN user u ON r.id_user = u.id_user
                ORDER BY r.date_participation DESC
            ");
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        } catch (PDOException $e) {
            error_log("Error fetching participations: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all participations for an event
     */
    public function getEventParticipations(int $eventId): array {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, CONCAT(u.prenom_user, ' ', u.nom_user) AS user_name
                FROM rejoindre r
                JOIN user u ON r.id_user = u.id_user
                WHERE r.id_event = ?
                ORDER BY r.date_participation DESC
            ");
            $stmt->execute([$eventId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error fetching event participations: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all participations for a user
     */
    public function getUserParticipations(int $userId): array {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, e.nom_event, e.date_event, e.lieu
                FROM rejoindre r
                JOIN events e ON r.id_event = e.id_event
                WHERE r.id_user = ?
                ORDER BY r.date_participation DESC
            ");
            $stmt->execute([$userId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error fetching user participations: " . $e->getMessage());
            return [];
        }
    }
    
}