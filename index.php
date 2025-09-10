<?php
/**
 * @var db $db
 */

require "settings/init.php";
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    
    <title>Sigende titel</title>
    
    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">
    
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body class="bg-light">

<header class="bg-primary">
    <h1 class="poppins text-center text-white p-3">
        🗿HabiTrak🗿
    </h1>
</header>

<!-- Stats div -->
<div class="m-4 bg-white rounded-4 d-flex align-items-center justify-content-center" style="height: 135px /* temp height */">
    <p class="fs-1 poppins">Stats</p>
</div>

<!-- Task Con -->
<div class="container-fluid">
    <div class="row gap-5 justify-content-center">
        <!-- Dailies -->
        <div class="col-3 bg-white rounded-4 flex-shrink-0">
            <h1>Din mor</h1>
        </div>
        <!-- Habits -->
        <div class="col-3 bg-white rounded-4 flex-shrink-0">
            <h1>Din mor</h1>
        </div>
        <!-- To-Do's -->
        <div class="col-3 bg-white rounded-4 flex-shrink-0">
            <h1>Din mor</h1>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
