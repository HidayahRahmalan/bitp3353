<?php
include('include/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in the database
    $check_query = "SELECT * FROM student WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Generate a new password
        $new_password = generateRandomPassword();
        // Hash the new password using MD5
        $hashed_password = md5($new_password);

        // Update user's password in the database
        $update_query = "UPDATE student SET password = '$hashed_password' WHERE email = '$email'";
        mysqli_query($conn, $update_query);

        // Display success message
        echo "Password reset successful. Check your email for the new password.";
    } else {
        // Display error message if email doesn't exist in the database
        echo "Email not found. Please enter a valid email address.";
    }
}

function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    $max = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[mt_rand(0, $max)];
    }
    return $password;
}
?>