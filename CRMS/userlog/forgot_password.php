
<!---->
<?php
include('dbcon.php'); // Include your database connection script

$submitted = false;
$error = false;
$error_message = '';

if (isset($_POST['submit'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["cpassword"];

    // Check if the username exists
    $check_query = "SELECT * FROM `user` WHERE username = '$username'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Username exists, check if passwords match
        if ($password === $confirm_password) {
            // Encrypt the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Update the password in the database
            $qry = "UPDATE `user` SET password='$hashed_password' WHERE username='$username'";
            $result = mysqli_query($con, $qry);

            if ($result) {
                // Successful password reset
                $submitted = true;
            } else {
                $error_message = mysqli_error($con);
                $error = true;
            }
        } else {
            $error_message = 'Passwords do not match. Please try again.';
            $error = true;
        }
    } else {
        // Username does not exist
        $error_message = 'Username does not exist. Please try again.';
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="userstyles.css">
    <link href="img/clothes-donation.png" rel="icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <style>
        .container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .user_card {
            width: 350px;
            padding: 30px;
            background: #B2B2B2;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .brand_logo_container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            background: #B2B2B2;
        }
        .brand_logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
        .form_container {
            display: flex;
            flex-direction: column;
        }
        .input_user, .input_pass {
            border-radius: 0;
        }
        .login_container {
            margin-top: 20px;
        }
        .login_btn {
            width: 100%;
            border-radius: 0;
        }
        .links {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<?php if ($submitted): ?>
    <script>
        alert("Password reset successfully!");
        window.location.href = "userlogin.php"; // Redirect after showing the message
    </script>
<?php elseif ($error): ?>
    <script>
        alert("<?php echo $error_message; ?>");
    </script>
<?php endif; ?>

<div class="container">
    <div class="user_card">
        <div class="brand_logo_container">
            <img src="img/icon.jpg" class="brand_logo" alt="Logo">
        </div>
        <div class="form_container">
            <form action="#" method="post">
            <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control input_user" value="" placeholder="Username" required>
                </div>
                <div class="input-group mb-2">
                    <input type="password" name="password" class="form-control input_pass" value="" placeholder="New Password" required>
                </div>
                <div class="input-group mb-2">
                    <input type="password" name="cpassword" class="form-control input_pass" value="" placeholder="Confirm Password" required>
                </div>
                <div class="d-flex justify-content-center mt-3 login_container">
                    <button type="submit" name="submit" class="btn btn-primary login_btn">Change Password</button>
                </div>
            </form>
        </div>
        <div class="links d-flex justify-content-center">
            <a href="userlogin.php" class="btn btn-primary" style="background-color: #c0392b; border-color: #c0392b;">Back to Login Page</a>
        </div>
    </div>
</div>

</body>
</html>
