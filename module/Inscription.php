<?php

require_once "../class/Config.php";
require_once "../class/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User(false, $_POST['login'], $_POST['firstname'], $_POST['lastname'], 
    $_POST['email'], $_POST['emailCheckout'], $_POST['password'], $_POST['passwordCheckout']);

    $errors = [];

    if (empty($errors)) {
        $hashedPassword = $user->hashPassword();
        $userData = [$user->getLogin(), $user->getFirstname(), $user->getLastname(), $user->getEmail(), $hashedPassword];
        $stmt = $db->prepare("INSERT INTO user (login, email, firstname, lastname, password) VALUES (:login, :email, :firstname, :lastname, :password)");
        if ($stmt->execute([':login' => $userData[0], ':firstname' => $userData[1], ':lastname' => $userData[2],':email' => $userData[3], ':password' => $userData[4]])) {
            $success = "Inscription résussie !";
        } else {
            $errors[] = "Erreur lors de l'inscription : " . $stmt->errorInfo()[2];
        }
            $stmt = null;
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