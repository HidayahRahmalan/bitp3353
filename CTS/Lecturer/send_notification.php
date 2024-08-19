<?php
session_start(); // Start session if not already started
include('../include/connection.php');

// Assuming you have validated the student's login credentials and retrieved their details
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform login validation (check username and password)
    // Example:
    $query = "SELECT stud_id, name, email, notification_status FROM student WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($stud_id, $name, $email, $notification_status);
        $stmt->fetch();

        $_SESSION['stud_id'] = $stud_id;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['notification_status'] = $notification_status;

        // Check if there are pending notifications
        if ($notification_status == 1) {
            // Display notifications to the student
            echo "<script>alert('You have pending notifications. Please check your messages.');</script>";
            
            // Reset notification status to 0 (or mark notifications as read)
            $update_query = "UPDATE student SET notification_status = 0 WHERE stud_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("i", $stud_id);
            $update_stmt->execute();
            $update_stmt->close();
        }

        // Redirect to student dashboard or desired page
        header("Location: student_dashboard.php");
        exit();
    } else {
        // Handle invalid login credentials
        echo "<script>alert('Invalid username or password.');</script>";
        // Redirect to login page or display error message
        header("Location: login.php");
        exit();
    }

    $stmt->close();
}

// Close database connection
$conn->close();
?>
