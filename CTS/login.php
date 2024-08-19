<?php
error_reporting();
include('include/connection.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/img/transfer.png" rel="icon">
    <title>Login Page</title>
    <link rel="stylesheet" href="assets/css/loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <h2>Login to FTMK CTS</h2>
        <form action="" method="post">
            <div class="input-group">
            <label for="role">Choose Role</label>
                <select required name="userType">
                     <option value="">--Select User Roles--</option>
                     <option value="admin">Administrator</option>
                     <option value="lecturer">Lecturer</option>
                     <option value="student">Student</option>
                  </select>
             </div>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                    <i class="eye-icon fa fa-eye" onclick="togglePassword()"></i>
                </div>
            </div>
            <div class="forgot-password-link">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>
            <button type="submit" class="btn" name="login">Login</button>
        </form>

        <?php
        if(isset($_POST['login'])){

            $userType = $_POST['userType'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password = md5($password);
        
            if($userType == "admin"){
        
              $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
              $rs = $conn->query($query);
              $num = $rs->num_rows;
              $rows = $rs->fetch_assoc();
        
              if($num > 0){
        
                $_SESSION['admin_id'] = $rows['admin_id'];
                $_SESSION['name'] = $rows['name'];
                $_SESSION['username'] = $rows['username'];
        
                echo "<script type = \"text/javascript\">
                window.location = (\"Admin/index.php\")
                </script>";
              }
        
              else{
        
                echo "<div class='alert alert-danger' role='alert'>
                Invalid Username/Password!
                </div>";
        
              }
            }
            else if($userType == "lecturer"){
        
              $query = "SELECT * FROM lecturer WHERE username = '$username' AND password = '$password'";
              $rs = $conn->query($query);
              $num = $rs->num_rows;
              $rows = $rs->fetch_assoc();
        
              if($num > 0){
        
                $_SESSION['lect_id'] = $rows['lect_id'];
                $_SESSION['lect_name'] = $rows['lect_name'];
                $_SESSION['phoneno'] = $rows['phoneno'];
                $_SESSION['email'] = $rows['email'];
                $_SESSION['username'] = $rows['username'];

        
                echo "<script type = \"text/javascript\">
                window.location = (\"Lecturer/index.php\")
                </script>";
              }
        
              else{
        
                echo "<div class='alert alert-danger' role='alert'>
                Invalid Username/Password!
                </div>";
        
              }
            }

            else if($userType == "student"){
        
                $query = "SELECT * FROM student WHERE username = '$username' AND password = '$password'";
                $rs = $conn->query($query);
                $num = $rs->num_rows;
                $rows = $rs->fetch_assoc();
          
                if($num > 0){
          
                  $_SESSION['stud_id'] = $rows['stud_id'];
                  $_SESSION['name'] = $rows['name'];
                  $_SESSION['icno'] = $rows['icno'];
                  $_SESSION['username'] = $rows['username'];
                  $_SESSION['phone'] = $rows['phone'];
                  $_SESSION['email'] = $rows['email'];
          
                  echo "<script type = \"text/javascript\">
                  window.location = (\"Student/index.php\")
                  </script>";
                }
          
                else{
          
                  echo "<div class='alert alert-danger' role='alert'>
                  Invalid Username/Password!
                  </div>";
          
                }
              }

            else{
        
                echo "<div class='alert alert-danger' role='alert'>
                Invalid Username/Password!
                </div>";
        
            }
        }
        ?>
        
        <div class="create-account-link">
            <p>User Guideline For CTS <a href="user-guideline.pdf" target="_blank">Read Me</a></p>
            <p> <a href="index.php">Back To Homepage</a></p>

        </div>
    </div>

    <script>
        function togglePassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
                document.querySelector(".eye-icon").classList.remove("fa-eye");
                document.querySelector(".eye-icon").classList.add("fa-eye-slash");
            } else {
                x.type = "password";
                document.querySelector(".eye-icon").classList.remove("fa-eye-slash");
                document.querySelector(".eye-icon").classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
