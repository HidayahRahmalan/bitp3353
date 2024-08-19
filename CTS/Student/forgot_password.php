<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/img/transfer.png" rel="icon">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="assets/css/forgotpassword.css">
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form action="forgot_password_process.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
