<?php
session_start();
session_destroy(); // Détruit la session
header("Location: ../view/loginPage.php"); // Redirige vers la page de connexion
exit();
