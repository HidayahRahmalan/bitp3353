<?php
session_start();
if (!$_SESSION["login_user"]) {
    echo "
    <script type='text/javascript'>
        window.location.href ='../../index.php';
    </script>";
    exit;
}

include_once("../../dbConnect.php");

$appointment_id = $_GET['id'] ?? null;

if ($appointment_id) {
    $query = "
        UPDATE appointment 
        SET status = 'Declined'
        WHERE id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $appointment_id);
    $stmt->execute();
    $stmt->close();

    echo "
    <script type='text/javascript'>
        alert('Appointment declined successfully.');
        window.location.href ='../view-appointment.php';
    </script>";
} else {
    echo "
    <script type='text/javascript'>
        alert('Invalid appointment ID.');
        window.location.href ='../view-appointment.php';
    </script>";
}
?>
