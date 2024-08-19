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
                    
                    <h1 class="page-header">Edit Pickup Details</h1>
                            <ol class="breadcrumb">
                                <li><a href="index.php">Home</a></li>
                                <li class="active">Pickup</li>
                            </ol>
                        </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
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
									$event_id=$_GET['event_id'];
									$qry= "select * from pickup_session where event_id='$event_id'";
									$result=mysqli_query($conn,$qry);
									while($row=mysqli_fetch_array($result)){
									?> 

                                    <form role="form" action="editedpickup.php" method="post">
                                     
                                        <div class="form-group">
                                        <label for="filterBrand" class="mr-2">Select Pickup Lorry: </label>
                                          <select name="lorry_id" class="form-control" required>
                                            <option value="">--- Select Lorry---</option>
                                            <?php
        
                                             $lorryname = $conn->query("SELECT * FROM pickup_lorry WHERE status = 'Available' ");
                                               while ($l = $lorryname->fetch_assoc()) {
                                                  $ev[$l['lorry_id']] = $l['plate_number'];
                                                   ?>
                                                  <option value="<?php echo $l['lorry_id'] ?>">
                                                    <?php echo $l['plate_number'] ?>
                                                      </option>
                                                  <?php
                                                 }
                                                   ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Pickup Status</label>
                                            
                                             <select class="form-control" name="pickup_status" value='<?php echo $row['pickup_status']; ?>' required>
                                               <option value="">--- Select Status---</option>
                                               <option value="To be Pickup">To be Pickup</option>
                                               <option value="Already Pickup">Already Pickup</option>
                                             </select>
                                            
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Pickup Date</label>
                                            <input class="form-control" type="date" name="pickup_date" min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Pickup Time</label>
                                            <input class="form-control" type="time"  name="pickup_time" value='<?php echo $row['pickup_time']; ?>' required>
                                        </div>

                                        <div class="form-group">
                                            <label>Dropoff Destination</label>
                                            <input class="form-control" type="text"  name="dropoff_destination" value='<?php echo $row['dropoff_destination']; ?>' required>
                                        </div>
                                       
             <!-- id hidden grna input type ma "hidden" -->
             <input type="hidden" name="event_id" value="<?php echo $row['event_id'];?>">
                                
             <button type="submit"  class="btn btn-success">Make Changes</button>
             <a href="viewpickup.php" class="btn btn-success">Back</a>
 
                                    </form>
                                </div>

						<?php
						}
						?>
                                
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
    document.getElementsByName("pickup_date")[0].setAttribute('min', today);
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
                             
