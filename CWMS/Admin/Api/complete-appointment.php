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
        SET status = 'Completed'
        WHERE id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $appointment_id);
    $stmt->execute();
    $stmt->close();

    $getEmployeesQuery = "
        SELECT empID 
        FROM appointmentEmp 
        WHERE apptID = ?
    ";
    $getEmployeesStmt = $conn->prepare($getEmployeesQuery);
    $getEmployeesStmt->bind_param('i', $appointment_id);
    $getEmployeesStmt->execute();
    $result = $getEmployeesStmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $emp_id = $row['empID'];

        $updateEmployeeQuery = "
            UPDATE employee 
            SET isAvailable = 1 
            WHERE id = ?
        ";
        $updateStmt = $conn->prepare($updateEmployeeQuery);
        $updateStmt->bind_param('i', $emp_id);
        $updateStmt->execute();
        $updateStmt->close();
    }

    $getEmployeesStmt->close();

    echo "
    <script type='text/javascript'>
        alert('Appointment is completed and employees are now available.');
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
