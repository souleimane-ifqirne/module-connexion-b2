<?php

require_once "../Class/Connect.php";
require_once "../Class/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User(false, $_POST['login'], $_POST['firstname'], $_POST['lastname'], 
    $_POST['email'], $_POST['emailCheckout'], $_POST['password'], $_POST['passwordCheckout']);

    // || connect to databse
    $conn = new Connect;
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
    
    // || verify if fields are empty
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
    
    if ($conn->loginExist($user->getLogin()) > 0) {
        $errors[] = $errorMessages['login_exists'];
    } $conn->closeStmt();

    // || verify if email match with email confirmation
    if (strcasecmp($user->getEmail(), $user->getEmailCheckout()) !== 0) {
        $errors[] = $errorMessages['email_mismatch'];
    }

    if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
        $errors[] = $errorMessages['email_invalid'];
    } else {
        if ($conn->emailExist($user->getEmail()) > 0) {
            $errors[] = $errorMessages['email_exists'];
        } $conn->closeStmt();
    }
    // || checkout every error possible and Insert ser information into database
    if (empty($errors)) {
        if ($conn->insertUser($user)) {
            $success = "Inscription résussie !";
        } else {
            $errors[] = "Erreur lors de l'inscription : " . $stmt->errorInfo()[2];
        }
    }
    $conn->closeDb();                                                                                                 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="../CSS/inscription.css">
    <script src="../JS/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#login').on('input', function() {
                var login = $('#login').val();

                $.ajax({
                    url: 'Check_login.php',
                    type: 'POST',
                    data: { login: login },
                    success: function(response) {
                        if (response === 'exists') {
                            $('#login_error_ajax').text("Le Login est déjà utilisé. Veuillez en choisir un autre.");
                        } else {
                            $('#login_error_ajax').text("");
                        }
                    }
                });

                if (login.length < 3) {
                    $('#login_error').text("Le Login doit contenir au moins 3 caractères.");
                } else {
                    $('#login_error').text("");
                }

            });

            $('#password').on('input', function() {
                var password = $('#password').val();

                var errors = [];

                if (password.length < 8) {
                    errors.push("Le mot de passe doit contenir au moins 8 caractères.");
                }

                if (!/[A-Z]/.test(password) || !/[a-z]/.test(password)) {
                    errors.push("Le mot de passe doit contenir au moins une lettre majuscule et une minuscule.");
                }

                if (!/[0-9]/.test(password)) {
                    errors.push("Le mot de passe doit contenir au moins un chiffre.");
                }

                if (!/[^A-Za-z0-9]/.test(password)) {
                    errors.push("Le mot de passe doit contenir au moins un caractère spécial.");
                }

                if (errors.length > 0) {
                    $('#password_error').html(errors.join("<br>"));
                } else {
                    $('#password_error').html("");
                }
            });

            $('#email, #emailCheckout').on('input', function() {
                var email = $('#email').val();
                var emailCheckout = $('#emailCheckout').val();

                $.ajax({
                    url: 'Check_email.php',
                    type: 'POST',
                    data: { email: email },
                    success: function(response) {
                        if (response === 'exists') {
                            $('#email_error_ajax').text("Cette adresse e-mail est déjà associée à un compte. Veuillez en choisir une autre.");
                        } else {
                            $('#email_error_ajax').text("");
                        }
                    }
                });

                if ($('#emailCheckout').is(':focus') && email.toLowerCase() !== emailCheckout.toLowerCase()) {
                    $('#email_checkout_error').text("Les adresses email ne correspondent pas.");
                } else {
                    $('#email_checkout_error').text("");
                }

                // Vérification de l'adresse e-mail
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    $('#email_error').text("L'adresse e-mail doit être au format example@example.com.");
                } else {
                    $('#email_error').text("");
                }

            });

            $('#password, #passwordCheckout').on('input', function() {
                var password = $('#password').val();
                var passwordCheckoutemailCheckout = $('#passwordCheckout').val();

                if ($('#passwordCheckout').is(':focus') && password !== passwordCheckout) {
                    $('#password_checkout_error').text("Les mots de passe ne correspondent pas.");
                } else {
                    $('#password_checkout_error').text("");
                }
            });

            function checkErrors() {
                var errors = 0;

                if ($('#login_error').text() !== "") {
                    errors++;
                }
                if ($('#email_error').text() !== "") {
                    errors++;
                }
                if ($('#email_checkout_error').text() !== "") {
                    errors++;
                }
                if ($('#password_error').text() !== "") {
                    errors++;
                }
                if ($('#password_checkout_error').text() !== "") {
                    errors++;
                }

                if (errors > 0) {
                    $('#inscription_button').prop('disabled', true);
                } else {
                    $('#inscription_button').prop('disabled', false);
                }
            }

            $('#login').on('input', checkErrors);
            $('#email, #emailCheckout').on('input', checkErrors);
            $('#password, #passwordCheckout').on('input', checkErrors);

        });
    </script>
</head>
<body>
    <nav></nav>
    <main></main>
    <div class="main-form">
		<input type="checkbox" id="chk" aria-hidden="true">

        <div class="signup">
            <form method="POST" action="" class="sign">
				<label for="chk" aria-hidden="true">Sign up</label>
                <input type="text" name="login" placeholder="login" id="login">
                <p id="login_error" style="color: #E7002A;"></p>
                <p id="login_error_ajax" style="color: #E7002A;"></p>
                <input type="text" name="firstname" placeholder="firstname" id="firstname">
                <input type="text" name="lastname" placeholder="last" id="lastname">
                <input type="email" name="email" placeholder="email" id="email">
                <p id="email_error" style="color: #E7002A;"></p>
                <p id="email_error_ajax" style="color: #E7002A;"></p>
                <input type="email" name="emailCheckout" placeholder="confirm email" id="emailCheckout"> 
                <p id="email_checkout_error" style="color: #E7002A;"></p>
                <input type="password" name="password" placeholder="password" id="password">
                <p id="password_error" style="color: #E7002A;"></p>
                <input type="password" name="passwordCheckout" placeholder="confirm password" id="passwordCheckout">
                <p id="password_checkout_error" style="color: #E7002A;"></p>
                <button>sign up</button>
            </form>
        </div>
        <div id="ErrorRegister">
        </div>
        <script>
            
        </script>
        <?php if (!empty($errors)): ?>
                    <ul style="color: #E7002A;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <p style="color: green;"><?php echo $success; ?></p>
                <?php endif; ?>
        <div class="login">
			<form>
				<label for="chk" aria-hidden="true">Login</label>
				<input type="email" name="email" placeholder="Email" required="">
				<input type="password" name="password" placeholder="Password" required="">
                <a href="Connexion.php">Connexion</a>
                <button></button>
            </form>
		</div>
    </div>
</body>
</html>