<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check whether the session variable user_id is present or not
if (!isset($_SESSION['driver_id']) || (trim($_SESSION['driver_id']) == '')) {
    header("location: driverdashboard.php");
    exit();
}

$session_id = $_SESSION['driver_id'];
?>
