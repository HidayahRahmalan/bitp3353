<html>

<head>


<title>Driver - CRMS</title>
<link href="../img/clothes-donation.png" rel="icon">
<!-- Bootstrap Core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">

<!-- MetisMenu CSS -->
<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

<!-- DataTables CSS -->
 <link href="../css/dataTables/dataTables.bootstrap.css" rel="stylesheet">
 
<!-- DataTables Responsive CSS -->
<link href="../css/dataTables/dataTables.responsive.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="../dist/css/sb-admin-2.css" rel="stylesheet">

<!-- Custom Fonts -->
<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="../icofont/icofont.min.css">

</head>


<body>
<div id="wrapper">

<?php include 'session.php';
include 'includes/drivernav.php';?>


<div id="page-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-lg-12 breadcrumb-container">
<marquee style="font-size: 20px; color: black;">Please Come to the DropOff location accordingly.</marquee>
                    <h1 class="page-header">Duties</h1>
                    <ol class="breadcrumb">
                        <li><a href="driverdashboard.php">Home</a></li>
                        <li class="active">Duties</li>
                    </ol>
                </div>

  </div>  

				<div class="row">
                        <div class=".col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Duty Records
                                </div>
								
								 <div class="panel-body">
                                    <div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">
									
									<?php

						include "../dbcon.php";

						$qry="SELECT lorry_driver.duty_date, lorry_driver.duty_time,pickup_lorry.plate_number 
                        from lorry_driver join pickup_lorry 
						on lorry_driver.lorry_id = pickup_lorry.lorry_id
                        where lorry_driver.driver_id = $session_id";
						$result=mysqli_query($con,$qry);


						echo"
						<thead>
						<tr>
                            <th>Duty Date</th>
							<th>Duty Time</th>
                            <th>Lorry Plate Number</th>
                            
							
						</tr>
						</thead>";

						while($row=mysqli_fetch_array($result)){
						  echo"<tbody>
						  <tr class='gradeA'>
						  
						  <td>".$row['duty_date']."</td>
                          <td>".$row['duty_time']."</td>
                          <td>".$row['plate_number']."</td>
                          
						  
						  

						</tr>
						<tbody>
						";
						}

						?>
						</table>
									
				</div>
				</div>		
		</div>
		</div>	
		</div>	
		</div>
        <!---sec div-->
        <div class="container-fluid">
<div class="row">
<div class=".col-lg-12">
               <h1 class="page-header">Pickup History</h1>
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

						$qry="select donate_item.item_id,donate_item.event_id,recycle_event.event_name,recycle_event.event_loc,recycle_event.event_date,item_category.item_name, pickup_session.pickup_status 
                        from donate_item join item_category on donate_item.item_id = item_category.item_id
                        join recycle_event on donate_item.event_id = recycle_event.event_id 
                        join pickup_session on donate_item.event_id = pickup_session.event_id
                        where pickup_session.pickup_status = 'Already Pickup' AND donate_item.user_id = $session_id";
						$result=mysqli_query($con,$qry);


						echo"
						<thead>
						<tr>
                            <th>Item Name</th>
							<th>Event Name</th>
                            <th>Event Date</th>
                            <th>Location</th>
                            
							<th>Pickup Status</th>
							
						</tr>
						</thead>";

						while($row=mysqli_fetch_array($result)){
						  echo"<tbody>
						  <tr class='gradeA'>
						  
						  <td>".$row['item_name']."</td>
                          <td>".$row['event_name']."</td>
                          <td>".$row['event_date']."</td>
                          <td>".$row['event_loc']."</td>
                          
						  <td>".$row['pickup_status']."</td>
						  
						  

						</tr>
						<tbody>
						";
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
		</div>

        

  <!-- jQuery -->
  <script src="../vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="../vendor/metisMenu/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="../dist/js/sb-admin-2.js"></script>

<!-- DataTables JavaScript -->
<script src="../js/dataTables/jquery.dataTables.min.js"></script>
<script src="../js/dataTables/dataTables.bootstrap.min.js"></script>

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