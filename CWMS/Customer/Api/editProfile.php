<?php
session_start();
if(!$_SESSION["login_user"]){
    echo "
    <script type='text/javascript'>
window.location.href ='../../index.php';
</script>";
 }
$login_session =$_SESSION['login_user'];
$uid =$_SESSION['uid'];
include_once("../../dbConnect.php");

$fullname=$_POST["fullname"];
$phone=$_POST["phone"];
$email=$_POST["email"];
            $sql = "UPDATE profile SET fullname='$fullname', phone='$phone', email='$email'
            WHERE user_id='$uid'";
            if ($conn->query($sql) === TRUE){
              echo "<script>alert('Update Successful')</script>" ;
                    echo "
                    <script type='text/javascript'>
                window.location.href ='../profile.php';
              </script>";
            }
            else{
            echo "<script>alert('Something went wrong... Try Again !')</script>" ;
                    echo "
                    <script type='text/javascript'>
                window.location.href ='../profile.php';
              </script>";
            }
     
   

?>