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

// Handle form submission
if (!empty($_POST["data"])) {
    $data = $_POST['data'];

    // Insert a new task/habit into the database
    $sql = "INSERT INTO tasks (taskUserId, title, description, type, habitType) 
            VALUES (:taskUserId, :title, :description, :type, :habitType)";

    $bind = [
        ":taskUserId" => $userId,          // comes from session
        ":title"      => $data["title"],   // habit/task title
        ":description"=> $data["description"], // habit/task description
        ":type"       => $data["type"],    // "todo" or "habit"
        ":habitType"  => $data["habitType"] // "positive" or "negative" (for habits)
    ];

    $db->sql($sql, $bind, false);

    // Redirect to avoid resubmission
    header("Location: index.php");
    exit;
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
            <form action="index.php" method="post">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Lav en opgave</h1>
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
                        <option value="habit">vane</option>
                    </select>

                    <div id="habitTypeHidden" class="d-none">
                        <label for="habitType" class="form-label mt-2">Opgave Type</label>
                        <select name="data[habitType]" id="habitType" class="form-select" required>
                            <option value="">--Positiv eller Negativ--</option>
                            <option value="1">Positiv</option>
                            <option value="0">Negativ</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                    <button type="submit" class="btn btn-primary">Opret opgave</button>
                </div>

                <input type="hidden" name="questId" value="4">
            </form>
        </div>
    </div>
</div>


<!-- Example Task Con -->
<div class="container-fluid">
    <div class="row gap-5 justify-content-center">
        <!-- Dailies -->
        <div class="col-12 col-md-3">
            <label for="dailies" class="fs-5">Daglige</label>
            <div id="dailies" class="bg-white rounded-4 p-2">

                <?php
                $dailies = $db->sql('SELECT * FROM tasks WHERE type = "daily" AND taskUserId = :userId', [":userId" => $userId]);
                foreach ($dailies as $daily) {
                    ?>
                    <div class="d-flex border border-light border-2 rounded-4 p-1 m-2">
                        <div class="check-bg bg-primary rounded-4">
                            <div class="check-box rounded-4"></div>
                        </div>

                        <div class="ms-3">
                            <?php
                            echo "<p class='m-0 fs-3 fw-bold'>" . $daily->title . "</p>";
                            ?>
                            <?php
                            echo "<p class='m-0'>" . $daily->description . "</p>";
                            ?>
                        </div>

                        <div class="ms-auto align-self-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                            </svg>
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
                $todos = $db->sql('SELECT * FROM tasks WHERE type = "todo" AND taskUserId = :userId', [":userId" => $userId]);
                foreach ($todos as $todo) {
                ?>
                <div class="d-flex border border-light border-2 rounded-4 p-1 m-2">
                    <div class="check-bg bg-secondary rounded-4">
                        <div class="check-box rounded-4"></div>
                    </div>

                    <div class="ms-3">
                        <?php
                        echo "<p class='m-0 fs-3 fw-bold'>" . $todo->title . "</p>";
                        ?>
                        <?php
                        echo "<p class='m-0'>" . $todo->description . "</p>";
                        ?>
                    </div>

                    <div class="ms-auto align-self-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        </svg>
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
                    <div class="check-bg <?= $isPositive ? 'bg-success' : 'bg-danger' ?> rounded-4">
                        <div class="check-box rounded-4 d-flex justify-content-center align-items-center">
                            <span class="fs-1 text-center"><?= $isPositive ? '+' : '-' ?></span>
                        </div>
                    </div>

                    <div class="ms-3">
                        <?php echo "<p class='m-0 fs-3 fw-bold'>" . $habit->title . "</p>" ?>
                        <?php echo "<p class='m-0'>" . $habit->description . "</p>" ?>
                    </div>

                    <div class="align-self-center ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        </svg>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-5">
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
