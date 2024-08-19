<?php
// Include database connection file
include_once 'include/connection.php'; // This file should contain your database connection details

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $newPassword = md5($_POST['new_password']); // Hash the new password using MD5
    $confirmPassword = md5($_POST['confirm_password']); // Hash the confirm password using MD5

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        echo "Error: New password and confirm password do not match.";
        exit;
    }

    // Check if the user exists in the student table
    $sqlStudent = "SELECT * FROM student WHERE email = ? AND username = ?";
    $stmtStudent = $conn->prepare($sqlStudent);
    $stmtStudent->bind_param("ss", $email, $username);
    $stmtStudent->execute();
    $resultStudent = $stmtStudent->get_result();

    // Check if the user exists in the staff table
    $sqlStaff = "SELECT * FROM lecturer WHERE email = ? AND username = ?";
    $stmtStaff = $conn->prepare($sqlStaff);
    $stmtStaff->bind_param("ss", $email, $username);
    $stmtStaff->execute();
    $resultStaff = $stmtStaff->get_result();

    if ($resultStudent->num_rows == 1) {
        // Update the password in the student table
        $updateSql = "UPDATE student SET password = ? WHERE email = ? AND username = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sss", $newPassword, $email, $username);

        if ($updateStmt->execute()) {
            echo '<script>alert("Password updated successfully!");</script>';
        } else {
            echo "Error updating password: " . $conn->error;
        }

        // Close statement and database connection
        $updateStmt->close();
    } elseif ($resultStaff->num_rows == 1) {
        // Update the password in the staff table
        $updateSql = "UPDATE lecturer SET password = ? WHERE email = ? AND username = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sss", $newPassword, $email, $username);

        if ($updateStmt->execute()) {
            echo '<script>alert("Password updated successfully!");</script>';
        } else {
            echo "Error updating password: " . $conn->error;
        }

        // Close statement and database connection
        $updateStmt->close();
    } else {
        echo "User not found.";
    }

    // Close statement and database connection
    $stmtStudent->close();
    $stmtStaff->close();
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/img/transfer.png" rel="icon">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Reset default browser styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            /* background: url("../assets/img/ftmk7.jpg") no-repeat center center fixed; */
            /* background-size: cover; Ensure the background image covers the entire page */ 
            background-color: #ccc;
        }

        /* Container styles */
        .container {   
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Heading styles */
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Option buttons container */
        .option-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        /* Option button styles */
        button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        button:hover {
            background-color: #ddd;
        }

        /* Form container styles */
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Form styles */
        form {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            display: none; /* Initially hidden */
        }

        /* Show form when active */
        form.active {
            display: block;
        }

        /* Form group styles */
        .form-group {
            margin-bottom: 20px;
        }

        /* Label styles */
        label {
            margin-bottom: 5px;
            display: block;
        }

        /* Input styles */
        input[type="email"],
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-group .eye-icon {
            position: static;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* Button styles */
        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Forgot Password</h2>
         <!-- Success message container -->
         <div id="success-message" style="display: none; color: green; margin-bottom: 15px;">
            Password updated successfully!
        </div>

        <!-- Back to Login Page button -->
        <div class="option-buttons">
            <button id="student-btn" onclick="showStudentForm()">
                <i class="fas fa-user-graduate"></i> Student
            </button>
            <button id="staff-btn" onclick="showStaffForm()">
                <i class="fas fa-chalkboard-teacher"></i> Staff
            </button>
            <button onclick="redirectToLoginPage()">
                <i class="fas fa-home"></i> Back to Login Page
            </button>
        </div>
        <div class="form-container">
            <form id="student-form" action="forgot-password.php" method="POST" class="active">
                <div class="form-group">
                    <label for="student-email">Email:</label>
                    <input type="email" id="student-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="student-username">Matric No:</label>
                    <input type="text" id="student-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="student-new-password">New Password:</label>
                    <input type="password" id="student-new-password" name="new_password" required>
                    <span class="eye-icon" onclick="togglePasswordVisibility('student-new-password')">
                        👁️
                    </span>
                </div>
                <div class="form-group">
                    <label for="student-confirm-password">Confirm Password:</label>
                    <input type="password" id="student-confirm-password" name="confirm_password" required>
                    <span class="eye-icon" onclick="togglePasswordVisibility('student-confirm-password')">
                        👁️
                    </span>
                </div>
                <button type="submit">Reset Password</button>
            </form>
            <form id="staff-form" action="forgot-password.php" method="POST">
                <div class="form-group">
                    <label for="staff-email">Email:</label>
                    <input type="email" id="staff-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="staff-username">Staff ID:</label>
                    <input type="text" id="staff-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="staff-new-password">New Password:</label>
                    <input type="password" id="staff-new-password" name="new_password" required>
                    <span class="eye-icon" onclick="togglePasswordVisibility('staff-new-password')">
                        👁️
                    </span>
                </div>
                <div class="form-group">
                    <label for="staff-confirm-password">Confirm Password:</label>
                    <input type="password" id="staff-confirm-password" name="confirm_password" required>
                    <span class="eye-icon" onclick="togglePasswordVisibility('staff-confirm-password')">
                        👁️
                    </span>
                </div>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
    <script>
        // JavaScript to toggle between Student and Staff forms
        function showStudentForm() {
            document.getElementById('student-form').classList.add('active');
            document.getElementById('staff-form').classList.remove('active');
        }

        function showStaffForm() {
            document.getElementById('student-form').classList.remove('active');
            document.getElementById('staff-form').classList.add('active');
        }

        // JavaScript to toggle password visibility
        function togglePasswordVisibility(inputId) {
            var input = document.getElementById(inputId);
            var eyeIcon = input.nextElementSibling;

            if (input.type === "password") {
                input.type = "text";
                eyeIcon.textContent = "👁️";
            } else {
                input.type = "password";
                eyeIcon.textContent = "🔒";
            }
        }

        // Function to redirect to login page
        function redirectToLoginPage() {
            window.location.href = "login.php"; // Change "login.php" to the actual URL of your login page
        }
    </script>
</body>

</html>
