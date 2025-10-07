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

// Henter bruger data
$userId = $_SESSION['userId'];

$user = $db->sql("SELECT name FROM users WHERE userId = :userId", [":userId" => $userId]);
$username = $user[0]->name;

$point = $db->sql("SELECT points FROM users WHERE userId = :userId", [":userId" => $userId]);
$userPoints = $point[0]->points;

// Opretter en ny opgave
if (!empty($_POST["data"])) {
    $data = $_POST['data'];

    $sql = "INSERT INTO tasks (taskUserId, title, description, type, habitType) 
            VALUES (:taskUserId, :title, :description, :type, :habitType)";

    $bind = [
        ":taskUserId" => $userId,
        ":title"      => $data["title"],
        ":description"=> $data["description"],
        ":type"       => $data["type"],
        ":habitType"  => $data["habitType"]
    ];

    $db->sql($sql, $bind, false);

    header("Location: index.php");
    exit;
}

// Sletter opgave
if(!empty($_GET["delete"]) && $_GET["delete"] == "1" && !empty($_GET["taskId"])) {
    $db->sql("DELETE FROM tasks WHERE taskId = :taskId", [":taskId" => $_GET["taskId"]]);
    header("Location: index.php");
    exit;
}

// Giver point når opgave er fuldført og ændre dens status til "done"
if (isset($_GET['updateStatus'], $_GET['taskId'], $_GET['status'])) {
    $taskId = (int)$_GET['taskId'];
    $status = $_GET['status'];

    $task = $db->sql("SELECT type, habitType FROM tasks WHERE taskId = :taskId AND taskUserId = :userId", [
        ":taskId" => $taskId,
        ":userId" => $userId
    ])[0];

    // Opdater status (kun for Daglige og To-Do)
    if ($task->type !== 'habit') {
        $db->sql("UPDATE tasks SET status = :status WHERE taskId = :taskId AND taskUserId = :userId", [
            ":status" => $status,
            ":taskId" => $taskId,
            ":userId" => $userId
        ]);
    }

    // Point logik
    if ($task->type === 'habit') {
        // Habit: positiv = +5, negativ = -5
        $points = ($task->habitType == '1') ? 5 : -5;
    } elseif ($status === 'done') {
        // Daglig eller To-Do
        $points = 5;
    } else {
        $points = 0;
    }

    if ($points !== 0) {
        $db->sql("UPDATE users SET points = points + :points WHERE userId = :userId", [
            ":points" => $points,
            ":userId" => $userId
        ]);
    }

    header("Location: index.php");
    exit;
}


// Genstarter færdiggjort dailies efter en dag
$dailies = $db->sql("SELECT * FROM tasks WHERE type='daily' AND taskUserId=:userId", [":userId"=>$userId]);

foreach ($dailies as $daily) {
    $lastReset = $daily->lastReset;
    $today = date('Y-m-d');

    if ($lastReset !== $today) {
        // Reset status til pending
        $db->sql("UPDATE tasks SET status='pending', lastReset=:today WHERE taskId=:taskId", [
            ":today" => $today,
            ":taskId" => $daily->taskId
        ]);
    }
}

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
    <style>
        .dynamic-primary {
            background-color: #d517bd;
        }

        .dynamic-secondary {
            background-color: #5c42e4;
        }
    </style>
</head>

<body class="bg-light">

<header class="dynamic-primary p-3">
    <h1 class="poppins text-center text-white mb-1">
        🗿HabiTrak🗿
    </h1>
    <h3 class="text-center text-white fw-normal">
        Velkommen <strong class="text-white"> <?php echo $username?> </strong>
    </h3>
    <h4 class="text-center text-white fw-normal">
        Du har <strong class="text-white"><?php echo $userPoints ?></strong> point
    </h4>
</header>

<!-- Stats div -->
<div class="m-4 bg-white rounded-4 d-flex align-items-center justify-content-center" style="height: 135px /* temp height */">
    <p class="fs-1 poppins">Stats</p>
</div>

<!-- Knapper til diverse ting -->
<!-- Button trigger modal -->
<div class="d-flex justify-content-center mb-2">
    <button type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#taskModal">
        Lav en opgave
    </button>

    <a href="completedTasks.php" class="btn btn-secondary ms-3  text-white">Færdige opgaver</a>
    <a href="shop.php" class="btn btn-info ms-3  text-white">Til butik</a>
</div>

<!-- ColorPickers -->
<div class="d-flex justify-content-center">
    <div class="m-2 d-flex">
        <label for="colorPickerC1" class="me-1 m-auto">Vælg farve 1</label>
        <input type="color" id="colorPickerC1" value="#d517bd">
    </div>

    <div class="m-2 d-flex">
        <label for="colorPickerC2" class="me-1 m-auto">Vælg farve 2</label>
        <input type="color" id="colorPickerC2" value="#5c42e4">
    </div>

    <div class="m-2 d-flex">
        <label for="colorPickerC3" class="me-1 m-auto">Vælg farve 3</label>
        <input type="color" id="colorPickerC3" value="#0fa0db">
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php" method="post">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="taskModalLabel">Lav en opgave</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <label for="title" class="form-label">Task</label>
                    <input type="text" class="form-control" id="title" name="data[title]" placeholder="Task" value="" required>

                    <label for="description" class="form-label mt-2">Description</label>
                    <textarea class="form-control" placeholder="Efterlad en deskriptorer til opgaven" id="description" name="data[description]"></textarea>

                    <label for="type" class="form-label mt-2">Opgave Type</label>
                    <select name="data[type]" id="type" class="form-select" required>
                        <option value="">--Vælg Type--</option>
                        <option value="daily">Daglig</option>
                        <option value="todo">To-Do</option>
                        <option value="habit">Vane</option>
                    </select>

                    <div id="habitTypeHidden" class="d-none">
                        <label for="habitType" class="form-label mt-2">Opgave Type</label>
                        <select name="data[habitType]" id="habitType" class="form-select" required>
                            <option value="0">--Positiv eller Negativ--</option>
                            <option value="1">Positiv</option>
                            <option value="0">Negativ</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                    <button type="submit" class="btn btn-primary">Opret opgave</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Task Cons -->
<div class="container-fluid">
    <div class="row gap-5 justify-content-center">
        <!-- Dailies -->
        <div class="col-12 col-md-3">
            <label for="dailies" class="fs-5">Daglige</label>
            <div id="dailies" class="bg-white rounded-4 p-2">

                <?php
                $dailies = $db->sql('SELECT * FROM tasks WHERE type = "daily" AND status != "done" AND taskUserId = :userId', [":userId" => $userId]);
                foreach ($dailies as $daily) {
                    ?>
                    <div class="d-flex border border-light border-2 rounded-4 p-1 m-2">
                        <div class="check-bg dynamic-primary rounded-4">
                            <a href="index.php?updateStatus=1&taskId=<?= $daily->taskId ?>&status=done">
                                <div class="check-box rounded-4"></div>
                            </a>
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
                                    <a href="updateTask.php?taskId=<?php echo $daily->taskId?>" class="dropdown-item">Rediger Opgave</a>
                                </li>
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
                $todos = $db->sql('SELECT * FROM tasks WHERE type = "todo" AND status != "done" AND taskUserId = :userId', [":userId" => $userId]);
                foreach ($todos as $todo) {
                ?>
                <div class="d-flex border border-light border-2 rounded-4 p-1 m-2">
                    <div class="check-bg dynamic-secondary rounded-4">
                        <a href="index.php?updateStatus=1&taskId=<?php echo $todo->taskId ?>&status=done">
                            <div class="check-box rounded-4 d-flex align-items-center justify-content-center fw-bold fs-2">
                                <?php echo ($todo->status === 'done') ? '✔' : ''; ?>
                            </div>
                        </a>
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
                                <a href="updateTask.php?taskId=<?php echo $todo->taskId?>" class="dropdown-item">Rediger Opgave</a>
                            </li>
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
        
        <!-- Habits -->
        <div class="col-12 col-md-3">
            <label for="habits" class="fs-5">Vaner</label>
            <div id="habits" class="bg-white rounded-4 p-2">
                <?php
                $habits = $db->sql('SELECT * FROM tasks WHERE type = "habit" AND taskUserId = :userId', [":userId" => $userId]);
                foreach ($habits as $habit) {
                    $isPositive = ($habit->habitType == '1'); // check habit type
                ?>
                <div class="d-flex border border-light border-2 rounded-4 p-1 m-2">
                    <div class="check-bg rounded-4 <?= $isPositive ? 'bg-success' : 'bg-danger' ?>">
                        <a href="index.php?updateStatus=1&taskId=<?= $habit->taskId ?>&status=habit">
                            <div class="check-box rounded-4 d-flex justify-content-center align-items-center">
                                <span class="fs-1 text-center text-black"><?= $isPositive ? '+' : '-' ?></span>
                            </div>
                        </a>
                    </div>

                    <div class="ms-3">
                        <?php echo "<p class='m-0 fs-5 fw-bold'>" . $habit->title . "</p>" ?>
                        <?php echo "<p class='m-0'>" . $habit->description . "</p>" ?>
                    </div>

                    <div class="btn-group dropstart ms-auto align-self-center">
                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                            </svg>
                        </button>
                        <ul class="dropdown-menu text-center fs-4 fw-bold">
                            <li>
                                <a href="updateTask.php?taskId=<?php echo $habit->taskId?>" class="dropdown-item">Rediger Opgave</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?delete=1&taskId=<?php echo $habit->taskId?>">Slet Opgave</a>
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
<script src="js/colorPicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>