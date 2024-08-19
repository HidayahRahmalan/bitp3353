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
                    
                    <h1 class="page-header">Edit Lorry Details</h1>
                            <ol class="breadcrumb">
                                <li><a href="index.php">Home</a></li>
                                <li class="active">Lorry</li>
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
									$lorry_id=$_GET['lorry_id'];
									$qry= "select * from pickup_lorry where lorry_id='$lorry_id'";
									$result=mysqli_query($conn,$qry);
									while($row=mysqli_fetch_array($result)){
									?> 

                                    <form role="form" action="editedlorry.php" method="post">

                                    <div class="form-group">
                                            <label>Enter Plate Number</label>
                                            <input class="form-control" type="text"  name="plate_number" value='<?php echo $row['plate_number']; ?>' required>
                                        </div>

                                        <div class="form-group">
                                       <label>Lorry Status</label>
                                     <select class="form-control" name="status" value='<?php echo $row['status']; ?>' required>
                                       <option value="">Select Status</option>
                                       <option value="Available">Available</option>
                                       <option value="Unavailable">Unavailable</option>
                                    </select>
                                    </div>
                                     
                                       

                                        
                                       
             <!-- id hidden grna input type ma "hidden" -->
             <input type="hidden" name="lorry_id" value="<?php echo $row['lorry_id'];?>">
                                
             <button type="submit"  class="btn btn-success">Make Changes</button>
             <a href="viewlorry.php" class="btn btn-success">Back</a>
 
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
                             
