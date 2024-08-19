<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Driver - CRMS</title>
    <link href="../img/clothes-donation.png" rel="icon">
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

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

        <?php include 'session.php';
        include 'includes/drivernav.php'?>

        <div id="page-wrapper">
            <div class="row">
            <div class="col-lg-12 breadcrumb-container">
                    
                    <h1 class="page-header">Driver Dashboard</h1>
                    <ol class="breadcrumb">
                        <li><a href="driverdashboard.php">Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                <i class="icofont-truck-alt icofont-6x"></i>
                                    <!-- <i class="fa fa-heartbeat fa-5x"></i> -->
                                </div>
                                <div class="col-xs-9 text-right">
                                    <!-- in order to count total donor's record -->
                                    <?php include 'counter/pickupcount.php';?> 
                                    
                                    <div>Number of Successful Pickup</div>
                                </div>
                            </div>
                        </div>
                        <a href="viewduty.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Pickup</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				
				
            
            <!-- <div class="container">
	<div class="row">
	<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
  <strong><i class="fa fa-warning"></i> You Are Currently Viewing User's Account</strong> <marquee><p style="font-family: Impact; font-size: 18pt">You Are Currently Viewing User's Account</p></marquee>
</div> -->
	</div>

    <div class="container-fluid">
<div class="row">
<div class=".col-lg-12">
               <h1 class="page-header">List of Pickup</h1>
                </div>
  </div>  

				<div class="row">
                        <div class=".col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Total Records of Pickup
                                </div>
								
								 <div class="panel-body">
                                    <div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">
									
									<?php

                       include "../dbcon.php";

						$qry="SELECT DISTINCT pickup_session.event_id,pickup_session.lorry_id, pickup_session.pickup_status,pickup_session.pickup_time,pickup_session.pickup_date,pickup_session.dropoff_destination,recycle_event.event_name,pickup_lorry.plate_number,recycle_event.event_loc
                        FROM pickup_session JOIN recycle_event 
                        ON pickup_session.event_id = recycle_event.event_id
                        JOIN pickup_lorry 
                        ON pickup_session.lorry_id = pickup_lorry.lorry_id
                        JOIN lorry_driver
                        ON pickup_session.lorry_id = lorry_driver.lorry_id
                        WHERE pickup_session.pickup_status = 'To be pickup'
                        ORDER BY pickup_session.pickup_status DESC";
						$result=mysqli_query($conn,$qry);


						echo"
						<thead>
						<tr>
							<th>Event Name</th>
                            <th>Event Location</th>
							<th>Pickup Date</th>
							<th>Pickup Time</th>
                            <th>Dropoff Destination</th>
                            <th>Pickup Status</th>
                            <th>Pickup Lorry</th>
							<th>Status</th>
							
						</tr>
						</thead>";

						if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                $formatted_date = date('d-m-Y', strtotime($row['pickup_date']));
                                echo "<tbody>
                                <tr class='gradeA'>
                                    <td>" . $row['event_name'] . "</td>
                                    <td>" . $row['event_loc'] . "</td>
                                    <td>" . $formatted_date . "</td>
                                    <td>" . $row['pickup_time'] . "</td>
                                    <td>" . $row['dropoff_destination'] . "</td>
                                    <td>" . $row['pickup_status'] . "</td>
                                    <td>" . $row['plate_number'] . "</td>
                                    <td><a href='confirmpickupform.php?event_id=" . $row['event_id'] . "'>Confirm Pickup</a></td>
                                </tr>
                                </tbody>";
                            }
                        } else {
                            echo "<tbody>
                            <tr>
                                <td colspan='7' style='text-align: center;'>No pickup schedule</td>
                            </tr>
                            </tbody>";
                        }
                        
						?>
						</table>
									
				</div>
				</div>		
		</div>
		</div>	
		</div>	
		</div>
</div>
            <!-- /.row -->
           
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

    <!-- Morris Charts JavaScript -->
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script>

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
