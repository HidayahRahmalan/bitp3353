<?php

include('../../dbConnect.php');

$id = $_GET['id'];

    $sql2 = "DELETE FROM feedback WHERE id='$id'";
    $run_query2 = mysqli_query($conn, $sql2);
        if ($conn->query($sql2) === TRUE) {
                echo "<script>alert('Delete Successful')</script>";
                echo "
                <script type='text/javascript'>
                   window.location.href ='../feedback-list.php';
                </script>";

        } else {
            echo "<script>alert('Something went wrong... Try Again !')</script>";
            echo "
            <script type='text/javascript'>
               window.location.href ='../feedback-list.php';
            </script>";
        }

?>
