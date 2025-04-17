<?php
class startup_id
{
    private $startup_id_id = null;
    private $nom_startup = null;
    private $nom_hoster = null;
    private $prenom_hoster = null;
    private $but_startup = null;
    private $desc_startup = null;
    private $date_startup = null;
    private $img_startup = null;

    // Constructor
    public function __construct($startup_id_id = null, $nom_startup = null, $nom_hoster = null, $prenom_hoster = null, $but_startup = null, $desc_startup = null, $date_startup = null, $img_startup = null)
    {
        $this->startup_id_id = $startup_id_id;
        $this->nom_startup = $nom_startup;
        $this->nom_hoster = $nom_hoster;
        $this->prenom_hoster = $prenom_hoster;
        $this->but_startup = $but_startup;
        $this->desc_startup = $desc_startup;
        $this->date_startup = $date_startup;
        $this->img_startup = $img_startup;
    }

    // Getters
    public function getStartupIdId() {
        return $this->startup_id_id;
    }
    public function getNomStartup() {
        return $this->nom_startup;
    }
    public function getNomHoster() {
        return $this->nom_hoster;
    }
    public function getPrenomHoster() {
        return $this->prenom_hoster;
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

    // Setters
    public function setStartupIdId($startup_id_id) {
        $this->startup_id_id = $startup_id_id;
    }
    public function setNomStartup($nom_startup) {
        $this->nom_startup = $nom_startup;
    }
    public function setNomHoster($nom_hoster) {
        $this->nom_hoster = $nom_hoster;
    }
    public function setPrenomHoster($prenom_hoster) {
        $this->prenom_hoster = $prenom_hoster;
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
}
?>