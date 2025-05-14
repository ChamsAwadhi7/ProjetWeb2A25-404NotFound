<?php
class Investissement {
    
    private $id_investissement;
    private $user_id;
    private $montant_investissement;
    private $date;
    private $id_startups;
    private $date_fin;
    private $type_investissement;
    // Nouveaux champs pour "autre ressource"
    private $type_ressource;
    private $caracteristique;

    /**
     * @param int         $id_investissement
     * @param int         $user_id
     * @param float       $montant_investissement
     * @param string      $date
     * @param int         $id_startups
     * @param string|null $date_fin
     * @param string      $type_investissement
     * @param string|null $type_ressource
     * @param string|null $caracteristique
     */
    public function __construct(
        $id_investissement,
        $user_id,
        $montant_investissement,
        $date,
        $id_startups,
        $date_fin = null,
        $type_investissement = 'carte',
        $type_ressource = null,
        $caracteristique = null
    ) {
        $this->id_investissement      = $id_investissement;
        $this->user_id                = $user_id;
        $this->montant_investissement = $montant_investissement;
        $this->date                   = $date;
        $this->id_startups            = $id_startups;
        $this->date_fin               = $date_fin;
        $this->type_investissement    = $type_investissement;
        $this->type_ressource         = $type_ressource;
        $this->caracteristique        = $caracteristique;
    }

    // Getters
    public function getIdInvestissement()       { return $this->id_investissement; }
    public function getUserId()                 { return $this->user_id; }
    public function getMontantInvestissement()  { return $this->montant_investissement; }
    public function getDate()                   { return $this->date; }
    public function getDateFin()                { return $this->date_fin; }
    public function getIdStartups()             { return $this->id_startups; }
    public function getTypeInvestissement()     { return $this->type_investissement; }
    public function getTypeRessource()          { return $this->type_ressource; }
    public function getCaracteristique()        { return $this->caracteristique; }

    // Setters
    public function setIdInvestissement($id)         { $this->id_investissement = $id; }
    public function setUserId($user_id)              { $this->user_id = $user_id; }
    public function setMontantInvestissement($montant) { $this->montant_investissement = $montant; }
    public function setDate($date)                   { $this->date = $date; }
    public function setDateFin($date_fin)            { $this->date_fin = $date_fin; }
    public function setIdStartups($id_startups)      { $this->id_startups = $id_startups; }
    public function setTypeInvestissement($type)     { $this->type_investissement = $type; }
    public function setTypeRessource($type_ressource) { $this->type_ressource = $type_ressource; }
    public function setCaracteristique($caracteristique) { $this->caracteristique = $caracteristique; }

    /**
     * Retourne un tableau associatif de l'investissement
     */
    public function toArray()
    {
        return [
            'id_investissement'      => $this->id_investissement,
            'user_id'                => $this->user_id,
            'montant_investissement' => $this->montant_investissement,
            'date'                   => $this->date,
            'date_fin'               => $this->date_fin,
            'id_startups'            => $this->id_startups,
            'type_investissement'    => $this->type_investissement,
            'type_ressource'         => $this->type_ressource,
            'caracteristique'        => $this->caracteristique,
        ];
    }

    /**
     * Crée un objet Investissement à partir d'un tableau
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        return new self(
            $data['id_investissement'], 
            $data['user_id'], 
            $data['montant_investissement'], 
            $data['date'], 
            $data['id_startups'], 
            $data['date_fin']            ?? null,
            $data['type_investissement'] ?? 'carte',
            $data['type_ressource']      ?? null,
            $data['caracteristique']     ?? null
        );
    }
    
}
?>
