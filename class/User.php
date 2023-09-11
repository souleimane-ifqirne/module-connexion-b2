<?php

require_once "./Config.php";

class User {
    private ?int $id;
    private ?string $login;
    private ?string $firstname;
    private ?string $lastname;
    private ?string $password;

    public function __construct($id=false, $login=false, $firstname=false, 
    $lastname=false, $password=false) {
        $this->id = $id;
        $this->grade_id = $login;
        $this->email = $firstname;
        $this->fullname = $lastname;
        $this->birthdate = $password;
    }
}