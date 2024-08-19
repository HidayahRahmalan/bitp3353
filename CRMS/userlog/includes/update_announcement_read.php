<?php
include 'session.php';
include '../dbcon.php';

$user_id = $_GET['user_id'];

// Update announcements to mark them as read
$qry = "UPDATE announcement SET is_read = 1 WHERE user_id = $user_id AND is_read = 0";
if (mysqli_query($con, $qry)) {
    echo "success";
} else {
    echo "error";
}
?>
