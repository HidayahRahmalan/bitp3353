<?php
session_start();
if (!$_SESSION["login_user"]) {
    echo "
    <script type='text/javascript'>
window.location.href ='../../index.php';
</script>";
}
$login_session = $_SESSION['login_user'];
include_once("../../dbConnect.php");


$name = strtoupper($_POST["name"]);
$location = strtoupper($_POST["location"]);
$phone = $_POST["phone"];

$query = "CALL addbranch ('$name', '$location', '$phone')";
$run_query = mysqli_query($conn, $query);
if ($run_query) {
    echo "
                  <script type='text/javascript'>
                  alert('Branch Successfully Added !');
              window.location.href ='../branch-list.php';
            </script>";
} else {
    echo "
          <script type='text/javascript'>
          alert('Something went wrong... Try again !');
          window.location.href ='../branch-list.php';
    </script>";
}
