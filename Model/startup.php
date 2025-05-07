<?php
class startup_id
{
    private $startup_id_id = null;
    private $nom_startup = null;
    private $but_startup = null;
    private $desc_startup = null;
    private $date_startup = null;
    private $img_startup = null;
    private $nitro;
    private $utilisateur_id = null;


    // Constructor
    public function __construct($startup_id_id = null, $nom_startup = null,  $but_startup = null, $desc_startup = null, $date_startup = null, $img_startup = null, $nitro = null, $utilisateur_id = null,)
    {
        $this->startup_id_id = $startup_id_id;
        $this->nom_startup = $nom_startup;
        $this->but_startup = $but_startup;
        $this->desc_startup = $desc_startup;
        $this->date_startup = $date_startup;
        $this->img_startup = $img_startup;
        $this->nitro = $nitro;
        $this->utilisateur_id = $utilisateur_id;

    }

    // Getters
    public function getStartupIdId() {
        return $this->startup_id_id;
    }
    public function getNomStartup() {
        return $this->nom_startup;
    }
    public function getButStartup() {
        return $this->but_startup;
    }
    public function getDescStartup() {
        return $this->desc_startup;
    }
    public function getDateStartup() {
        return $this->date_startup;
    }
    public function getImgStartup() {
        return $this->img_startup;
    }
    public function getNitro() {
        return $this->nitro;
    }
    public function getUtilisateur_id() {
        return $this->Utilisateur_id;
    }

    // Setters
    public function setStartupIdId($startup_id_id) {
        $this->startup_id_id = $startup_id_id;
    }
    public function setNomStartup($nom_startup) {
        $this->nom_startup = $nom_startup;
    }
    public function setButStartup($but_startup) {
        $this->but_startup = $but_startup;
    }
    public function setDescStartup($desc_startup) {
        $this->desc_startup = $desc_startup;
    }
    public function setDateStartup($date_startup) {
        $this->date_startup = $date_startup;
    }
    public function setImgStartup($img_startup) {
        $this->img_startup = $img_startup;
    }
    public function setUtilisateur_id($utilisateur_id) {
        $this->utilisateur_id = $utilisateur_id;
    }
}

?>