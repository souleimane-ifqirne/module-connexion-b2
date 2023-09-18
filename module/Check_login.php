<?php

require_once "../class/Connect.php";

$login = $_POST['login'];

$conn = new Connect;

if ($conn->loginExist($login) > 0) {
    echo 'exists';
} else {
    echo 'not_exists';
}

$conn->closeStmt();
$conn->closeDb();