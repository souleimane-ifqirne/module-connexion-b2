<?php

session_start();

$Connected;
if (isset($_SESSION['id'])) {
	$Connected = true;
  } else {
    $Connected = false;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Sign In</title>
    <link rel="stylesheet" href="./CSS/style.css">
    <script>
        
    <?php echo "let ConnectedBackground = '$Connected';"?>
    let body = document.body;

        if (ConnectedBackground) {
            body.style.backgroundImage = url("./Assets/notConnected.png")
        } else {
            body.style.backgroundImage = url("./Assets/Connected.png")
        }
</script>
</head>
<body class="main-content">
    <nav>
        <ul>
            <li class="nav-btn" ><a id="SignUp-btn" href="./Module/Inscription.php">Sign up</a></li>
            <li class="nav-btn" ><a id="Login-btn" href="./Module/Connexion.php">Login</a></li>
            <li></li>
        </ul>
    </nav>
    <main class="welcome">
        <p id="welcome"></p>
    </main>
    <section>
        <a class="test" onclick="backgroundConnected()" href="./Module/Inscription.php">Register</a>
    </section>

</body>
</html>