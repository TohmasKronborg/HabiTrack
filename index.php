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
    <p class="fs-1 poppins">Stats</p>
</div>

<!-- Opret Opgave -->
<!-- Button trigger modal -->
<div class="d-flex justify-content-center">
    <button type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Lav en opgave
    </button>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Lav en opgave</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Example Task Con -->
<div class="container-fluid">
    <div class="row gap-5 justify-content-center">
        <!-- Dailies -->
        <div class="col-12 col-md-3">
            <label for="dailies" class="fs-5">Dailies</label>
            <div id="dailies" class="bg-white rounded-4 p-2">

                <div class="d-flex justify-content-between border border-light border-2 rounded-4 p-1">
                    <div class="check-bg bg-primary rounded-4">
                        <div class="check-box rounded-4"></div>
                    </div>

                    <div>
                        <p class="m-0 fs-3 fw-bold">
                            Daily name
                        </p>
                        <p class="m-0">
                            Daily desc
                        </p>
                    </div>

                    <div class="d-flex justify-content-center align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        </svg>
                    </div>
                </div>

            </div>
        </div>
        <!-- To-Do's -->
        <div class="col-12 col-md-3">
            <label for="To-Do" class="fs-5">To-Do</label>
            <div id="To-Do" class="bg-white rounded-4 p-2">

                <div class="d-flex justify-content-between border border-light border-2 rounded-4 p-1">
                    <div class="check-bg bg-secondary rounded-4">
                        <div class="check-box rounded-4"></div>
                    </div>

                    <div>
                        <p class="m-0 fs-3 fw-bold">
                            To-Do name
                        </p>
                        <p class="m-0">
                            To-Do desc
                        </p>
                    </div>

                    <div class="d-flex justify-content-center align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        </svg>
                    </div>

                </div>
            </div>
        </div>
        <!-- Habits -->
        <div class="col-12 col-md-3">
            <label for="habits" class="fs-5">Habits</label>
            <div id="habits" class="bg-white rounded-4 p-2">

                <div class="d-flex justify-content-between border border-light border-2 rounded-4 p-1">
                    <div class="check-bg bg-info rounded-4">
                        <div class="check-box rounded-4 d-flex justify-content-center align-items-center"><span class="fs-1 text-center">+</span></div>
                    </div>

                    <div>
                        <p class="m-0 fs-3 fw-bold">
                            Habit Name
                        </p>
                        <p class="m-0">
                            Habit Desc
                        </p>
                    </div>

                    <div class="d-flex justify-content-center align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        </svg>
                    </div>

                    <div class="check-bg bg-info rounded-4">
                        <div class="check-box rounded-4 d-flex justify-content-center align-items-center"><span class="fs-1 text-center">-</span></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
