<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('car-wash-login.jpg'); 
            background-size: cover;
            background-position: center;
            /* filter: blur(4px); */
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .login-container input[type="submit"] {
            width: 90%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .register {
            margin-top: 20px;
            display: block;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        /* .cwms-logo {
            margin-bottom: 10px;
        } */
    </style>
</head>

<body>
    <div class="login-container">
        <div class="cwms-logo">
            <img src="cwms-logo.png" alt="CWMS Logo" width="200">
        </div>
        <h2>Login</h2>
        <form action="getIn.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <br>
            <input type="password" name="pass" placeholder="Password" required>
            <br>
            <input type="submit" value="Login">
        </form>
        <a class="register" href="register.html">Haven't Registered?</a>
    </div>
</body>

</html>
