<?php
include 'session.php';
include 'dbcon.php';

if (isset($_GET['donate_id'])) {
    $donate_id = $_GET['donate_id'];

    // Query to delete the item from the donate_item table
    $qry = "DELETE FROM donate_item WHERE donate_id = ?";
    
    // Prepare and execute the statement
    if ($stmt = mysqli_prepare($con, $qry)) {
        mysqli_stmt_bind_param($stmt, "i", $donate_id);

        if (mysqli_stmt_execute($stmt)) {
            // Success message with JavaScript alert and redirection
            echo '<script>
                    alert("Successfully deleted.");
                    window.location.href = "viewdonate.php";
                  </script>';
        } else {
            // Error message with JavaScript alert and redirection
            echo '<script>
                    alert("Something went wrong. Please try again.");
                    window.location.href = "viewdonate.php";
                  </script>';
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Failed to prepare the delete statement.";
    }
} else {
    $_SESSION['error'] = "No item ID provided for deletion.";
    // Redirect to user dashboard in case of error
    echo '<script>
            window.location.href = "userdashboard.php";
          </script>';
}
?>
