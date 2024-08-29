<?php
session_start();

if (!isset($_SESSION["login_user"])) {
    echo "
    <script type='text/javascript'>
        window.location.href ='../../index.php';
    </script>";
    exit;
}

include_once("../../dbConnect.php");

$login_user = $_SESSION["login_user"];
$custID = $_SESSION["uid"];
$appointment_id = $_POST['appointment_id'] ?? null;

$rating = $_POST['rating'] ?? null;
$comment = $_POST['comment'] ?? null;

if ($appointment_id === null || $rating === null) {
    echo "
    <script type='text/javascript'>
        alert('Invalid data. Please try again.');
        window.location.href ='../view-appointment.php';
    </script>";
    exit;
}

$insertQuery = "
    INSERT INTO feedback (rate, comment, custID, apptID)
    VALUES (?, ?, ?, ?)
";

$stmt = $conn->prepare($insertQuery);
$stmt->bind_param('isii', $rating, $comment, $custID, $appointment_id);
$stmt->execute();
$stmt->close();

echo "
<script type='text/javascript'>
    alert('Thank you for your feedback!');
    window.location.href ='../view-appointment.php';
</script>";