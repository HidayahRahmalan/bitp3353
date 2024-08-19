<!DOCTYPE html>
<html lang="en">
<head>
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
                <div class="col-lg-12">
                    <h1 class="page-header">CRMS</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            MESSAGE BOX
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="#" method="post">
                                        <?php
                                            include 'dbconnect.php';

                                            $event_id = $_POST['event_id'];
                                            $event_name = $_POST["event_name"];
                                            $event_loc = $_POST["event_loc"];
                                            $event_date = $_POST["event_date"];
                                            $event_start_time = $_POST["event_start_time"];
                                            $event_end_time = $_POST["event_end_time"];
                                            $current_image = $_POST["current_image"];

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
                                            } else {
                                                $target_file = $current_image;
                                            }

                                            // Update the event details in the database
                                            $qry = "UPDATE recycle_event SET event_name=?, event_loc=?, event_date=?, event_start_time=?, event_end_time=?, event_image=? WHERE event_id=?";
                                            $stmt = mysqli_prepare($conn, $qry);
                                            if ($stmt) {
                                                mysqli_stmt_bind_param($stmt, 'ssssssi', $event_name, $event_loc, $event_date, $event_start_time, $event_end_time, $target_file, $event_id);
                                                if (mysqli_stmt_execute($stmt)) {
                                                    echo "<div style='text-align: center'><h1>SUCCESSFULLY UPDATED</h1>";
                                                    echo "<a href='index.php' style='font-size: 20px;'>Go back to home</a></div>";
                                                } else {
                                                    echo "ERROR: Could not execute query: $qry. " . mysqli_error($conn);
                                                }
                                            } else {
                                                echo "ERROR: Could not prepare query: $qry. " . mysqli_error($conn);
                                            }

                                            mysqli_stmt_close($stmt);
                                            mysqli_close($conn);
                                        ?>
                                    </form>
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
