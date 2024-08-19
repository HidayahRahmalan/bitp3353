<html>

<head>


<title>Admin - CRMS</title>

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

<?php include 'includes/nav.php'?>


<div id="page-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-lg-12 breadcrumb-container">
                    
            <h1 class="page-header">Pickup</h1>
                    <ol class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Pickup</li>
                    </ol>
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
									<thead>
                                        <tr>
										<th>Event Name</th>
                                        <th>Pickup Location</th>
										<th>Pickup Date</th>
										<th>Pickup Time</th>
                                        <th>Dropoff Destination</th>
										<th>Pickup Status</th>
										<th>Pickup Lorry</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include "dbconnect.php";
                                        $qry = "SELECT DISTINCT * FROM view_pickup_details ORDER BY pickup_date ASC ";
                                        $result = mysqli_query($conn, $qry);
                                        while ($row = mysqli_fetch_array($result)) {
                                            $formatted_date = date('d-m-Y', strtotime($row['pickup_date']));
                                            echo "<tr class='gradeA'>
													<td>".$row['event_name']."</td>
                                                    <td>".$row['event_loc']."</td>
													<td>".$formatted_date."</td>
													<td>".$row['pickup_time']."</td>
                                                    <td>".$row['dropoff_destination']."</td>
													<td>".$row['pickup_status']."</td>
													<td>".$row['plate_number']."</td>
                                                    <td><a href='editpickupform.php?event_id=".$row['event_id']."'><i class='fa fa-edit' style='color:green'></i></a></td>
                                            </tr>";
                                        }
                                        ?>
                                    </tbody>

								
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

<!--utk searchbox dgn pagination-->
<script>
$(document).ready(function() {
    $('#dataTables-example').DataTable({
        responsive: true,
        "paging": true,
        "searching": true,
        "ordering": true,
    });
});
</script>
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