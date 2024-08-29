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
$description = strtoupper($_POST["description"]);
$price = $_POST["price"];
$duration = $_POST["duration"];
$isFeatured = isset($_POST["isFeatured"]) ? 1 : 0;
$id = $_POST["id"];


$sql = "UPDATE washPackage SET name='$name', description='$description',price='$price', duration='$duration', isFeatured='$isFeatured'
            WHERE id='$id'";

if ($conn->query($sql) === TRUE){
  echo "<script>alert('Update Successful !')</script>" ;
        echo "
        <script type='text/javascript'>
    window.location.href ='../package-list.php';
  </script>";
}
else{
echo "<script>alert('Something went wrong... Try Again !')</script>" ;
        echo "
        <script type='text/javascript'>
    window.location.href ='../package-list.php';
  </script>";
}
