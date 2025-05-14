<?php
class User {
    private $id;
    private $nom;
    private $prénom;
    private $email;
    private $tel;
    private $password;
    private $role;
    private $date_inscription;
    private $face_image_path;
    private $face_descriptor;

    // Constructeur
    public function __construct(array $data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    

    public function getNom(): ?string {
        return $this->nom;
    }

    public function getPrénom(): ?string {
        return $this->prénom;
    }
   

    public function getEmail(): ?string {
        return $this->email;
    }

    public function getTel(): ?string {
        return $this->tel;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function getRole(): ?string {
        return $this->role;
    }

    public function getDateInscription(): ?string {
        return $this->date_inscription;
    }

    public function getFaceImagePath(): ?string {
        return $this->face_image_path;
    }

    public function getFaceDescriptor(): ?string {
        return $this->face_descriptor;
    }

    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function setPrénom(string $prénom): void {
        $this->prénom = $prénom;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setTel(string $tel): void {
        $this->tel = $tel;
    }

    public function setPassword(string $password): void {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }

    public function setFaceImagePath(string $path): void {
        $this->face_image_path = $path;
    }

    
    public function setFaceDescriptor(string $descriptor): void {
        $this->face_descriptor = $descriptor;
    }
}