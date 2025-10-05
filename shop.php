<?php
/**
 * @var db $db
 */

require "settings/init.php";

session_start();

if (empty($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userId'];


$user = $db->sql("SELECT name, points FROM users WHERE userId = :userId", [":userId" => $userId]);
$username = $user[0]->name;
$userPoints = $user[0]->points;

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
    <link rel="icon" href="images/favicon2.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body class="bg-light">

<header class="bg-primary p-3">
    <h1 class="poppins text-center text-white mb-1">
        🗿HabiTrak🗿
    </h1>
    <h3 class="text-center text-white fw-normal">
        Velkommen <strong class="text-white"><?php echo $username?></strong>
    </h3>
    <h4 class="text-center text-white fw-normal">
        Du har <strong class="text-white"><?php echo $userPoints ?></strong> point
    </h4>
</header>

<div class="d-flex justify-content-center">
    <a href="index.php" class="btn btn-secondary m-3">Tilbage til dine opgaver</a>
</div>

<!-- Shop div -->
<div class="m-4 mt-1 p-4 bg-white rounded-4 d-flex flex-column align-items-center justify-content-center">
    <p class="fs-1 poppins mb-3">SHOP</p>

    <div class="card" style="width: 18rem;">
        <img src="images/colorPicker.png" class="card-img-top img-fluid" alt="color picker">
        <div class="card-body">
            <h5 class="card-title">Color picker</h5>
            <p class="card-text">Med denne opgradering, kan du selv ændre farverne på hjemmesiden.</p>
            <a href="#" class="btn btn-primary text-white">Køb</a>
        </div>
    </div>
</div>

<script src="js/colorPicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
