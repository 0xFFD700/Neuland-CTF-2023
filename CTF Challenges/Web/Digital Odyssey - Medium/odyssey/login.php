<?php
session_start();


if (isset($_SESSION['user'])) {

    header("Location: index.php");
    exit;
}


if (isset($_COOKIE['remember_user'])) {
    $token = $_COOKIE['remember_user'];
    $username = getUsernameFromToken($token);

    if ($username !== false) {

        $_SESSION['user'] = $username;
        header("Location: index.php");
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === "guest" && $password === "guest") {
        $_SESSION['user'] = $username;

        if (isset($_POST["remember_me"]) && $_POST["remember_me"] === "on") {

            $token = generateToken($username);
            setcookie("remember_user", $token, time() + 604800);
        }
        header("Location: index.php");
        exit;
    } else {
        echo "<h3>Invalid login credentials.</h3>";
    }
}

function generateToken($username) {
    $time = time();
    $input = "username:$username, time:$time";
    
    $base64Token = base64_encode($input);
    $deflatedToken = gzencode($base64Token);
    $hexToken = bin2hex($deflatedToken);

    return $hexToken;
}

function getUsernameFromToken($token) {
    $deflatedToken = hex2bin($token);
    $base64Token = gzdecode($deflatedToken);
    $data = base64_decode($base64Token);
    

    if (preg_match("/username:(.*?),/", $data, $matches)) {
        return $matches[1];
    }
    
    return false;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>
        <input type="checkbox" name="remember_me" id="remember_me">
        <label for="remember_me">Remember Me</label><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>

