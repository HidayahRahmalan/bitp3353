<?php
include('../dbcon.php'); // Include your database connection script

$submitted = false;
$error = false;
$error_message = '';

if (isset($_POST['submit'])) {
    $name = $_POST["name"];
    $phone_num = $_POST["phone_num"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Encrypt the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if the username already exists
    $check_query = "SELECT * FROM `user` WHERE username = '$username'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Username already exists
        $error_message = 'Username already taken. Please choose a different username.';
        $error = true;
    } else {
        // Attempt to execute the query
        try {
            $qry = "INSERT INTO `user` (name, phone_num, address, email, username, password) VALUES ('$name', '$phone_num', '$address', '$email', '$username', '$hashed_password')";
            $result = mysqli_query($con, $qry); // Execute the query

            if (!$result) {
                throw new Exception(mysqli_error($con));
            }

            // Successful submission
            $submitted = true;
        } catch (mysqli_sql_exception $e) {
            // Check if the error message indicates the phone number already exists
            $sql_error = $e->getMessage();

            if (strpos($sql_error, 'Phone number already registered') !== false) {
                // Phone number already exists error handling
                echo "<script>alert('Phone number already registered. Please use a different phone number.');</script>";
            } else {
                // Other database error handling
                $error_message = $sql_error;
                $error = true;
            }
        } catch (Exception $e) {
            // Other generic exception handling
            $error_message = $e->getMessage();
            $error = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration - CRMS</title>
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
        alert("Submitted Successfully!");
        window.location.href = "userlogin.php"; // Redirect after showing the message
    </script>
<?php elseif ($error): ?>
    <script>
        alert("Error occurred while submitting the form: <?php echo $error_message; ?>");
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
                    <input type="text" name="name" class="form-control input_user" value="" placeholder="FullName" required>
                </div>
                <div class="input-group mb-3">
                    <input type="text" name="phone_num" class="form-control input_user" value="" placeholder="Phone Number" minlength="10" maxlength="11" required>
                </div>
                <div class="input-group mb-3">
                    <input type="text" name="address" class="form-control input_user" value="" placeholder="Address" required>
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control input_user" value="" placeholder="Email" required>
                </div>
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control input_user" value="" placeholder="Username" required>
                </div>
                <div class="input-group mb-2">
                    <input type="password" name="password" class="form-control input_pass" value="" placeholder="Password" required>
                </div>
                <div class="d-flex justify-content-center mt-3 login_container">
                    <button type="submit" name="submit" class="btn btn-primary login_btn">Create Account</button>
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
