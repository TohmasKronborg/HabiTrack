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
    
    <title>HabiTrak</title>
    
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
        <div class="col-12 col-md-3">
            <label for="dailies" class="fs-5">Dailies</label>
            <div id="dailies" class="bg-white rounded-4 p-2">

                <div class="d-flex justify-content-between">
                    <div class="check-bg bg-primary rounded-4">
                        <div class="check-box rounded-4"></div>
                    </div>

                    <div>
                        <p class="m-0 fs-2 fw-bold">
                            Daily name
                        </p>
                        <p class="m-0">
                            Daily desc
                        </p>
                    </div>

                    <p class="fs-1 mt-auto mb-auto">X</p>
                </div>

            </div>
        </div>
        <!-- Habits -->
        <div class="col-12 col-md-3">
            <label for="habits" class="fs-5">Habits</label>
            <div id="habits" class="bg-white rounded-4 p-2">

                <div class="d-flex justify-content-between">
                    <div class="check-bg bg-primary rounded-4">
                        <div class="check-box rounded-4 d-flex justify-content-center align-items-center"><span class="fs-1 text-center">+</span></div>
                    </div>

                    <div>
                        <p class="m-0 fs-2 fw-bold">
                            Daily name
                        </p>
                        <p class="m-0">
                            Daily desc
                        </p>
                    </div>

                    <p class="fs-1 mt-auto mb-auto">X</p>
                </div>

            </div>
        </div>
        <!-- To-Do's -->
        <div class="col-12 col-md-3">
            <label for="To-Do" class="fs-5">To-Do</label>
            <div id="To-Do" class="bg-white rounded-4 p-2">

                <div class="d-flex justify-content-between">
                    <div class="check-bg bg-primary rounded-4">
                        <div class="check-box rounded-4"></div>
                    </div>

                    <div>
                        <p class="m-0 fs-2 fw-bold">
                            Daily name
                        </p>
                        <p class="m-0">
                            Daily desc
                        </p>
                    </div>

                    <p class="fs-1 mt-auto mb-auto">X</p>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
