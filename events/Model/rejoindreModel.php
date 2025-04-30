<?php
class Rejoindre {
    private $id_participation;
    private $id_event;
    private $id_user;
    private $date_participation;
    private $statut_participation;
    private $telnbr;  
    private $nbrguest;  

    // Getters
    public function getIdParticipation() {
        return $this->id_participation;
    }

    public function getIdEvent() {
        return $this->id_event;
    }

    public function getIdUser() {
        return $this->id_user;
    }

    public function getDateParticipation() {
        return $this->date_participation;
    }

    public function getStatutParticipation() {
        return $this->statut_participation;
    }

    public function getTelnbr() {
        return $this->telnbr;
    }

    public function getNbrguest() {
        return $this->nbrguest;
    }

    // Setters
    public function setIdParticipation($id_participation) {
        $this->id_participation = $id_participation;
    }

    public function setIdEvent($id_event) {
        $this->id_event = $id_event;
    }

    public function setIdUser($id_user) {
        $this->id_user = $id_user;
    }

    public function setDateParticipation($date_participation) {
        $this->date_participation = $date_participation;
    }

    public function setStatutParticipation($statut_participation) {
        $this->statut_participation = $statut_participation;
    }

    public function setTelnbr($telnbr) {
        $this->telnbr = $telnbr;
    }

    public function setNbrguest($nbrguest) {
        $this->nbrguest = $nbrguest;
    }
}
?>
