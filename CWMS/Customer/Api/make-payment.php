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

$appointment_id = $_POST['appointment_id'] ?? null;
$paymentMethod = $_POST['paymentMethod'] ?? null;
$paymentProof = $_FILES['paymentProof'] ?? null;

if ($appointment_id === null || $paymentMethod === null) {
    echo "
    <script type='text/javascript'>
        alert('Invalid data. Please try again.');
        window.location.href ='../view-appointment.php';
    </script>";
    exit;
}

$amount = 0;

$appointmentQuery = "
    SELECT 
        date, start_time, wp.price AS package_price 
    FROM 
        appointment a
    JOIN 
        washPackage wp ON a.washPackID = wp.id
    WHERE 
        a.id = ?
";
$appointmentStmt = $conn->prepare($appointmentQuery);
$appointmentStmt->bind_param('i', $appointment_id);
$appointmentStmt->execute();
$appointmentStmt->bind_result($appointment_date, $appointment_time, $amount);
$appointmentStmt->fetch();
$appointmentStmt->close();

$paymentProofPath = null;

if ($paymentMethod === 'online') {
    if ($paymentProof && $paymentProof['size'] > 0) {
        $allowedFileTypes = ['application/pdf', 'image/jpeg', 'image/jpg','image/png'];
        if (!in_array($paymentProof['type'], $allowedFileTypes) || $paymentProof['size'] > 5000000) {
            echo "
            <script type='text/javascript'>
                alert('Invalid file format or size. Please try again.');
                window.location.href ='../view-appointment.php';
            </script>";
            exit;
        }

        $uploadDir = '../../uploads/paymentProofs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . basename($paymentProof['name']);
        if (!move_uploaded_file($paymentProof['tmp_name'], $filePath)) {
            echo "
            <script type='text/javascript'>
                alert('Error uploading file. Please try again.');
                window.location.href ='../view-appointment.php';
            </script>";
            exit;
        }

        $paymentProofPath = $filePath; // Set proof path if successful
    } else {
        // If online, but no proof is given, use an empty string or null
        $paymentProofPath = "";
    }
} else {
    // If payment is cash, no proof required
    $paymentProofPath = null;
}

// Insert into payment table
$insertPaymentQuery = "
    INSERT INTO payment (apptID, date, time, amount, paymentMethod, paymentProofPath)
    VALUES (?, ?, ?, ?, ?, ?)
";
$insertStmt = $conn->prepare($insertPaymentQuery);
$insertStmt->bind_param('issdss', $appointment_id, $appointment_date, $appointment_time, $amount, $paymentMethod, $paymentProofPath);
$insertStmt->execute();
$insertStmt->close();

// Update appointment status to 'Paid'
$updateAppointmentQuery = "
    UPDATE appointment 
    SET status = 'Paid' 
    WHERE id = ?
";
$updateStmt = $conn->prepare($updateAppointmentQuery);
$updateStmt->bind_param('i', $appointment_id);
$updateStmt->execute();
$updateStmt->close();

echo "
<script type='text/javascript'>
    alert('Payment made successfully.');
    window.location.href ='../view-appointment.php';
</script>";
