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

$login_session = $_SESSION['login_user'];
$uid = $_POST['custID'];
$washPackID = $_POST['washPackID'];
$branchID = $_POST['branchID'];
$appointmentDate = $_POST['appointmentDate'];
$appointmentStart = $_POST['appointmentTime'];
$plateNo = strtoupper($_POST['plateNo']);
$model = strtoupper($_POST['model']);
$color = strtoupper($_POST['color']);

$query = "SELECT duration FROM washPackage WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $washPackID);
$stmt->execute();
$stmt->bind_result($duration);
$stmt->fetch();
$stmt->close();

$durationParts = explode(':', $duration);
$durationMinutes = ($durationParts[0] * 60) + $durationParts[1];
$appointmentEnd = date('H:i', strtotime("+$durationMinutes minutes", strtotime($appointmentStart)));

$checkConflictQuery = "
    SELECT * 
    FROM appointment 
    WHERE branchID = ? 
        AND date = ? 
        AND (
            (? < end_time AND ? > start_time)
        )
        AND status IN ('Pending', 'Accepted', 'Paid')
";

$stmt = $conn->prepare($checkConflictQuery);
$stmt->bind_param('isss', $branchID, $appointmentDate, $appointmentStart, $appointmentEnd);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "
    <script type='text/javascript'>
        alert('Appointment conflict: the selected time slot is already booked. Please choose a different time or another branch.');
        window.location.href ='../view-appointment.php';
    </script>";
    exit;
}

$stmt->close();

$insertVehicleQuery = "
    INSERT INTO vehicle (plateNo, model, color) 
    VALUES (?, ?, ?)
";
$vehicleStmt = $conn->prepare($insertVehicleQuery);
$vehicleStmt->bind_param('sss', $plateNo, $model, $color);
$vehicleStmt->execute();
$vehicleID = $vehicleStmt->insert_id;

$insertAppointmentQuery = "
    INSERT INTO appointment (vehicleID, washPackID, branchID, date, start_time, end_time, status)
    VALUES (?, ?, ?, ?, ?, ?, 'Pending')
";

$stmt = $conn->prepare($insertAppointmentQuery);
$stmt->bind_param('iiisss', $vehicleID, $washPackID, $branchID, $appointmentDate, $appointmentStart, $appointmentEnd);
$stmt->execute();

$appointmentID = $stmt->insert_id;

$insertCustomerQuery = "
    INSERT INTO customer (userID, apptID)
    VALUES (?, ?)
";
$customerStmt = $conn->prepare($insertCustomerQuery);
$customerStmt->bind_param('ii', $uid, $appointmentID);
$customerStmt->execute();

$custID = $customerStmt->insert_id;

$updateAppointmentQuery = "
    UPDATE appointment 
    SET custID = ? 
    WHERE id = ?
";
$updateStmt = $conn->prepare($updateAppointmentQuery);
$updateStmt->bind_param('ii', $custID, $appointmentID);
$updateStmt->execute();

$updateVehicleQuery = "
    UPDATE vehicle 
    SET custID = ? 
    WHERE id = ?
";
$updateStmt = $conn->prepare($updateVehicleQuery);
$updateStmt->bind_param('ii', $custID, $vehicleID);
$updateStmt->execute();

echo "
    <script type='text/javascript'>
        alert('Appointment booked successfully!');
        window.location.href ='../view-appointment.php';
    </script>";
