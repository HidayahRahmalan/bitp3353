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

$id = $_POST['id']; // The ID of the vehicle to update
$plateNo = strtoupper($_POST['plateNo']);
$model = strtoupper($_POST['model']);
$color = strtoupper($_POST['color']);

// Check if a new image has been uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // A new image is uploaded
    $image = $_FILES['image']['tmp_name'];
    $imageData = file_get_contents($image);

    // Update query with image
    $query = "UPDATE vehicle SET plateNo = ?, model = ?, color = ?, image = ? WHERE id = ? AND custID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssi', $plateNo, $model, $color, $imageData, $id, $custID);
    $stmt->send_long_data(3, $imageData); // Sending the binary image data
} else {
    // No new image uploaded, update other fields only
    $query = "UPDATE vehicle SET plateNo = ?, model = ?, color = ? WHERE id = ? AND custID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssii', $plateNo, $model, $color, $id, $custID);
}

if ($stmt->execute()) {
    echo "
    <script type='text/javascript'>
        alert('Vehicle Successfully Updated!');
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
