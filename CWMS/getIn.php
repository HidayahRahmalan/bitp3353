<?php
session_start();
include("dbConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['pass'])) {
        $myusername = mysqli_real_escape_string($conn, $_POST['username']);
        $mypassword = mysqli_real_escape_string($conn, $_POST['pass']);

        // Prepare the SQL query
        $sql = "SELECT * FROM user WHERE username='$myusername' AND password='$mypassword'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $count = mysqli_num_rows($result);

            if ($count == 1) {
                $role = $row['role'];
                $uid = $row['id'];

                $_SESSION['login_user'] = $myusername;
                $_SESSION['role'] = $role;
                $_SESSION['uid'] = $uid;

                if ($role == 'Admin') {
                    echo "
                    <script type='text/javascript'>
                        window.location.href ='Admin/index.php';
                    </script>";
                } elseif ($role == 'Customer') {
                    echo "
                    <script type='text/javascript'>
                        window.location.href ='Customer/index.php';
                    </script>";
                }
            } else {
                echo "<script>alert('Incorrect username or password!');</script>";
                echo "
                <script type='text/javascript'>
            window.location.href ='index.php';
        </script>";
            }
        } else {
            echo "<script>alert('Database error. Please try again later.');</script>";
            echo "
            <script type='text/javascript'>
        window.location.href ='index.php';
    </script>";
        }
    } else {
        echo "<script>alert('Please provide both username and password.');</script>";
        echo "
        <script type='text/javascript'>
    window.location.href ='index.php';
</script>";
    }
}
?>
