<html>
<head>
    <title>User - CRMS</title>
    <link href="img/clothes-donation.png" rel="icon">
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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
<div id="wrapper">
    <?php include 'session.php'; include 'includes/donornav.php'; ?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
            <div class="col-lg-12 breadcrumb-container">
                    <h1 class="page-header">List of Announcement</h1>
                                        <ol class="breadcrumb">
                                            <li><a href="userdashboard.php">Home</a></li>
                                            <li class="active">Announcement</li>
                                        </ol>
                                    </div>
            </div>  
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Total Records of Announcement
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Inbox</th>
                                            <th>Message Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include "../dbcon.php";
                                        $qry = "SELECT message, created_at FROM announcement WHERE user_id = $session_id AND is_read = 0 ORDER BY created_at desc";
                                        $result = mysqli_query($con, $qry);

                                        while($row = mysqli_fetch_assoc($result)){
                                            $formatted_date = date('d-m-Y', strtotime($row['created_at']));
                                            echo "<tr class='gradeA'>
                                                    <td>{$row['message']}</td>
                                                    <td>{$formatted_date}</td>
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
