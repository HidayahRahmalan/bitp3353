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
$email = $_POST["email"];
$position = $_POST["position"];
$branch = $_POST["branch"];
$id = $_POST["id"];
$isAvailable = isset($_POST["isAvailable"]) ? 1 : 0;


$sql = "UPDATE employee SET name='$name', email='$email',position='$position', branchID='$branch', isAvailable='$isAvailable'
            WHERE id='$id'";

if ($conn->query($sql) === TRUE){
  echo "<script>alert('Update Successful !')</script>" ;
        echo "
        <script type='text/javascript'>
    window.location.href ='../employee-list.php';
  </script>";
}
else{
echo "<script>alert('Something went wrong... Try Again !')</script>" ;
        echo "
        <script type='text/javascript'>
    window.location.href ='../employee-list.php';
  </script>";
}
