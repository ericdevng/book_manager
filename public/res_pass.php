<?php

require_once '../includes/app.php';
require_once '../templates/header.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$email = trim($_POST['email'] ?? ''); 

$token = User::requestPassReset($email);


?>

<form action="" method="POST">
    <label for="email">E-mail: </label>
    <input type="email" name="email" placeholder="Tu e-mail" id="email">
    <button type="submit"><a href="/book_manager/public/change_pass.php">TOKEN</a></button>
</form>

