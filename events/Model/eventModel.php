<?php
class Event {
    private $id_event, $nom_event, $date_event, $desc_event, $lieu_event, $img_event;

    public function __construct($id_event = null, $nom_event = null, $date_event = null, $desc_event = null, $lieu_event = null, $img_event = null) {
        $this->id_event = $id_event;
        $this->nom_event = $nom_event;
        $this->date_event = $date_event;
        $this->desc_event = $desc_event;
        $this->lieu_event = $lieu_event;
        $this->img_event = $img_event;
    }

    // Getters
    public function getIdEvent() { return $this->id_event; }
    public function getNomEvent() { return $this->nom_event; }
    public function getDateEvent() { return $this->date_event; }
    public function getDescEvent() { return $this->desc_event; }
    public function getLieuEvent() { return $this->lieu_event; }
    public function getImgEvent() { return $this->img_event; }

    // Setters avec validation
    public function setIdEvent($id_event) { 
        $this->id_event = $id_event; 
    }
    
    public function setNomEvent($nom_event) { 
        if (empty(trim($nom_event))) {
            throw new Exception("Le nom de l'événement ne peut pas être vide");
        }
        $this->nom_event = htmlspecialchars($nom_event); 
    }
    
    public function setDateEvent($date_event) { 
        if (empty($date_event)) {
            throw new Exception("La date de l'événement est requise");
        }
        $this->date_event = $date_event; 
    }
    
    public function setDescEvent($desc_event) { 
        if (empty(trim($desc_event))) {
            throw new Exception("La description ne peut pas être vide");
        }
        $this->desc_event = htmlspecialchars($desc_event); 
    }
    
    public function setLieuEvent($lieu_event) { 
        if (empty(trim($lieu_event))) {
            throw new Exception("Le lieu ne peut pas être vide");
        }
        $this->lieu_event = htmlspecialchars($lieu_event); 
    }
    
    public function setImgEvent($img_event) { 
        $this->img_event = $img_event; 
    }
}
?>