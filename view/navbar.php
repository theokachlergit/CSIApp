<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<body>
    <h1>EcoFerme</h1>
    <p><?php
        $user = new PDO("mysql:host=localhost;dbname=projet_woofer;charset=utf8", "root", "");
        var_dump($_SESSION);
        if (isset($_SESSION['username'])) { ?>Bienvenue <?php echo $_SESSION['username'] ?> !</p>"; <?php } ?></p>
<a href="../databases/logout.php" class="btn btn-danger">Déconnexion</a>

</body>

</html>