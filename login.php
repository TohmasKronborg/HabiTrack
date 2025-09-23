<?php
/**
 * @var db $db
 */


require "settings/init.php";

$resultMessage = '';

if (!empty($_POST["data"])) {
    $data = $_POST["data"];
    $inputname = trim($data["name"]);
    $inputkeyword = trim($data["keyword"]);

    $sql = "SELECT name, keyword FROM users WHERE name = :name";
    $bind = [":name" => $inputname];
    $stmt = $db->sql($sql, $bind, false);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($inputkeyword, $user["keyword"])) {
        $_SESSION['user'] = $user["name"];
        header("Location: index.php");
        exit();
    } else {
        $resultMessage = "Ugyldigt login";
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

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body class="bg-light">

<header class="bg-primary">
    <h1 class="poppins text-center text-white p-3">
        🗿HabiTrak🗿
    </h1>
</header>

<div class="container w-25">
    <div class="row">
        <div class="col d-flex justify-content-center m-3">
            <p class="h2">Log ind</p>
        </div>
    </div>

    <div class="row">
        <div class="col d-flex justify-content-center m-3 mt-0 p-3 rounded-4 flex-column">
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Navn</label>
                    <input type="text" class="form-control shadow-sm" id="name" name="data[name]" maxlength="50">
                </div>
                <div class="mb-3">
                    <label for="keyword" class="form-label">keyword</label>
                    <input type="password" class="form-control shadow-sm" id="keyword" aria-describedby="keyword" name="data[keyword]">
                </div>
                <button type="submit" class="btn btn-primary text-white shadow-sm">Log ind</button>
            </form>

            <?php if (!empty($resultMessage)): ?>
                <p class="mt-2"><?php echo $resultMessage; ?></p>
            <?php endif; ?>

            <p class="mt-5 justify-content-center d-flex">Har du intet keyword? <br></p>
            <a class="d-flex mt-0 mb-3 justify-content-center" href="signup.php">Klik her for at lave et keyword!</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
