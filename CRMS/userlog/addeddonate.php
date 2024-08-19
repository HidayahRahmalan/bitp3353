<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="wrapper">
        <?php 
        include 'session.php';
        include 'includes/donornav.php';
        include '../dbcon.php'; 

        $sql1 = "SELECT user_id FROM `user` WHERE user_id='$session_id'";
        $result1 = mysqli_query($con, $sql1);
        $row = mysqli_fetch_assoc($result1);

        if (isset($_POST['cloth_condition']) && isset($_POST['item_id'])) {
            $cloth_condition = $_POST["cloth_condition"];
            $event_id = $_POST["event_id"];
            $user_id = $row["user_id"];
            $item_ids = $_POST["item_id"];
            $item_point = 0;
            $point_redeem = 'No';

            $all_success = true;
            foreach ($item_ids as $item_id) {
                $qry = "INSERT INTO `donate_item` (cloth_condition, item_id, event_id, user_id, item_point, point_redeem) VALUES ('$cloth_condition', '$item_id', '$event_id', '$user_id', '$item_point', '$point_redeem')";
                $result = mysqli_query($con, $qry);
                if (!$result) {
                    $all_success = false;
                    echo "<div style='text-align: center'><h1>ERROR</h1></div>";
                    break;
                }
            }

            if ($all_success) {
                // Get the user_id associated with the event_id
                $user_qry = "SELECT recycle_event.event_date, recycle_event.event_loc 
                FROM donate_item join recycle_event 
                on donate_item.event_id = recycle_event.event_id
                WHERE donate_item.event_id='$event_id'";
                $user_result = mysqli_query($con, $user_qry);
                $user_row = mysqli_fetch_assoc($user_result);
               
                $event_loc =$user_row['event_loc'];
                //$event_date =$user_row['event_date'];
                $formatted_date = date('d-m-Y', strtotime($user_row['event_date']));

                $announcement_message = "Please come to the $event_loc on the $formatted_date to drop off your items.";
                $qry_announcement = "INSERT INTO `announcement` (user_id, message) VALUES ('$user_id', '$announcement_message')";
                mysqli_query($con, $qry_announcement);
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        $('#successModal').modal('show');
                    });
                    </script>";
            }
        } else {
            echo "<h3>YOU ARE NOT AUTHORIZED TO REDIRECT THIS PAGE. GO BACK to <a href='userdashboard.php'> DASHBOARD </a></h3>";
        }
        ?>
    </div>

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Modal HTML -->
    <div id="successModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Submission Successful</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Your donation has been successfully submitted.
                       Please come to the pickup location on the date stated. Thank you!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='userdashboard.php'">Go to Dashboard</button>
                </div>
            </div>
        </div>
    </div>
</body>
<footer>
    <p>&copy; <?php echo date("Y"); ?>: Developed By Salihah Husna</p>
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
