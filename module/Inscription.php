<?php

require_once "../class/Config.php";
require_once "../class/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User(false, $_POST['login'], $_POST['firstname'], $_POST['lastname'], 
    $_POST['email'], $_POST['emailCheckout'], $_POST['password'], $_POST['passwordCheckout']);

    // || connect to databse
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // || Array of user "must have" to register
    $errorMessages = array(
        'login_length' => "Le nom d'utilisateur doit contenir au moins 3 caractères.",
        'empty_fields' => "Veuillez remplir tous les champs.",
        'password_mismatch' => "Les mots de passe ne correspondent pas.",
        'password_length' => "Le mot de passe est trop court. Veuillez en choisir un plus long.",
        'password_case' => "Le mot de passe doit contenir au moins une lettre majuscule et une minuscule.",
        'password_digit' => "Le mot de passe doit contenir au moins un chiffre.",
        'password_special_char' => "Le mot de passe doit contenir au moins un caractère spécial.",
        'login_exists' => "Le nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre.",
        'email_mismatch' => "Les adresses email ne correspondent pas.",
        'email_invalid' => "L'adresse e-mail doit être au format example@example.com.",
        'email_exists' => "Cette adresse e-mail est déjà associée à un compte. Veuillez en choisir une autre."
    );

    $errors = [];

    // || verify if login is longer than 3
    if (strlen($user->getLogin()) < 3) {
        $errors[] = $errorMessages['login_length'];
    }
    
    // || verify if fields are empy
    if (empty($user->getLogin()) || empty($user->getEmail()) || empty($user->getEmailCheckout()) || empty($user->getPassword()) || empty($user->getPasswordCheckout())) {
        $errors[] = $errorMessages['empty_fields'];
    }
    
    // || verify if password mastch the password confirmation
    if ($user->getPassword() !== $user->getPasswordCheckout()) {
        $errors[] = $errorMessages['password_mismatch'];
    }
    
    // || verify if password lenght is long enough
    if (strlen($user->getPassword()) < 8) {
        $errors[] = $errorMessages['password_length'];
    }
    
    // || verify if password got 1 lowercase and upercase
    if (!preg_match('/[A-Z]/', $user->getPassword()) || !preg_match('/[a-z]/', $user->getPassword())) {
        $errors[] = $errorMessages['password_case'];
    }
    
    // || verify if password has at least one digit
    if (!preg_match('/[0-9]/', $user->getPassword())) {
        $errors[] = $errorMessages['password_digit'];
    }
    
    // || verify if password has at least one special characters
    if (!preg_match('/[^A-Za-z0-9]/', $user->getPassword())) {
        $errors[] = $errorMessages['password_special_char'];
    }

        // || verify if the login is already used
    $stmt = $db->prepare('SELECT * FROM user WHERE login = :login');
    $stmt->execute([':login' => $user->getLogin()]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $errors[] = $errorMessages['login_exists'];
    } $stmt = null;

    // || verify if email match with email confirmation
    if (strcasecmp($user->getEmail(), $user->getEmailCheckout()) !== 0) {
        $errors[] = $errorMessages['email_mismatch'];
    }

    if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
        $errors[] = $errorMessages['email_invalid'];
    } else {
        $stmt = $db->prepare('SELECT * FROM user WHERE email = :email');
        $stmt->execute([':email' => $user->getEmail()]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            $errors[] = $errorMessages['email_exists'];
        } $stmt = null;
    }
    // || checkout every error possible and Insert ser information into database
    if (empty($errors)) {
        $hashedPassword = $user->hashPassword();
        $userData = [$user->getLogin(), $user->getFirstname(), $user->getLastname(), $user->getEmail(), $hashedPassword];
        $stmt = $db->prepare('INSERT INTO user (login, email, firstname, lastname, password) VALUES (:login, :email, :firstname, :lastname, :password)');
        if ($stmt->execute([':login' => $userData[0], ':firstname' => $userData[1], ':lastname' => $userData[2],':email' => $userData[3], ':password' => $userData[4]])) {
            $success = "Inscription résussie !";
        } else {
            $errors[] = "Erreur lors de l'inscription : " . $stmt->errorInfo()[2];
        }
    }
    $db = null;
}
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module I/C</title>
</head>
<body>
    <nav></nav>
    <main></main>
    <div class="sign-in">
        <p>Inscription</p>
        <form method="POST" action="" class="form">
            <div class="inputStyle">
                <input type="text" name="login" id="login">
                <label for="login">Login</label>
            </div>
            <div class="inputStyle">
                <input type="text" name="firstname" id="firstname">
                <label for="firstname">firstname</label>
            </div>
            <div class="inputStyle">
                <input type="text" name="lastname" id="lastname">
                <label for="lastname">Lastname</label>
            </div>
            <div class="inputStyle">
                <input type="email" name="email" id="email">
                <label for="email">email</label>
            </div>
            <div class="inputStyle">
                <input type="email" name="emailCheckout" id="emailCheckout">
                <label for="emailCheckout">Confirm email</label>
            </div>
            <div class="inputStyle">
                <input type="password" name="password" id="password">
                <label for="password">Password</label>
            </div>
            <div class="inputStyle">
                <input type="password" name="passwordCheckout" id="passwordCheckout">
                <label for="passwordCheckout">Confirm Password</label>
            </div>
            <div class="links">
                    <a href="connexion.php">Se connecter</a>
                </div>
                <div class="inputStyle">
                    <input type="submit" value="S'inscrire" id="inscription_button">
                </div>
        </form>
    </div>
</body>
</html>