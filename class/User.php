<?php
class User {
    private ?int $id;
    private ?string $login;

    private ?string $email;
    private ?string $emailCheckout;
    
    private ?string $firstname;
    private ?string $lastname;
    private ?string $password;
    private ?string $passwordCheckout;

    public function __construct($id=false, $login=false, $firstname=false, 
    $lastname=false, $email=false, $emailCheckout=false, 
    $password=false, $passwordCheckout=false) {
        $this->id = $id;
        $this->login = $login;
        $this->email = $email;
        $this->emailCheckout = $emailCheckout;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
        $this->passwordCheckout = $passwordCheckout;
    }

    
    public function getId(): ?int {
        return $this->id;
    }
    public function setId(int $id) {
        $this->id = $id;
    }
    public function getEmail(): ?string {
        return $this->email;
    }
    public function setEmail(string $email) {
        $this->email = $email;
    }
    public function getEmailCheckout(): ?string {
        return $this->emailCheckout;
    }
    public function setEmailCheckout(string $email) {
        $this->emailCheckout = $email;
    }
    public function getLogin(): ?string {
        return $this->login;
    }
    public function setLogin(string $login) {
        $this->login = $login;
    }
    public function getFirstname(): ?string {
        return $this->firstname;
    }
    public function setFirstname(string $firstname) {
        $this->firstname = $firstname;
    }
    public function getLastname(): ?string {
        return $this->lastname;
    }
    public function setLastname(string $lastname) {
        $this->lastname = $lastname;
    }
    public function getPassword(): ?string {
        return $this->password;
    }
    public function setPassword(string $password) {
        $this->password = $password;
    }
    public function getPasswordCheckout(): ?string {
        return $this->passwordCheckout;
    }
    public function setPasswordCheckout(string $password) {
        $this->passwordCheckout = $password;
    }

    public function hashPassword() {
        return password_hash($this->password, PASSWORD_DEFAULT);
    }
}