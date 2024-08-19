<?php

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    include 'dbconnect.php';

    // Prepare the stored procedure call
    $qry = "CALL delete_event(?)";
    $stmt = mysqli_prepare($conn, $qry);

    if ($stmt) {
        // Bind the event_id parameter
        mysqli_stmt_bind_param($stmt, 'i', $event_id);

        // Execute the stored procedure
        if (mysqli_stmt_execute($stmt)) {
            echo "DELETED";
            header('Location: deleteevent.php');
        } else {
            echo "ERROR!!";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR!!";
    }

    // Close the connection
    mysqli_close($conn);
}
?>
