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
        SELECT a.custID, a.date AS appointment_date, a.start_time, wp.price AS package_price 
        FROM appointment a
        JOIN washPackage wp ON a.washPackID = wp.id
        WHERE a.id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $appointment_id);
    $stmt->execute();
    $stmt->bind_result($custID, $appointment_date, $appointment_time, $package_price);
    $stmt->fetch();
    $stmt->close();

    if ($custID) {
        // Check if the customer is a walk-in (userID 2)
        $query = "
            SELECT userID 
            FROM customer 
            WHERE id = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $custID);
        $stmt->execute();
        $stmt->bind_result($userID);
        $stmt->fetch();
        $stmt->close();

        if ($userID == 2) {//walkin only
            $status = 'Paid';
            $query = "
                INSERT INTO payment (apptID, date, time, amount, paymentMethod)
                VALUES (?, ?, ?, ?, 'cash')
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('issd', $appointment_id, $appointment_date, $appointment_time, $package_price);
            $stmt->execute();
            $stmt->close();

            $message = 'Appointment marked as Paid (Walk-In).';
        } else {
            $status = 'Accepted';
            $message = 'Appointment accepted successfully.';
        }

        $query = "
            UPDATE appointment 
            SET status = ?
            WHERE id = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $status, $appointment_id);
        $stmt->execute();
        $stmt->close();

        echo "
        <script type='text/javascript'>
            alert('$message');
            window.location.href ='../view-appointment.php';
        </script>";
    }
} else {
    echo "
    <script type='text/javascript'>
        alert('Invalid appointment ID.');
        window.location.href ='../view-appointment.php';
    </script>";
}
