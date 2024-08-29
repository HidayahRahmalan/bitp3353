<?php

include_once("dbConnect.php");

$fullname = $_POST["fullname"];
$username = $_POST["username"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$pass = $_POST["pass"];
$role = "Customer";

$check_query = "SELECT * FROM user WHERE username='$username'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    echo "
    <script type='text/javascript'>
        alert('Username has been taken. Please enter another username.');
        window.location.href ='index.php';
    </script>";
} else {
    $query = "INSERT INTO user (username, password, role) VALUES ('$username', '$pass', '$role')";
    $run_query = mysqli_query($conn, $query);

    if ($run_query) {
        $sql = "SELECT * FROM user WHERE username='$username'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $userId = $row['id'];

        $query1 = "INSERT INTO profile (user_id, fullname, phone, email) VALUES ('$userId', '$fullname', '$phone', '$email')";
        $run_query1 = mysqli_query($conn, $query1);

        if ($run_query1) {
            echo "
            <script type='text/javascript'>
                alert('Registration Successful!');
                window.location.href ='index.php';
            </script>";
        } else {
            echo "
            <script type='text/javascript'>
                alert('Registration Error!');
                window.location.href ='index.php';
            </script>";
        }
    } else {
        echo "
        <script type='text/javascript'>
            alert('Registration Failed');
            window.location.href ='index.php';
        </script>";
    }
}
