<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
                    
                    <h1 class="page-header">Event</h1>
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
                            Total Records of Event
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Event Date</th>
                                            <th>Event Name</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Location</th>
                                            <th>Location Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include "dbconnect.php";
                                        $qry = "SELECT * FROM recycle_event ORDER BY event_date DESC";
                                        $result = mysqli_query($conn, $qry);
                                        while ($row = mysqli_fetch_array($result)) {
                                            $formatted_date = date('d-m-Y', strtotime($row['event_date']));
                                            echo "<tr class='gradeA'>
                                                <td>".$formatted_date."</td>
                                                <td>".$row['event_name']."</td>
                                                <td>".$row['event_start_time']."</td>
                                                <td>".$row['event_end_time']."</td>
                                                <td>".$row['event_loc']."</td>
                                                <td><img src='". $row['event_image'] . "' alt='Event Image' width='200'></td>
                                                <td><a href='editeventform.php?event_id=".$row['event_id']."'><i class='fa fa-edit' style='color:green'></i></a></td>
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
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../js/dataTables/jquery.dataTables.min.js"></script>
<script src="../js/dataTables/dataTables.bootstrap.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>

<!-- Include the Date Sorting Plugin -->
<script src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
$(document).ready(function() {
    // Register the datetime sorting plugin for DataTables
    $.fn.dataTable.moment('DD-MM-YYYY');

    $('#dataTables-example').DataTable({
        responsive: true,
        "paging": true,
        "searching": true,
        "ordering": true,
        "order": [[0, 'desc']],  // Order by the first column (Event Date) in descending order
    });
});
</script>

</body>
<footer>
    <p>&copy; <?php echo date("Y"); ?>: Developed By Salihah</p>
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
