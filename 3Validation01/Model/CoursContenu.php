<?php
class CoursContenu
{
    private $id;
    private $cours_id;
    private $nomChapitre;
    private $typeContenu;
    private $fichierPath;
    private $duree;

    public function __construct($id = null, $cours_id = null, $nomChapitre = null, $typeContenu = null, $fichierPath = null, $duree = null)
    {
        $this->id = $id;
        $this->cours_id = $cours_id;
        $this->nomChapitre = $nomChapitre;
        $this->typeContenu = $typeContenu;
        $this->fichierPath = $fichierPath;
        $this->duree = $duree;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getCoursId()
    {
        return $this->cours_id;
    }

    public function getNomChapitre()
    {
        return $this->nomChapitre;
    }

    public function getTypeContenu()
    {
        return $this->typeContenu;
    }

    public function getFichierPath()
    {
        return $this->fichierPath;
    }

    public function getDuree()
    {
        return $this->duree;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCoursId($cours_id)
    {
        $this->cours_id = $cours_id;
    }

    public function setNomChapitre($nomChapitre)
    {
        $this->nomChapitre = $nomChapitre;
    }

    public function setTypeContenu($typeContenu)
    {
        $this->typeContenu = $typeContenu;
    }

    public function setFichierPath($fichierPath)
    {
        $this->fichierPath = $fichierPath;
    }

    public function setDuree($duree)
    {
        $this->duree = $duree;
    }
}
?>
