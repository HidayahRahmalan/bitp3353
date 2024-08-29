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
$description = strtoupper($_POST["description"]);
$price = $_POST["price"];
$duration = $_POST["duration"];
$isFeatured = isset($_POST["isFeatured"]) ? 1 : 0;

$query = "INSERT INTO washPackage (name, description, price, duration, isFeatured) VALUES ('$name', '$description', '$price', '$duration', '$isFeatured')";
$run_query = mysqli_query($conn, $query);
if ($run_query) {
    echo "
                  <script type='text/javascript'>
                  alert('Package Successfully Added !');
              window.location.href ='../package-list.php';
            </script>";
} else {
    echo "
          <script type='text/javascript'>
          alert('Something went wrong... Try again !');
          window.location.href ='../package-list.php';
    </script>";
}
