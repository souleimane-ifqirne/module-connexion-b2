<?php
class Connect {
    private $host = "localhost";
    private $username = "root";
    private $password = "root";
    private $dbname = "moduleconnexionb2";
    private $db;
    private $stmt;
    public function __construct() {
        $this->db = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function getDB() {
        return $this->db;
    }

    public function loginExist(string $user): int {
        $this->stmt = $this->db->prepare('SELECT * FROM user WHERE login = :login');
        $this->stmt->execute([':login' => $user]);
        return $this->stmt->fetchColumn();
    }

    public function emailExist(string $email): int {
        $this->stmt = $this->db->prepare('SELECT * FROM user WHERE email = :email');
        $this->stmt->execute([':email' => $email]);
        return $this->stmt->fetchColumn();
    }

    public function getPassword(string $login) {
        $this->stmt = $this->db->prepare('SELECT * FROM user WHERE login = :login');
        $this->stmt->execute([':login' => $login]);
        return $this->stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function insertUser(object $user) {
        $hashedPassword = $user->hashPassword();
        $this->stmt = $this->db->prepare('INSERT INTO user (login, email, firstname, lastname, password) VALUES (:login, :email, :firstname, :lastname, :password)');
        return ($this->stmt->execute([':login' => $user->getLogin(),
        ':firstname' => $user->getFirstname(),
        ':lastname' => $user->getLastname(),
        ':email' => $user->getEmail(),
        ':password' => $hashedPassword]));
    }
    public function closeStmt() {
        $this->stmt = null;
    }
    
    public function closeDb() {
        $this->db = null;
    }
}