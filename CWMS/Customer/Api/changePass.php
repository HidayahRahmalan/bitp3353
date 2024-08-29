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

// $user_id=$_POST["user_id"];
$email=$login_session;
$pass1=$_POST["pass1"];
$pass2=$_POST["pass2"];
$pass3=$_POST["pass3"];



// echo $pass3.$pass2.$pass1;

$query = "SELECT * from user WHERE id='$uid'";
$result = $conn->query($query);

$modal = 0;
if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
    $password = $row['password'];
    
    if ($pass1 !=$password){
        echo "<script>alert('Incorrect Current Password')</script>" ;
    }
    else{
        if($pass2!=$pass3){
            echo "<script>alert('The Password Does Not Match')</script>" ;
        }
        else{
            $sql = "UPDATE user SET password='$pass2' WHERE id='$uid'";
            if ($conn->query($sql) === TRUE){
                echo "<script>alert('Password Successfully Changed')</script>" ;
            }
            else{
            echo "<script>alert('Something went wrong.. Try Again !')</script>" ;
            }
            echo "
    <script type='text/javascript'>
window.location.href ='../profile.php';
</script>";
          
        }

    }
    echo "
    <script type='text/javascript'>
window.location.href ='../profile.php';
</script>";
}
}


?>