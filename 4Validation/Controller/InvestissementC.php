<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Investissement.php';

class InvestissementC {
    public $message = "";

    // Afficher tous les investissements
    public function afficherInvestissements() {
        $investissementModel = new Investissement();
        return $investissementModel->getAllInvestissements();
    }

    // Afficher les investissements d'un utilisateur
    public function afficherParUtilisateur($user_id) {
        $investissementModel = new Investissement();
        return $investissementModel->getInvestissementsParUtilisateur($user_id);
    }

    // Récupérer un investissement par ID
    public function recupererInvestissement($id) {
        $investissementModel = new Investissement();
        return $investissementModel->getInvestissementById($id);
    }

    // Ajouter un nouvel investissement
    public function ajouterInvestissement($data) {
        $investissementModel = new Investissement();
        return $investissementModel->addInvestissement($data);
    }

    // Modifier un investissement existant
    public function modifierInvestissement($id, $data) {
        $investissementModel = new Investissement();
        return $investissementModel->updateInvestissement($id, $data);
    }

    // Supprimer un investissement
    public function supprimerInvestissement($id) {
        $investissementModel = new Investissement();
        return $investissementModel->deleteInvestissement($id);
    }

    // Rechercher des investissements
    public function rechercher($champ, $valeur) {
        $investissementModel = new Investissement();
        return $investissementModel->searchInvestissements($champ, $valeur);
    }

    // Trier par montant croissant
    public function trierMontantAsc() {
        $investissementModel = new Investissement();
        return $investissementModel->sortByMontantAsc();
    }

    // Trier par montant décroissant
    public function trierMontantDesc() {
        $investissementModel = new Investissement();
        return $investissementModel->sortByMontantDesc();
    }

    // Trier par date croissante
    public function trierDateAsc() {
        $investissementModel = new Investissement();
        return $investissementModel->sortByDateAsc();
    }

    // Trier par date décroissante
    public function trierDateDesc() {
        $investissementModel = new Investissement();
        return $investissementModel->sortByDateDesc();
    }

    // Investissements proches échéance
    public function echeanceProche($days = 15) {
        $investissementModel = new Investissement();
        return $investissementModel->getInvestissementsProchesEcheance($days);
    }

    // Statistiques par type d'investissement
    public function statistiquesInvestissement() {
        $investissementModel = new Investissement();
        return $investissementModel->getInvestissementStats();
    }
}