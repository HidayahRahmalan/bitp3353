<?php
include '../Includes/dbcon.php';

if (isset($_POST['ClubTypeID'])) {
    $clubTypeID = $_POST['ClubTypeID'];
    $qry = "SELECT * FROM club WHERE ClubTypeID = '$clubTypeID' ORDER BY ClubName ASC";
    $result = mysqli_query($conn, $qry);
    if (mysqli_num_rows($result) > 0) {
        echo '<option value="">--Select Club--</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="'.$row['ClubID'].'">'.$row['ClubName'].'</option>';
        }
    } else {
        echo '<option value="">No clubs available</option>';
    }
}

if (isset($_POST['ClubID'])) {
    $clubID = $_POST['ClubID'];
    $qry = "SELECT * FROM activity WHERE ClubID = '$clubID' ORDER BY ActivityName ASC";
    $result = mysqli_query($conn, $qry);
    if (mysqli_num_rows($result) > 0) {
        echo '<option value="">--Select Activity--</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="'.$row['ActivityID'].'">'.$row['ActivityName'].'</option>';
        }
    } else {
        echo '<option value="">No activities available</option>';
    }
}
?>
