<?php

$servername="localhost:3307";
$uname="root";
$pass="";
$db="crmsdb";

$conn=mysqli_connect($servername,$uname,$pass,$db);

if(!$conn){
    die("Connection Failed");
}

$sql = "SELECT * FROM donate_item join item_category 
ON donate_item.item_id = item_category.item_id WHERE item_category.item_category = 'Men'";
                $query = $conn->query($sql);

                echo "<h1>".$query->num_rows."</h1>";
?>