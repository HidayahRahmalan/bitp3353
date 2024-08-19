<?php

if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    include 'dbconnect.php';

    // Prepare the stored procedure call
    $qry = "CALL delete_item(?)";
    $stmt = mysqli_prepare($conn, $qry);

    if ($stmt) {
        // Bind the item_id parameter
        mysqli_stmt_bind_param($stmt, 'i', $item_id);

        // Execute the stored procedure
        if (mysqli_stmt_execute($stmt)) {
            echo "DELETED";
            header('Location: deleteitem.php');
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
