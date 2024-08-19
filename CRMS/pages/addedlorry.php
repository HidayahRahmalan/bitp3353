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

        <?php 
        include '../session.php';
        include 'includes/nav.php';
        
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">CRMS</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="index.php" method="post">
                                    <?php
                                            include 'dbconnect.php'; // Include your database connection script

                                            $submitted = false;
                                            $error = false;
                                            $error_message = '';

                                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                                if (isset($_POST['plate_number']) && isset($_POST['status'])) {
                                                    $plate_number = $_POST['plate_number'];
                                                    $status = $_POST['status'];
                                                    
                                                    try {
                                                        // Prepare the query to insert into pickup_lorry table
                                                        $qry = "INSERT INTO `pickup_lorry` (plate_number, status, admin_id) VALUES ('$plate_number','$status','$session_id')";
                                                        $result = mysqli_query($conn, $qry); // Execute the query

                                                        if (!$result) {
                                                            throw new Exception(mysqli_error($conn)); // Throw exception if query execution fails
                                                        } else {
                                                            $submitted = true;
                                                        }
                                                    } catch (Exception $e) {
                                                        $error = true;
                                                        $error_message = $e->getMessage(); // Get the error message from the exception
                                                    }
                                                } else {
                                                    echo "<h3>YOU ARE NOT AUTHORIZED TO REDIRECT THIS PAGE.  GO BACK to <a href='index.php'> DASHBOARD </a></h3>";
                                                }
                                            }
                                            ?>                        
                
                                    </form>
                                    <?php
                                    if ($submitted) {
                                        echo "<script>alert('Lorry Successfully Added!')</script>";
                                        echo "<script> window.location.href = 'viewlorry.php';</script>";
                                    } elseif ($error) {
                                        echo "<script>alert('Error: $error_message')</script>";
                                        echo "<script> window.location.href = 'add_lorry.php';</script>";
                                    }
                                    ?>
                                </div>
                                
                            </div>
                            <!-- /.row (nested) -->
                        
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
