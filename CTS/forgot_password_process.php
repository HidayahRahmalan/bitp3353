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
            echo "Password updated successfully!";
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
            echo "Password updated successfully!";
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
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .option-buttons {
            margin-bottom: 20px;
        }

        .option-buttons button {
            margin-right: 10px;
            cursor: pointer;
            padding: 5px 10px;
        }

        .form-container {
            border: 1px solid #ccc;
            padding: 20px;
        }

        .form-container form {
            display: none;
        }

        .form-container form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="email"],
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: calc(100% - 30px);
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .form-group input[type="password"] {
            position: relative;
        }

        .form-group .eye-icon {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .form-group button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <div class="option-buttons">
            <button id="student-btn" onclick="showStudentForm()">Student</button>
            <button id="staff-btn" onclick="showStaffForm()">Staff</button>
        </div>
        <div class="form-container">
            <form id="student-form" action="forgot_password_process.php" method="POST" class="active">
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
            <form id="staff-form" action="forgot_password_process.php" method="POST">
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
    </script>
</body>
</html>
