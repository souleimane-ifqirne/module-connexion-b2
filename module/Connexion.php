<?php


require_once "../Class/Connect.php";
require_once "../Class/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User(false, $_POST['login'], false,
     false, false, false, $_POST['password'], false);


    $conn = new Connect;

    if ($conn->loginExist($user->getLogin()) > 0) {
        $row = $conn->getPassword($user->getLogin());
        $hashedPassword = $row['password'];

        if (password_verify($user->getPassword(), $hashedPassword)) {
            session_start();
            $_SESSION['id'] = $row['id'];
            $_SESSION['login'] = $row['login'];
            $_SESSION['lastname'] = $row['lastname'];
            $_SESSION['firstname'] = $row['firstname'];
            header("Location: ../index.php");
            exit();
        } else {
            $errorMessage = "Désolé, le mot de passe que vous avez saisi est incorrect. Veuillez vérifier votre saisie et réessayer.";
        }
    } else {
        $errorMessage = "L'adresse e-mail que vous avez saisie est introuvable. Veuillez vérifier votre saisie et réessayer.";
    }$conn->closeStmt();
    $conn->closeDb();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/logo_title.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="./../css/form.css">
    <title>Connexion</title>
</head>
<body>
    <section><div class="signin">
        <div class="content">
            <h2>Connexion</h2>
            <form method="POST" action="" class="form">
                <div class="input-style">
                    <input type="text" name="login" required>
                    <label for="login">login</label>
                </div>
                <div class="input-style">
                    <input type="password" name="password" required>
                    <label for="password">Password</label>
                </div>
                <?php if (!empty($errorMessage)): ?>
                <div class="error-message" style="color: #E7002A">
                    <?php echo $errorMessage; ?>
                </div>
                <?php endif; ?>
                <div class="links">
                    <a href="inscription.php">S'inscrire</a>
                </div>
                <div class="input-style">
                    <input type="submit" value="Se connecter">
                </div>
            </form>
        </div>
    </section>
</body>
</html>