<?php
session_start();


unset($_SESSION['user']);

setcookie("remember_user", "", time() - 3600);

header("Location: login.php");
exit;
?>

