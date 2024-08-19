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
                                    <form role="form" action="#" method="post">

                                    <?php
                                    include 'dbconnect.php';

                                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                        $item_name = $_POST["item_name"];
                                        $item_category = $_POST["item_category"];
                                        $item_id = $_POST["item_id"];
                                        $current_image = $_POST["current_image"];

                                        // Handle file upload
                                        if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
                                            $fileTmpPath = $_FILES['item_image']['tmp_name'];
                                            $fileName = $_FILES['item_image']['name'];
                                            $fileSize = $_FILES['item_image']['size'];
                                            $fileType = $_FILES['item_image']['type'];
                                            $fileNameCmps = explode(".", $fileName);
                                            $fileExtension = strtolower(end($fileNameCmps));

                                            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
                                            if (in_array($fileExtension, $allowedfileExtensions)) {
                                                $uploadFileDir = 'images/';
                                                $dest_path = $uploadFileDir . $fileName;

                                                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                                                    $item_image = $fileName;
                                                } else {
                                                    echo 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                                                }
                                            } else {
                                                echo 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
                                            }
                                        } else {
                                            $item_image = $current_image;
                                        }

                                        if (empty($item_name) || empty($item_category) || empty($item_id)) {
                                            echo "All fields are required!";
                                        } else {
                                            $qry = "CALL edit_item(?, ?, ?, ?)";
                                            $stmt = mysqli_prepare($conn, $qry);

                                            if ($stmt) {
                                                mysqli_stmt_bind_param($stmt, "isss", $item_id, $item_name, $item_category, $item_image);
                                                $result = mysqli_stmt_execute($stmt);

                                                if (!$result) {
                                                    echo "ERROR: " . mysqli_error($conn);
                                                } else {
                                                    echo "SUCCESSFULLY UPDATED";
                                                    // Redirect to index.php after success
                                                    echo '<meta http-equiv="refresh" content="2;url=index.php">';
                                                    exit();
                                                }

                                                mysqli_stmt_close($stmt);
                                            } else {
                                                echo "ERROR: Could not prepare statement " . mysqli_error($conn);
                                            }
                                        }
                                    } else {
                                        echo "Invalid request method.";
                                    }
                                    ?>

                                  </form>
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
