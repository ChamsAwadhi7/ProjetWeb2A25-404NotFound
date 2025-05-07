<?php
class Cours {
    private $id;
    private $dateAjout;
    private $titre;
    private $description;
    private $notes;
    private $nbrVu;
    private $prix;
    private $exportation;
    private $imgCover;

    public function __construct($row) {
        $this->id = $row['id'] ?? null;
        $this->dateAjout = $row['DateAjout'] ?? null;
        $this->titre = $row['Titre'] ?? '';
        $this->description = $row['Description'] ?? '';
        $this->notes = $row['Notes'] ?? 0;
        $this->nbrVu = $row['NbrVu'] ?? 0;
        $this->prix = $row['Prix'] ?? 0;
        $this->exportation = $row['Exportation'] ?? '';
        $this->imgCover = $row['ImgCover'] ?? '';
    }

    public function insert(Cours $cours) {
        $sql = "INSERT INTO cours (Titre, Description, Prix, imagePath, filePath)
                VALUES (?, ?, ?, ?, ?)";
        // Exécution avec PDO
      }
      

    // Getters
    public function getId() { return $this->id; }
    public function getDateAjout() { return $this->dateAjout; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getNotes() { return $this->notes; }
    public function getNbrVu() { return $this->nbrVu; }
    public function getPrix() { return $this->prix; }
    public function getExportation() { return $this->exportation; }
    public function getImgCover() { return $this->imgCover; }

    // Setters
    public function setTitre($titre) { $this->titre = $titre; }
    public function setDescription($description) { $this->description = $description; }
    public function setPrix($prix) { $this->prix = $prix; }
    public function setExportation($exportation) { $this->exportation = $exportation; }
    public function setImgCover($imgCover) { $this->imgCover = $imgCover; }

    // Méthodes utiles
    public function incrementerVu() {
        $this->nbrVu++;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'DateAjout' => $this->dateAjout,
            'Titre' => $this->titre,
            'Description' => $this->description,
            'Notes' => $this->notes,
            'NbrVu' => $this->nbrVu,
            'Prix' => $this->prix,
            'Exportation' => $this->exportation,
            'ImgCover' => $this->imgCover,
        ];
    }


    public function updateNoteMoyenne($id_cours, $moyenne) {
        $stmt = $this->pdo->prepare("UPDATE cours SET Notes = ? WHERE id = ?");
        $stmt->execute([$moyenne, $id_cours]);
    }
}

?>
