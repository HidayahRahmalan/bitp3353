<?php
include "dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the redeem ID and the selected status from the form
    $redeem_id = $_POST['redeem_id'];
    $approve_status = $_POST['approve_status'];

    // Update the approve_status in the database
    $sql = "UPDATE point_redeem SET approve_status = ? WHERE redeem_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $approve_status, $redeem_id);

    if ($stmt->execute()) {
        echo '<script>alert("Status updated successfully."); window.location.href = "viewredeem.php";</script>';
    } else {
        echo '<script>alert("Failed to update status. Please try again."); window.location.href = "viewredeem.php";</script>';
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if the request method is not POST
    header("Location: viewredeem.php");
    exit();
}
?>
