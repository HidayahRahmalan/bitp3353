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
</head>
<body>
    <div id="wrapper">
        <?php include 'includes/nav.php'?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12 breadcrumb-container">
                    <h1 class="page-header">Edit Event Details</h1>
                    <ol class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Event</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Please make your changes by updating the form below:
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <?php
                                        include 'dbconnect.php';
                                        $event_id = $_GET['event_id'];
                                        $qry = "SELECT * FROM recycle_event WHERE event_id='$event_id'";
                                        $result = mysqli_query($conn, $qry);
                                        while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <form role="form" action="editedevent.php" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Enter Event Name</label>
                                            <input class="form-control" type="text" name="event_name" value='<?php echo $row['event_name']; ?>' required>
                                        </div>
                                        <div class="form-group">
                                            <label>Location</label>
                                            <input class="form-control" type="text" name="event_loc" value='<?php echo $row['event_loc']; ?>' required>
                                        </div>
                                        <div class="form-group">
                                            <label>Enter Date</label>
                                            <input class="form-control" type="date" name="event_date" min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Enter Start Time</label>
                                            <input class="form-control" type="time" name="event_start_time" value='<?php echo $row['event_start_time']; ?>' required>
                                        </div>
                                        <div class="form-group">
                                            <label>Enter End Time</label>
                                            <input class="form-control" type="time" name="event_end_time" value='<?php echo $row['event_end_time']; ?>' required>
                                        </div>
                                        <div class="form-group">
                                            <label>Event Image</label>
                                            <input class="form-control" type="file" name="event_image">
                                            <input type="hidden" name="current_image" value="<?php echo $row['event_image']; ?>">
                                        </div>
                                        <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                        <button type="submit" class="btn btn-success">Make Changes</button>
                                        <a href="viewevent.php" class="btn btn-success">Back</a>

                                    </form>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    // JavaScript fallback for browsers not supporting HTML5 date input validation
    var today = new Date().toISOString().split('T')[0];
    document.getElementsByName("event_date")[0].setAttribute('min', today);
</script>
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
footer{
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
