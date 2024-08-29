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
$employee_ids = $_POST['employee_ids'] ?? [];

if ($appointment_id && !empty($employee_ids)) {
    $appointmentQuery = "
        SELECT 
            date, start_time 
        FROM 
            appointment 
        WHERE 
            id = ?
    ";
    $appointmentStmt = $conn->prepare($appointmentQuery);
    $appointmentStmt->bind_param('i', $appointment_id);
    $appointmentStmt->execute();
    $appointmentStmt->bind_result($appointment_date, $appointment_time);
    $appointmentStmt->fetch();
    $appointmentStmt->close();

    $clearQuery = "
        DELETE FROM appointmentEmp 
        WHERE apptID = ?
    ";
    $clearStmt = $conn->prepare($clearQuery);
    $clearStmt->bind_param('i', $appointment_id);
    $clearStmt->execute();
    $clearStmt->close();

    foreach ($employee_ids as $emp_id) {
        $insertQuery = "
            INSERT INTO appointmentEmp (apptID, empID, date, time) 
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('iiss', $appointment_id, $emp_id, $appointment_date, $appointment_time);
        $stmt->execute();
        $stmt->close();

        $updateEmployeeAvailabilityQuery = "
            UPDATE employee
            SET isAvailable = 0
            WHERE id = ?
        ";
        $updateStmt = $conn->prepare($updateEmployeeAvailabilityQuery);
        $updateStmt->bind_param('i', $emp_id);
        $updateStmt->execute();
        $updateStmt->close();
    }

    echo "
    <script type='text/javascript'>
        alert('Employees assigned successfully.');
        window.location.href ='../view-appointment.php';
    </script>";
} else {
    echo "
    <script type='text/javascript'>
        alert('Invalid data. Please try again.');
        window.location.href ='../view-appointment.php';
    </script>";
}
?>
