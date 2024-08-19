<?php

if(isset($_GET['lorry_id'])){
$event_id=$_GET['lorry_id'];

include 'dbconnect.php';


$qry="delete from pickup_lorry where lorry_id=$lorry_id";
$result=mysqli_query($conn,$qry);

if($result){
    echo"DELETED";
    header('Location:deletelorry.php');
}else{
    echo"ERROR!!";
}
}
?>