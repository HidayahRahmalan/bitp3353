<?php

$servername="localhost:3307";
$uname="root";
$pass="";
$db="crmsdb";

$conn=mysqli_connect($servername,$uname,$pass,$db);

if(!$conn){
    die("Connection Failed");
}

?>