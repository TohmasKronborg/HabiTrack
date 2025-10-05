<?php
/** @var PDO $db */
require "settings/init.php";

session_start();

if (empty($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userId'];


if (!empty($_POST["taskId"]) && !empty($_POST["data"])) {
    $data = $_POST["data"];

    $db->sql(
        "UPDATE tasks SET title = :title, description = :description, type = :type, habitType = :habitType WHERE taskId = :taskId",
        [
            ":title"      => $data["title"],
            ":description"     => $data["description"],
            ":type" => $data["type"],
            ":habitType" => $data["habitType"],
            ":taskId"    => $_POST["taskId"]
        ]
    );


    header("Location: index.php?success=1&taskId=" . $_POST["taskId"]);
    exit;
}


if (empty($_GET["taskId"])) {
    header("Location: index.php");
    exit;
}

$taskId = $_GET["taskId"];


$task = $db->sql(
    "SELECT * FROM tasks WHERE taskId = :taskId",
    [":taskId" => $taskId]
);

$task = $task[0];

?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>Sports events</title>

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
        Opdatere en opgave med navnet <strong class="text-white"><?php echo $task->title ?></strong>
    </h3>
</header>

<div class="container">
    <?php
    if(!empty($_GET["success"]) && $_GET["success"] == 1) {
        echo "<h4>The task has been updated successfully!</h4>";
    }
    ?>

    <form action="updateTask.php" method="post">
        <div class="row d-flex justify-content-center">
            <div class="col-5 mt-5 bg-white p-5 rounded-4">

                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="data[title]" placeholder="task title" value="<?php echo $task->title ?>">


                <label for="description" class="form-label mt-2">Description</label>
                <textarea class="form-control" placeholder="Efterlad en deskriptorer til opgaven" id="description" name="data[description]" rows="3"><?php echo $task->description ?></textarea>


                <label for="type" class="form-label mt-2">Opgave Type</label>
                <select name="data[type]" id="type" class="form-select" required>
                    <option value="">--Vælg Type--</option>
                    <option value="daily" <?php echo ($task->type === 'daily') ? 'selected' : ''; ?>>Daglig</option>
                    <option value="todo" <?php echo ($task->type === 'todo') ? 'selected' : ''; ?>>To-Do</option>
                    <option value="habit" <?php echo ($task->type === 'habit') ? 'selected' : ''; ?>>Vane</option>
                </select>


                <div id="habitTypeHidden" class="d-none">
                    <label for="habitType" class="form-label mt-2">Opgave Type</label>
                    <select name="data[habitType]" id="habitType" class="form-select" required>
                        <option value="0">--Positiv eller Negativ--</option>
                        <option value="1" <?php echo ($task->habitType == '1') ? 'selected' : ''; ?>>Positiv</option>
                        <option value="0" <?php echo ($task->habitType == '0') ? 'selected' : ''; ?>>Negativ</option>
                    </select>
                </div>


                <div class="d-flex flex-column align-items-center justify-content-center">
                    <button type="submit" class="btn btn-primary w-75 mt-5 text-white">Update task</button>
                    <a href="index.php" class="mt-3">Tilbage</a>
                </div>

            </div>
        </div>

        <input type="hidden" name="taskId" value="<?php echo $taskId ?>">
    </form>

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
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>