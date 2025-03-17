<?php
session_start();
session_destroy(); // Détruit la session
header("Location: ../loginPage.php"); // Redirige vers la page de connexion
exit();
