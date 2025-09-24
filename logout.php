<?php
/**
 * @var db $db
 */

require "settings/init.php";

session_start(); // Ensure session is started

// Unset all session variables
$_SESSION = [];

// Destroy the session entirely
session_destroy();

// Optionally, delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login page
header("Location: login.php");
exit();


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>

</body>
</html>
