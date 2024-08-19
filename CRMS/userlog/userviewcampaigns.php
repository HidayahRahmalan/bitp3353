<html>

<head>

<title>User - CRMS</title>
<link href="img/clothes-donation.png" rel="icon">
<!-- Bootstrap Core CSS -->
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- MetisMenu CSS -->
<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="../dist/css/sb-admin-2.css" rel="stylesheet">

<!-- Custom Fonts -->
<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="../icofont/icofont.min.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


</head>


<body>
<div id="wrapper">

<?php include 'includes/donornav.php'?>


<div id="page-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-lg-12 breadcrumb-container">
<h1 class="page-header">Campaign</h1>
                    <ol class="breadcrumb">
                        <li><a href="userdashboard.php">Home</a></li>
                        <li class="active">Campaign</li>
                    </ol>
                </div>
  </div>  

				<div class="row">
                        <div class=".col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Total Records of available campaign
                                </div>
								
								 <div class="panel-body">
                                    <div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">
									
									<?php
                                        include "../dbcon.php";

                                        // Get today's date
                                        $today = date("Y-m-d");
                                        $qry = "SELECT * FROM recycle_event WHERE event_date > '$today'";
                                        $result = mysqli_query($con, $qry);

                                        echo "
                                        <thead>
                                            <tr>
                                                <th>Campaign Name</th>
                                                <th>Location</th>                        
                                                <th>Date of Campaign</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Event Location</th>
												<th>Donate</th>
                                            </tr>
                                        </thead>";

                                        while ($row = mysqli_fetch_array($result)) {
                                            $formatted_date = date('d-m-Y', strtotime($row['event_date']));
                                            $image_path = '../pages/' . $row['event_image']; 
                                            echo "
                                            <tbody>
                                                <tr>
                                                    <td>".$row['event_name']."</td>
                                                    <td>".$row['event_loc']."</td>
                                                    <td>".$formatted_date."</td>
                                                    <td>".$row['event_start_time']."</td>
                                                    <td>".$row['event_end_time']."</td>
                                                    <td><img src='".$image_path."' alt='Event Image' width='200'></td>
													<td><a href='adddonateitem.php?event_id=".$row['event_id']."'><i class='fa fa-edit' style='color:blue'></i></a></td>
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
</div>

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