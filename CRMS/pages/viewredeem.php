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
                    
            <h1 class="page-header">Customer Redeem Point</h1>
                    <ol class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Redeem Points</li>
                    </ol>
                </div>
  </div>  

				<div class="row">
                        <div class=".col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Total Records of Redemption
                                </div>
								
								 <div class="panel-body">
                                    <div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">
									<thead>
                                        <tr>
										<th>User </th>
                                        <th>Name</th>
										<th>Total Redeem</th>
										<th>Total MYR</th>
										<th>Bank Name</th>
                                        <th>Account Number</th>
										<th>Status</th>
                                        <th>Transfered</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include "dbconnect.php";
                                        $qry = "SELECT r.redeem_id, r.total_point, r.total_ringgit, r.bank_name, r.account_num, r.approve_status, r.user_id, u.name
                                         FROM point_redeem r 
                                        JOIN user u ON 
                                        u.user_id = r.user_id";
                                        $result = mysqli_query($conn, $qry);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "<tr class='gradeA'>
                                                    <form method='post' action='update_redeem_status.php'>
                                                    <td>".$row['user_id']."</td>
                                                    <td>".$row['name']."</td>
                                                    <td>".$row['total_point']."</td>
                                                    <td>".$row['total_ringgit']."</td>
                                                    <td>".$row['bank_name']."</td>
                                                    <td>".$row['account_num']."</td>
                                                    <td>".$row['approve_status']."</td>
                                                    <td>
                                                        <input type='hidden' name='redeem_id' value='".$row['redeem_id']."' />
                                                        <select class='form-control' name='approve_status' required>
                                                            <option value=''>--- Status---</option>
                                                            <option value='Approved'>Approved</option>
                                                            <option value='Not Approved'>Not Approved</option>    
                                                        </select>
                                                        <button type='submit' class='btn btn-link' style='margin-top: 10px; padding: 0; border: none; background: none;'>
                                                            <i class='fa fa-edit' style='color:green'></i>
                                                        </button>
                                                    </td>
                                                    </form>
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