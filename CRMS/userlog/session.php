<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check whether the session variable user_id is present or not
if (!isset($_SESSION['user_id']) || (trim($_SESSION['user_id']) == '')) {
    header("location: userdashboard.php");
    exit();
}

$session_id = $_SESSION['user_id'];
?>
