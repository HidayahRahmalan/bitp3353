<?php
session_start();
if (!$_SESSION["login_user"]) {
  echo "
    <script type='text/javascript'>
window.location.href ='../../index.php';
</script>";
}
$login_session = $_SESSION['login_user'];
$uid = $_SESSION['uid'];
include_once("../../dbConnect.php");

$name = strtoupper($_POST["name"]);
$location = strtoupper($_POST["location"]);
$phone = $_POST["phone"];
$id = $_POST["id"];
$isOpen = isset($_POST["isOpen"]) ? 1 : 0;


$sql = "UPDATE branch SET name='$name', location='$location',phone='$phone', isOpen='$isOpen'
            WHERE id='$id'";

if ($conn->query($sql) === TRUE){
  echo "<script>alert('Update Successful !')</script>" ;
        echo "
        <script type='text/javascript'>
    window.location.href ='../branch-list.php';
  </script>";
}
else{
echo "<script>alert('Something went wrong... Try Again !')</script>" ;
        echo "
        <script type='text/javascript'>
    window.location.href ='../branch-list.php';
  </script>";
}
