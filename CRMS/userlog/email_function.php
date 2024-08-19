<?php
include '../dbcon.php';

function getUserEmail($userId) {
    global $con;
    $query = "SELECT email FROM user WHERE user_id = '$userId'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['email'];
}

function sendEmail($to, $subject, $message) {
    // Use PHP's mail function or a library like PHPMailer
    // For simplicity, we're using mail function here
    // Ensure your server is configured to send emails
    mail($to, $subject, $message);
}

function onClothesSubmission($userId, $submissionId) {
    $userEmail = getUserEmail($userId);
    $subject = "Thank you for your clothes submission!";
    $message = "Dear User,\n\nThank you for submitting your clothes items for recycling. Your submission ID is $submissionId.\n\nBest regards,\nClothes Recycle Management Team";
    sendEmail($userEmail, $subject, $message);
}
?>
