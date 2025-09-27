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
    <link rel="icon" href="favicon2.jpg">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body class="bg-light">

<header class="bg-primary p-3">
    <h1 class="poppins text-center text-white mb-1">
        🗿HabiTrak🗿
    </h1>
    <h3 class="text-center text-white fw-normal">
        Her er dine fuldførte opgaver <strong class="text-white"> <?php echo $username?> </strong>
    </h3>
</header>

<!-- Stats div -->
<div class="m-4 bg-white rounded-4 d-flex align-items-center justify-content-center" style="height: 135px /* temp height */">
    <p class="fs-1 poppins">Stats</p>
</div>

<div class="d-flex justify-content-center">
       <a href="index.php" class="btn btn-secondary ms-3">Tilbage til dine opgaver</a>
</div>

<!-- Task Cons -->
<div class="container-fluid">
    <div class="row gap-5 justify-content-center">
        <!-- Dailies -->
        <div class="col-12 col-md-3">
            <label for="dailies" class="fs-5">Daglige</label>
            <div id="dailies" class="bg-white rounded-4 p-2">

                <?php
                $dailies = $db->sql('SELECT * FROM tasks WHERE type = "daily" AND status = "done" AND taskUserId = :userId', [":userId" => $userId]);
                foreach ($dailies as $daily) {
                    ?>
                    <div class="d-flex border border-light border-2 rounded-4 p-1 m-2">
                        <div class="check-bg bg-primary rounded-4">
                            <div class="check-box rounded-4 d-flex align-items-center justify-content-center fw-bold fs-2">X</div>
                        </div>

                        <div class="ms-3">
                            <?php
                            echo "<p class='m-0 fs-5 fw-bold'>" . $daily->title . "</p>";
                            ?>
                            <?php
                            echo "<p class='m-0'>" . $daily->description . "</p>";
                            ?>
                        </div>

                        <div class="btn-group dropstart ms-auto align-self-center">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                </svg>
                            </button>
                            <ul class="dropdown-menu text-center fs-4 fw-bold">
                                <li>
                                    <a class="dropdown-item" href="index.php?delete=1&taskId=<?php echo $daily->taskId?>">Slet Opgave</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <?php
                }
                ?>

            </div>
        </div>
        <!-- To-Do's -->
        <div class="col-12 col-md-3">
            <label for="To-Do" class="fs-5">To-Do</label>
            <div id="To-Do" class="bg-white rounded-4 p-2">
                <?php
                $todos = $db->sql('SELECT * FROM tasks WHERE type = "todo" AND status = "done" AND taskUserId = :userId', [":userId" => $userId]);
                foreach ($todos as $todo) {
                    ?>
                    <div class="d-flex border border-light border-2 rounded-4 p-1 m-2">
                        <div class="check-bg bg-light rounded-4">
                            <div class="check-box rounded-4 d-flex align-items-center justify-content-center fw-bold fs-2">X</div>
                        </div>

                        <div class="ms-3">
                            <?php
                            echo "<p class='m-0 fs-5 fw-bold'>" . $todo->title . "</p>";
                            ?>
                            <?php
                            echo "<p class='m-0'>" . $todo->description . "</p>";
                            ?>
                        </div>


                        <!-- Default dropup button -->
                        <div class="btn-group dropstart ms-auto align-self-center">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                </svg>
                            </button>
                            <ul class="dropdown-menu text-center fs-4 fw-bold">
                                <li>
                                    <a class="dropdown-item" href="index.php?delete=1&taskId=<?php echo $todo->taskId?>">Slet Opgave</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-5 fw-bolder">
    <a href="logout.php">log ud</a>
</div>

<script>
    const selectTaskType = document.querySelector("#type")
    const habitTypeDiv = document.querySelector("#habitTypeHidden")
    console.log(selectTaskType.value)

    selectTaskType.addEventListener("input", () => {
        if(selectTaskType.value === "habit") {
            habitTypeDiv.classList.remove("d-none")
            console.log(selectTaskType.value)
        } else {
            habitTypeDiv.classList.add("d-none")
            console.log(selectTaskType.value)
        }
    })
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
