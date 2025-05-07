<?php
//nitro

class nitro_id
{
    private $nitro_id = null;
    private $nitro_name = null;
    private $nitro_price = null;
    private $nitro_period = null;
    

    // Constructor
    public function __construct($nitro_id = null, $nitro_name = null, $nitro_price= null, $nitro_period = null )
    {
        $this->nitro_id = $nitro_id;
        $this->nitro_name = $nitro_name;
        $this->nitro_price = $nitro_price;
        $this->nitro_period = $nitro_period;
       
    }

    // Getters
    public function getnitro_id() {
        return $this->nitro_id;
    }
    public function getnitro_name() {
        return $this->nitro_name;
    }
    public function getnitro_price() {
        return $this->nitro_price;
    }
    public function getnitro_period() {
        return $this->nitro_period;
    }
    
    // Setters
    public function setnitro_id($nitro_id) {
        $this->nitro_id= $nitro_id;
    }
    public function setnitro_name($nitro_name) {
        $this->nitro_name = $nitro_name;
    }
    public function setnitro_price($nitro_price) {
        $this->nitro_price = $nitro_price;
    }
    public function setnitro_period($nitro_period) {
        $this->nitro_period = $nitro_period;
    }
}


//workshop
class workshop_id
{
    private $id_workshop = null;
    private $nom_workshop= null;
    private $date_workshop = null;
    private $lieu_workshop = null;
    private $sujet_workshop = null;
    private $responsable = null;
    // Constructor
    public function __construct($id_workshop = null,$nom_workshop = null,$date_workshop = null,$lieu_workshop = null,$sujet_workshop = null,$responsable = null )
    {
        $this->id_workshop = $id_workshop;
        $this->nom_workshop = $nom_workshop;
        $this->date_workshop = $date_workshop;
        $this->lieu_workshop = $lieu_workshop;
        $this->sujet_workshop = $sujet_workshop;
        $this->responsable = $responsable;
    }
    // Getters
    public function getid_workshop() {
        return $this->id_workshop;
    }
    public function getnom_workshop() {
        return $this->nom_workshop;
    }
    public function getdate_workshop() {
        return $this->date_workshop;
    }
    public function getlieu_workshop() {
        return $this->lieu_workshop;
    }
    public function getsujet_workshop() {
        return $this->sujet_workshop;
    }
    public function getresponsable() {
        return $this->responsable;
    }
     // Setters
     public function setid_workshop($id_workshop) {
        $this->id_workshop= $id_workshop;
    }
    public function setnom_workshop($nom_workshop) {
        $this->nom_workshop = $nom_workshop;
    }
    public function setdate_workshop($date_workshop) {
        $this->date_workshop = $date_workshop;
    }
    public function setsujet_workshop($sujet_workshop) {
        $this->sujet_workshop = $sujet_workshop;
    }
    public function setresponsable($responsable) {
        $this->responsable = $responsable;
    }
}

//workingspace
class workingspace_id
{
    private $id_workingspace = null;
    private $nom_workingspace = null;
    private $surface = null;
    private $prix_workingspace = null;
    private $capacite_workingspace = null;
    private $localisation = null;

    // Constructor
    public function __construct($id_workingspace = null, $nom_workingspace = null, $surface = null, $prix_workingspace = null, $capacite_workingspace = null, $localisation = null)
    {
        $this->id_workingspace = $id_workingspace;
        $this->nom_workingspace = $nom_workingspace;
        $this->surface = $surface;
        $this->prix_workingspace = $prix_workingspace;
        $this->capacite_workingspace = $capacite_workingspace;
        $this->localisation = $localisation;
    }

    // Getters
    public function getid_workingspace() {
        return $this->id_workingspace;
    }
    public function getnom_workingspace() {
        return $this->nom_workingspace;
    }
    public function getsurface() {
        return $this->surface;
    }
    public function getprix_workingspace() {
        return $this->prix_workingspace;
    }
    public function getcapacite_workingspace() {
        return $this->capacite_workingspace;
    }
    public function getlocalisation() {
        return $this->localisation;
    }

    // Setters
    public function setid_workingspace($id_workingspace) {
        $this->id_workingspace = $id_workingspace;
    }
    public function setnom_workingspace($nom_workingspace) {
        $this->nom_workingspace = $nom_workingspace;
    }
    public function setsurface($surface) {
        $this->surface = $surface;
    }
    public function setprix_workingspace($prix_workingspace) {
        $this->prix_workingspace = $prix_workingspace;
    }
    public function setcapacite_workingspace($capacite_workingspace) {
        $this->capacite_workingspace = $capacite_workingspace;
    }
    public function setlocalisation($localisation) {
        $this->localisation = $localisation;
    }
}

?>