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

$login_session = $_SESSION['login_user'];
$custID = $_SESSION['uid'];

$plateNo = strtoupper($_POST['plateNo']);
$model = strtoupper($_POST['model']);
$color = strtoupper($_POST['color']);
$image = $_FILES['image']['tmp_name'];

// Check for file upload errors
if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo "
    <script type='text/javascript'>
        alert('Error during file upload: " . $_FILES['image']['error'] . "');
        window.location.href ='../car-register.php';
    </script>";
    exit;
}

// Read the image file as binary data
$imageData = file_get_contents($image);

$query = "INSERT INTO vehicle (custID, plateNo, model, color, image) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('sssss', $custID, $plateNo, $model, $color, $imageData);

// Use send_long_data() to send the binary data
$stmt->send_long_data(4, $imageData);

if ($stmt->execute()) {
    echo "
    <script type='text/javascript'>
        alert('Vehicle Successfully Added!');
        window.location.href ='../car-register.php';
    </script>";
} else {
    echo "
    <script type='text/javascript'>
        alert('Something went wrong... Try again!');
        window.location.href ='../car-register.php';
    </script>";
}

$stmt->close();
$conn->close();
?>
