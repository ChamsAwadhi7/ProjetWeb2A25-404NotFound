<?php

class Cours
{
    private $id;
    private $dateAjout;
    private $titre;
    private $description;
    private $notes;
    private $nbrVu;
    private $prix;
    private $exportation;
    private $imgCover;

    // Constructeur
    public function __construct($titre, $description, $prix, $exportation, $imgCover, $dateAjout = null, $notes = 0, $nbrVu = 0)
    {
        $this->titre = $titre;
        $this->description = $description;
        $this->prix = $prix;
        $this->exportation = $exportation;
        $this->imgCover = $imgCover;
        $this->dateAjout = $dateAjout ?: date('Y-m-d'); // Si dateAjout est null, on prend la date actuelle
        $this->notes = $notes;
        $this->nbrVu = $nbrVu;
    }

    // Getters et setters
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function getNbrVu()
    {
        return $this->nbrVu;
    }

    public function setNbrVu($nbrVu)
    {
        $this->nbrVu = $nbrVu;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function getExportation()
    {
        return $this->exportation;
    }

    public function setExportation($exportation)
    {
        $this->exportation = $exportation;
    }

    public function getImgCover()
    {
        return $this->imgCover;
    }

    public function setImgCover($imgCover)
    {
        $this->imgCover = $imgCover;
    }
}
