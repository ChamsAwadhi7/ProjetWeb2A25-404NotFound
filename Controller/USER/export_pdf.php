<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/fpdf186/fpdf.php';


// Connexion à la base de données
$pdo = Database::getInstance()->getConnection();

// Requête pour récupérer les utilisateurs
$stmt = $pdo->query("SELECT nom, email, role FROM utilisateur ORDER BY nom ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Création du PDF
$pdf = new FPDF();
$pdf->AddPage();

// Titre
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Liste des utilisateurs'), 0, 1, 'C');
$pdf->Ln(5);

// En-tête du tableau
$pdf->SetFillColor(200, 220, 255); // Bleu clair
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(.3);
$pdf->SetFont('Arial', 'B', 14); // Police plus grande

// Définir les largeurs des colonnes
$w_nom = 60;    // Largeur pour le nom
$w_email = 90;  // Largeur pour l'email
$w_role = 40;   // Largeur pour le rôle
$h_header = 10; // Hauteur des en-têtes

$pdf->Cell($w_nom, $h_header, iconv('UTF-8', 'windows-1252', 'Nom'), 1, 0, 'C', true);
$pdf->Cell($w_email, $h_header, 'Email', 1, 0, 'C', true);
$pdf->Cell($w_role, $h_header, iconv('UTF-8', 'windows-1252', 'Rôle'), 1, 0, 'C', true);
$pdf->Ln();

// Données du tableau
$pdf->SetFillColor(224, 235, 255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 12); // Police plus grande
$h_row = 8; // Hauteur des lignes de données
$fill = false;
foreach ($users as $row) {
    $pdf->Cell($w_nom, $h_row, iconv('UTF-8', 'windows-1252', $row['nom']), 'LR', 0, 'L', $fill);
    $pdf->Cell($w_email, $h_row, iconv('UTF-8', 'windows-1252', $row['email']), 'LR', 0, 'L', $fill);
    $pdf->Cell($w_role, $h_row, iconv('UTF-8', 'windows-1252', ucfirst($row['role'])), 'LR', 0, 'C', $fill);
    $pdf->Ln();
    $fill = !$fill;
}
$pdf->Cell($w_nom + $w_email + $w_role, 0, '', 'T');

// Sortie du PDF
$pdf->Output('D', 'utilisateurs.pdf');
?>