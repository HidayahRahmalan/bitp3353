<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin - CRMS</title>
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../icofont/icofont.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div id="wrapper">
        <?php include 'includes/nav.php'; ?>
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
                                    <form role="form" action="index.php" method="post">
                                        <?php
                                        include 'dbconnect.php'; // Include the file where $conn is defined

                                        if (isset($_POST['event_id'])) {
                                            $event_id = $_POST["event_id"];
                                            $lorry_id = $_POST["lorry_id"];
                                            $pickup_status = $_POST["pickup_status"];
                                            $pickup_date = $_POST["pickup_date"];
                                            $pickup_time = $_POST["pickup_time"];
                                            $dropoff_destination = $_POST["dropoff_destination"];

                                            // Start a transaction
                                            mysqli_begin_transaction($conn);

                                            try {
                                                // Insert the pickup session
                                                $qry = "CALL insert_pickup (?, ?, ?, ?, ?, ?)";
                                                $stmt = mysqli_prepare($conn, $qry);
                                                mysqli_stmt_bind_param($stmt, 'iissss', $event_id, $lorry_id, $pickup_status, $pickup_time, $pickup_date, $dropoff_destination);
                                                $result = mysqli_stmt_execute($stmt);

                                                if (!$result) {
                                                    throw new Exception(mysqli_error($conn));
                                                }

                                                // Update the lorry status to "Unavailable"
                                                $update_lorry_qry = "UPDATE pickup_lorry SET status = 'Unavailable' WHERE lorry_id = ?";
                                                $update_stmt = mysqli_prepare($conn, $update_lorry_qry);
                                                mysqli_stmt_bind_param($update_stmt, 'i', $lorry_id);
                                                $update_result = mysqli_stmt_execute($update_stmt);

                                                if (!$update_result) {
                                                    throw new Exception(mysqli_error($conn));
                                                }

                                                // Commit the transaction
                                                mysqli_commit($conn);

                                                echo "<div style='text-align: center'><h1>SUBMITTED SUCCESSFULLY</h1>";
                                                echo "<a href='index.php' div style='text-align: center'><h3>Go Back</h3>";
                                            } catch (Exception $e) {
                                                // Rollback the transaction in case of error
                                                mysqli_rollback($conn);
                                                echo "<div style='text-align: center'><h1>ERROR: " . $e->getMessage() . "</h1></div>";
                                            }

                                            mysqli_stmt_close($stmt);
                                            mysqli_stmt_close($update_stmt);
                                            mysqli_close($conn);
                                        } else {
                                            echo "<h3>YOU ARE NOT AUTHORIZED TO REDIRECT THIS PAGE.  GO BACK to <a href='index.php'> DASHBOARD </a></h3>";
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
    <p>&copy; <?php echo date("Y"); ?>: Developed By Salihah Husna</p>
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
