<!DOCTYPE html>
<html lang="en">

<head>
    <title>Driver - CRMS</title>
    <link href="../img/clothes-donation.png" rel="icon">
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../icofont/icofont.min.css">
</head>

<body>
    <div id="wrapper">
        <?php include 'includes/drivernav.php'?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">CRMS</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            MESSAGE BOX
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="#" method="post">
                                    <?php
                                    include '../dbcon.php';

                                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                        $lorry_id = $_POST["lorry_id"];
                                        $pickup_status = $_POST["pickup_status"];
                                        $pickup_date = $_POST["pickup_date"];
                                        $pickup_time = $_POST["pickup_time"];
                                        $dropoff_destination = $_POST["dropoff_destination"];
                                        $event_id = $_POST['event_id'];

                                        // Start a transaction
                                        mysqli_begin_transaction($con);

                                        try {
                                            // Update pickup session
                                            $qry = "UPDATE pickup_session SET lorry_id=?, pickup_status=?, pickup_date=?, pickup_time=?, dropoff_destination=? WHERE event_id=?";
                                            $stmt = mysqli_prepare($con, $qry);
                                            mysqli_stmt_bind_param($stmt, 'issssi', $lorry_id, $pickup_status, $pickup_date, $pickup_time, $dropoff_destination, $event_id);
                                            $result = mysqli_stmt_execute($stmt);

                                            if (!$result) {
                                                throw new Exception(mysqli_stmt_error($stmt));
                                            }

                                            // Get the user_id associated with the event_id
                                            $user_qry = "SELECT donate_item.user_id, recycle_event.event_name FROM donate_item JOIN recycle_event ON donate_item.event_id=recycle_event.event_id WHERE donate_item.event_id=?";
                                            $user_stmt = mysqli_prepare($con, $user_qry);
                                            mysqli_stmt_bind_param($user_stmt, 'i', $event_id);
                                            mysqli_stmt_execute($user_stmt);
                                            mysqli_stmt_bind_result($user_stmt, $user_id, $event_name);
                                            mysqli_stmt_fetch($user_stmt);
                                            mysqli_stmt_close($user_stmt);

                                            // Insert notification into announcements table
                                            $message = "Your donation item at $event_name with ID $event_id has been picked up and hand in to $dropoff_destination.";
                                            $notif_qry = "INSERT INTO announcement (user_id, message) VALUES (?, ?)";
                                            $notif_stmt = mysqli_prepare($con, $notif_qry);
                                            mysqli_stmt_bind_param($notif_stmt, 'is', $user_id, $message);
                                            mysqli_stmt_execute($notif_stmt);
                                            mysqli_stmt_close($notif_stmt);

                                            // Update lorry status to "Available"
                                            $update_lorry_qry = "UPDATE pickup_lorry SET status='Available' WHERE lorry_id=?";
                                            $update_stmt = mysqli_prepare($con, $update_lorry_qry);
                                            mysqli_stmt_bind_param($update_stmt, 'i', $lorry_id);
                                            mysqli_stmt_execute($update_stmt);
                                            mysqli_stmt_close($update_stmt);

                                            // Update item_point in donate_item table
                                            $update_points_qry = "UPDATE donate_item SET item_point=10 WHERE user_id=? AND event_id=?";
                                            $points_stmt = mysqli_prepare($con, $update_points_qry);
                                            mysqli_stmt_bind_param($points_stmt, 'ii', $user_id, $event_id);
                                            mysqli_stmt_execute($points_stmt);
                                            mysqli_stmt_close($points_stmt);

                                            // Commit the transaction
                                            mysqli_commit($con);

                                            echo "SUCCESSFULLY UPDATED";
                                        } catch (Exception $e) {
                                            // Rollback the transaction in case of error
                                            mysqli_rollback($con);
                                            echo "ERROR: " . $e->getMessage();
                                        }

                                        mysqli_close($con);
                                    }
                                    ?>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
</body>

<footer>
    <p>&copy; <?php echo date("Y"); ?>: Developed By Salihah</p>
</footer>

<style>
    footer {
        background-color: #424558;
        bottom: 0;
        left: 0;
        right: 0;
        height: 35px;
        text-align: center;
        color: #CCC;
    }

    footer p {
        padding: 10.5px;
        margin: 0px;
        line-height: 100%;
    }
</style>

</html>
