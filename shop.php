<?php
/**
 * @var db $db
 */

require "settings/init.php";

session_start(); // make sure the session is started

if (empty($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userId'];

// Fetch the user's name
$user = $db->sql("SELECT name FROM users WHERE userId = :userId", [":userId" => $userId]);
$username = $user[0]->name;


?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>HabiTrak</title>

    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">

    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="favicon2.jpg">

    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body class="bg-light">

<header class="bg-primary p-3">
    <h1 class="poppins text-center text-white mb-1">
        🗿HabiTrak🗿
    </h1>
    <h3 class="text-center text-white fw-normal">
        Velkommen <strong class="text-white"> <?php echo $username?> </strong>
    </h3>
</header>

<!-- Stats div -->
<div class="m-4 bg-white rounded-4 d-flex align-items-center justify-content-center" style="height: 135px /* temp height */">
    <p class="fs-1 poppins">SHOP</p>
</div>

<div class="d-flex justify-content-center">
    <a href="index.php" class="btn btn-secondary ms-3">Tilbage til dine opgaver</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>