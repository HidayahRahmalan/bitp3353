<?php

$servername = "localhost:3307";
$uname = "root";
$pass = "";
$db = "crmsdb";

$conn = mysqli_connect($servername, $uname, $pass, $db);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Assuming $session_id is retrieved from session or defined earlier


$sql = "SELECT SUM(item_point) AS total_points FROM donate_item WHERE point_redeem = 'Yes' and user_id = $session_id";
$query = $conn->query($sql);

if ($query) {
    $result = $query->fetch_assoc();
    $total_points = $result['total_points'];
    echo "<h1>" . ($total_points ?? 0) . "</h1>";
} else {
    echo "<h1>0</h1>";
}
?>
