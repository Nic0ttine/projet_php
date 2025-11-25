<?php
session_start();
session_destroy(); // Détruit toutes les traces de la session
header("Location: login.php");
exit;
?>