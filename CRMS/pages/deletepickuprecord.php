<?php

if(isset($_GET['event_id'])){
$event_id=$_GET['event_id'];

include 'dbconnect.php';


$qry="DELETE from pickup_session where event_id=$event_id";
$result=mysqli_query($conn,$qry);

if($result){
    echo"DELETED";
    header('Location:deletepickup.php');
}else{
    echo"ERROR!!";
}
}
?>