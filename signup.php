<?php
/**
 * @var db $db
 */

require "settings/init.php";

$message = '';
if (!empty($_POST["data"])) {
    $data = $_POST["data"];

    if (empty($data["keyword"]) || empty($data["name"])) {
        $message = "Keyword and Name are required!";
    } else {
        $sql = "INSERT INTO users (keyword, name) VALUES (:keyword, :name)";
        $bind = [
            ":name" => $data["name"],
            ":keyword" => $data["keyword"]
        ];

        try {
            $db->sql($sql, $bind, false);
            // Redirect after successful insert to prevent duplicate insertion on reload
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1&name=" . urlencode($data["name"]));
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Fejl: Dette keyword er allerede brugt. Vælg et andet.";
            } else {
                $message = "Fejl: Der opstod en fejl under registreringen. Prøv igen.";
            }
        }
    }

} elseif (isset($_GET['success'])) {
    header("Location: login.php?success=1");
    exit;
}

?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <title>HabiTrak</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="bg-light">
<header class="bg-primary">
    <h1 class="poppins text-center text-white p-3">🗿HabiTrak🗿</h1>
</header>
<div class="container">
    <div class="row">
        <div class="col d-flex justify-content-center m-3">
            <p class="h2">Registrér ny konto</p>
        </div>
    </div>
    <div class="row">
        <div class="col d-flex justify-content-center m-3 p-3 flex-column rounded-4">
            <?php if ($message !== ''): ?>
                <div class="alert alert-info text-center"><?php echo $message; ?></div>
            <?php endif; ?>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Navn</label>
                    <input type="text" class="form-control shadow-sm" id="name" name="data[name]" maxlength="50" placeholder="Skal udfyldes">
                </div>
                <div class="mb-3">
                    <label for="keyword" class="form-label">Keyword</label>
                    <input type="password" class="form-control shadow-sm" id="keyword" name="data[keyword]" required placeholder="Skal udfyldes">
                </div>
                <button type="submit" class="btn btn-secondary text-white shadow-sm">Registrér</button>
            </form>
            <p class="mt-5 justify-content-center d-flex">Har du allerede et keyword?<br></p>
            <a class="d-flex mt-0 mb-3 justify-content-center" href="login.php">Klik her for at logge ind.</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>