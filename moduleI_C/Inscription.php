<?php

require_once "../class/Config.php";

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
        <form action="POST" value="" class="form">
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
                <input type="email" name="email_checkout" id="email_checkout">
                <label for="email_checkout">email</label>
            </div>
            <div class="inputStyle">
                <input type="password" name="password" id="password">
                <label for="password">Password</label>
            </div>
        </form>
    </div>
</body>
</html>