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
$email = $_POST["email"];
$position = $_POST["position"];
$branch = $_POST["branch"];

$query = "INSERT INTO employee (name, email, position, branchID) VALUES ('$name', '$email', '$position', '$branch')";
$run_query = mysqli_query($conn, $query);
if ($run_query) {
    echo "
                  <script type='text/javascript'>
                  alert('Employee Successfully Added !');
              window.location.href ='../employee-list.php';
            </script>";
} else {
    echo "
          <script type='text/javascript'>
          alert('Something went wrong... Try again !');
          window.location.href ='../employee-list.php';
    </script>";
}
