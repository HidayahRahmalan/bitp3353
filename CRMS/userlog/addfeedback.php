<?php 
include "session.php";
include 'dbcon.php';
if (isset($_POST['submit'])) {
    $event_id           = $_POST['event_id'];
    $description        = $_POST['description'];
    $feedback_date      = $_POST['feedback_date'];
    $feedback_time      = $_POST['feedback_time'];
    $rating_score       = $_POST['rating_score'];
    $user_id            = $_SESSION['user_id'];
    
    $sql = "INSERT INTO `feedback` ( description, feedback_date, feedback_time, rating_score, user_id, event_id) VALUES ('$description', '$feedback_date','$feedback_time', '$rating_score', '$user_id', '$event_id')";

    $result = mysqli_query($con, $sql);

    if ($result) {
        echo '<script>alert("Thank you for your feedback.")</script>';
    } else {
        echo '<script>alert("Something went wrong. Please try again.")</script>';
    }
}
$todayDate = date("Y-m-d");
?>
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

    <!-- RateYo CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- RateYo JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div id="wrapper">
        <?php include 'includes/donornav.php'; include '../dbcon.php'; ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12 breadcrumb-container">
                    <h1 class="page-header">Add Feedback Details</h1>
                    <ol class="breadcrumb">
                        <li><a href="userdashboard.php">Home</a></li>
                        <li class="active">Add Feedback</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Please fill up the form below:
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="" method="post">

                                    <div class="form-group">
                                        <label for="event_id" class="mr-2">Event: </label>
                                        <select name="event_id" id="event_id" class="form-control" required>
                                            <option value="">--- Select Event---</option>
                                            <?php
                                            $today = date('Y-m-d');
                                            $eventname = $con->query("SELECT * FROM recycle_event WHERE event_date <= '$today'"); // Select events that already happened
                                            while ($c = $eventname->fetch_assoc()) {
                                                ?>
                                                <option value="<?php echo $c['event_id'] ?>">
                                                    <?php echo $c['event_name'] ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="filterBrand" class="mr-2"> Description: </label>
                                        <input class="form-control" type="text"  name="description" required>
                                    </div>

                                    <!-- Hidden Feedback Date and Time -->
                                    <input type="hidden" name="feedback_date" value="<?php echo $todayDate; ?>">
                                    <input type="hidden" name="feedback_time" id="feedback_time" value="">

                                    <div class="form-group">
                                        <label for="filterBrand" class="mr-2"> Rating: </label>
                                        <div id="rating"></div>
                                        <input type="hidden" name="rating_score" id="rating_score" required>
                                    </div>

                                    <button type="submit" name="submit" class="btn btn-success btn-default" style="border-radius: 0%;">Submit Form</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function () {
        $("#rating").rateYo({
            rating: 1, // Default rating
            fullStar: true,
            onSet: function (rating, rateYoInstance) {
                $("#rating_score").val(rating);
            }
        });
    });

   // Function to format the current time as HH:MM
        function getCurrentTime() {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            return `${hours}:${minutes}`;
        }

        // Set the value of feedback_time to the current time
        document.getElementById('feedback_time').value = getCurrentTime();

    </script>
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
