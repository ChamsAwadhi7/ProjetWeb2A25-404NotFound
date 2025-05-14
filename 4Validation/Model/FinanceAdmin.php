<?php
class FinanceAdmin {
    private int $id_finance;
    private int $id_user;
    private float $balance;
    private string $pays;
    private string $num_carte_encrypted;
    private string $nom_bank;

    /**
     * Constructeur sécurisé avec chiffrement du numéro de carte
     */
    public function __construct(
        int $id_finance, 
        int $id_user, 
        float $balance, 
        string $pays, 
        string $num_carte,
        string $nom_bank
    ) {
        $this->id_finance = $id_finance;
        $this->id_user = $id_user;
        $this->setBalance($balance);
        $this->setPays($pays);
        $this->setNumCarte($num_carte); // Utilisation du setter pour le chiffrement
        $this->setNomBank($nom_bank);
    }

    // Getters
    public function getIdFinance(): int {
        return $this->id_finance;
    }

    public function getIdUser(): int {
        return $this->id_user;
    }

    public function getBalance(): float {
        return $this->balance;
    }

    public function getPays(): string {
        return $this->pays;
    }

    public function getNumCarteEncrypted(): string {
        return $this->num_carte_encrypted;
    }

    public function getNomBank(): string {
        return $this->nom_bank;
    }

    // Setters sécurisés
    public function setIdFinance(int $id_finance): void {
        $this->id_finance = $id_finance;
    }

    public function setIdUser(int $id_user): void {
        $this->id_user = $id_user;
    }

    public function setBalance(float $balance): void {
        $this->balance = $balance;
    }

    public function setPays(string $pays): void {
        if (strlen($pays) < 2) {
            throw new InvalidArgumentException("Le pays doit contenir au moins 2 caractères.");
        }
        $this->pays = $pays;
    }

    public function setNumCarte(string $num_carte): void {
        if (!preg_match('/^\d{12,19}$/', $num_carte)) {
            throw new InvalidArgumentException("Numéro de carte invalide.");
        }
        
        $encrypted = openssl_encrypt(
            $num_carte, 
            'AES-256-CBC', 
            $_ENV['ENCRYPTION_KEY'], 
            0, 
            $_ENV['IV_KEY']
        );
        
        if (!$encrypted) {
            throw new RuntimeException("Échec du chiffrement de la carte.");
        }
        
        $this->num_carte_encrypted = $encrypted;
    }

    public function setNomBank(string $nom_bank): void {
        if (empty($nom_bank)) {
            throw new InvalidArgumentException("Le nom de la banque ne peut être vide.");
        }
        $this->nom_bank = $nom_bank;
    }

    // Méthode pour obtenir le numéro masqué
    public function getNumCarteMasked(): string {
        try {
            $decrypted = openssl_decrypt(
                $this->num_carte_encrypted,
                'AES-256-CBC',
                $_ENV['ENCRYPTION_KEY'],
                0,
                $_ENV['IV_KEY']
            );
            
            return '**** ' . substr($decrypted, -4);
        } catch (Exception $e) {
            error_log("Erreur de déchiffrement : " . $e->getMessage());
            return '**** [ERROR]';
        }
    }

    // Représentation sécurisée
    public function __toString(): string {
        return sprintf(
            "Finance #%d | User #%d | Balance: %.2f | Pays: %s | Banque: %s | Carte: %s",
            $this->id_finance,
            $this->id_user,
            $this->balance,
            $this->pays,
            $this->nom_bank,
            $this->getNumCarteMasked()
        );
    }

    // Conversion sécurisée en tableau
    public function toArray(): array {
        return [
            'id_finance' => $this->id_finance,
            'id_user' => $this->id_user,
            'balance' => $this->balance,
            'pays' => $this->pays,
            'nom_bank' => $this->nom_bank,
            'num_carte_masked' => $this->getNumCarteMasked()
        ];
    }
}
?>