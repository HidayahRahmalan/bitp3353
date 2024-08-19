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
                        <div class="panel-heading">
                            MESSAGE BOX
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="index.php" method="post">
                                    <?php
                                            include 'dbconnect.php'; // Include your database connection script

                                            $submitted = false;
                                            $error = false;
                                            $error_message = '';

                                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                                if (isset($_POST['event_name']) && isset($_POST['event_loc']) && isset($_POST['event_date']) && isset($_POST['event_start_time']) && isset($_POST['event_end_time'])) {
                                                    $event_name = $_POST['event_name'];
                                                    $event_loc = $_POST['event_loc'];
                                                    $event_date = $_POST['event_date'];
                                                    $event_start_time = $_POST['event_start_time'];
                                                    $event_end_time = $_POST['event_end_time'];
                                                    $admin_id = $_SESSION['admin_id']; // Assuming you have set the session variable for admin_id

                                                    // Handle the file upload
                                                    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
                                                        $target_dir = "images/";
                                                        $target_file = $target_dir . basename($_FILES["event_image"]["name"]);
                                                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                                                        // Check if image file is an actual image or fake image
                                                        $check = getimagesize($_FILES["event_image"]["tmp_name"]);
                                                        if ($check === false) {
                                                            die("File is not an image.");
                                                        }

                                                        // Check file size (optional)
                                                        if ($_FILES["event_image"]["size"] > 500000) {
                                                            die("Sorry, your file is too large.");
                                                        }

                                                        // Allow certain file formats (optional)
                                                        $allowed_formats = ['jpg', 'jpeg', 'png', 'gif'];
                                                        if (!in_array($imageFileType, $allowed_formats)) {
                                                            die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                                                        }

                                                        // Move the uploaded file to the target directory
                                                        if (!move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
                                                            die("Sorry, there was an error uploading your file.");
                                                        }

                                                        try {
                                                            // Insert the event details into the database
                                                            $qry = "CALL insert_recycle_event(?, ?, ?, ?, ?, ?, ?)";
                                                            $stmt = mysqli_prepare($conn, $qry);
                                                            if ($stmt) {
                                                                mysqli_stmt_bind_param($stmt, 'ssssssi', $event_name, $event_loc, $event_date, $event_start_time, $event_end_time, $target_file, $admin_id);
                                                                if (mysqli_stmt_execute($stmt)) {
                                                                    $submitted = true;
                                                                } else {
                                                                    throw new Exception("Could not execute statement: " . mysqli_stmt_error($stmt));
                                                                }
                                                                mysqli_stmt_close($stmt);
                                                            } else {
                                                                throw new Exception("Could not prepare statement: " . mysqli_error($conn));
                                                            }
                                                        } catch (Exception $e) {
                                                            $error = true;
                                                            $error_message = $e->getMessage(); // Get the error message from the exception
                                                        }
                                                    } else {
                                                        die("No file was uploaded or there was an error.");
                                                    }
                                                } else {
                                                    echo "<h3>YOU ARE NOT AUTHORIZED TO REDIRECT THIS PAGE.  GO BACK to <a href='index.php'> DASHBOARD </a></h3>";
                                                }
                                                mysqli_close($conn);
                                            }
                                            ?>

                              
                
                                    </form>
                                    <?php
                                    if ($submitted) {
                                        echo "<script>alert('Event added Successfully!');";
                                        echo "window.location.href = 'addevent.php';</script>";
                                    } elseif ($error) {
                                        echo "<script>alert('$error_message');</script>";
                                        echo "<script> window.location.href = 'addevent.php';</script>";
                                    }
                                    ?>
                                </div>
                                
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
