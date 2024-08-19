<?php


$servername="localhost:3307";
$uname="root";
$pass="";
$db="crmsdb";

$conn=mysqli_connect($servername,$uname,$pass,$db);

if(!$conn){
    die("Connection Failed");
}

$sql = "SELECT * FROM pickup_session JOIN recycle_event 
ON pickup_session.event_id = recycle_event.event_id
JOIN pickup_lorry 
ON pickup_session.lorry_id = pickup_lorry.lorry_id
JOIN lorry_driver
ON pickup_session.lorry_id = lorry_driver.lorry_id                       
WHERE pickup_session.pickup_status = 'Already Pickup' AND lorry_driver.lorry_id = $session_id";
                $query = $conn->query($sql);

                echo "<h1>".$query->num_rows."</h1>";
?>