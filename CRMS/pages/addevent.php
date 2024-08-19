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

        <?php include 'includes/nav.php'?>

        <div id="page-wrapper">
            <div class="row">
            <div class="col-lg-12 breadcrumb-container">
                    
                    <h1 class="page-header">Add Event Details</h1>
                    <ol class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Add Event</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Please fill up the form below:
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="addedevent.php" method="post" enctype="multipart/form-data">
                                     
                                        <div class="form-group">
                                            <label>Enter Event Name</label>
                                            <input class="form-control" type="text" placeholder="Donation for Ramadhan" name="event_name" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Enter Event Location</label>
                                            <select class="form-control" name="event_loc" required>
                                            <option value="">Select Location</option>
                                            <option value="The Vintage Attire - Penang">The Vintage Attire - Penang</option>
                                            <option value="Usnasa Bundle Kepala Batas - Penang">Usnasa Bundle Kepala Batas - Penang</option>
                                            <option value="Pusat Amal QC - Melaka">Pusat Amal QC - Melaka</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Enter event date</label>
                                            <input class="form-control" type="date" name="event_date" min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Enter Start time</label>
                                            <input class="form-control"  type="time" name="event_start_time" min="07:00" max="23:59" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Enter End time</label>
                                            <input class="form-control"  type="time" name="event_end_time" min="07:00" max="23:59" required>
                                        </div>

                                        <div class="form-group">
                                        <label>Location Image</label>
                                        <input type="file" class="form-control"  name="event_image"  required="true">
                                        </div>
                                    
										
                                        <button type="submit" class="btn btn-success btn-default" style="border-radius: 0%;">Submit Form</button>
                
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
        <p>&copy; <?php echo date("Y"); ?>: Developed By Salihah Husna</p>
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
