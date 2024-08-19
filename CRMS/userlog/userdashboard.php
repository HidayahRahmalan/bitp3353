<?php
include 'dbcon.php'; // Include your database connection script

// Fetch upcoming events
$events_query = "SELECT event_name, event_date, event_loc FROM recycle_event WHERE event_date >= CURDATE() ORDER BY event_date ASC";
$events_result = mysqli_query($con, $events_query);

$events = [];
if ($events_result && mysqli_num_rows($events_result) > 0) {
    while ($row = mysqli_fetch_assoc($events_result)) {
        $row['event_date'] = date('d-m-Y', strtotime($row['event_date'])); // Format date to DD-MM-YY
        $events[] = $row;
    }
}

mysqli_close($con);
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
    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../icofont/icofont.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>
    <div id="wrapper">
        <?php include 'session.php'; ?>
        <?php include 'includes/donornav.php'; ?>
        
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12 breadcrumb-container">
                    <h1 class="page-header">Recycle Campaign/Event</h1>
                    <ol class="breadcrumb">
                        <li><a href="userdashboard.php">Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </div>
            </div>

            <!-- Marquee for Upcoming Events -->
            <?php if (!empty($events)): ?>
                <marquee style="font-size: 20px; color: black;">
                    <?php foreach ($events as $event): ?>
                        <?php echo "Upcoming Event: " . htmlspecialchars($event['event_name']) . " on " . htmlspecialchars($event['event_date']) . " at " . htmlspecialchars($event['event_loc']) . " | "; ?>
                    <?php endforeach; ?>
                </marquee>
            <?php else: ?>
                <marquee style="font-size: 20px; color: black;">
                    No upcoming events.
                </marquee>
            <?php endif; ?>

            <div class="row">
                
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                <i class="icofont-cube icofont-5x"></i>
                                    <!-- <i class="fa fa-heartbeat fa-5x"></i> -->
                                </div>
                                <div class="col-xs-9 text-right">
                                    <!-- in order to count total donor's record -->
                                    <?php include 'counter/dashpointcount.php';?> 
                                    
                                    <div>Points Collected</div>
                                </div>
                            </div>
                        </div>
                        <a href="addredeem.php">
                            <div class="panel-footer">
                                <span class="pull-left"></span>
                                <span class="pull-left">Redeem Points</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                <i class="icofont-gift icofont-5x"></i>
                                    <!-- <i class="fa fa-heartbeat fa-5x"></i> -->
                                </div>
                                <div class="col-xs-9 text-right">
                                    <!-- in order to count total donor's record -->
                                    <?php include 'counter/redeempointcount.php';?> 
                                    
                                    <div>Points Redeem</div>
                                </div>
                            </div>
                        </div>
                        <a href="viewhistredeem.php">
                            <div class="panel-footer">
                                <span class="pull-left"></span>
                                <span class="pull-left">Redeem History</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>

                </div>
           
				
			
	</div>

            <div class="w3-container" id="where" style="padding-bottom:20px;">
                <div class="w3-content" style="max-width:900px">
                    <h5 class=" w3-padding-46"><span class="w3-tag w3-wide">HOW TO DONATE?</span></h5>
                    <p>Donate now by following these simple steps:</p>
                    <div class="img-row">
                        <img src="../img/1.png" class="w3-margin-top">
                        <img src="../img/2.png" class="w3-margin-top">
                        <img src="../img/3.png" class="w3-margin-top">
                    </div>
                    <p><span class="w3-tag">1</span> <strong>Pack</strong> your clothes into boxes or bag.</p>
                    <p><span class="w3-tag">2</span> <strong>Login</strong> to your account and submit your donation.</p>
                    <p><span class="w3-tag">3</span> <strong>Drop</strong> your items at the pickup location. </p>
                </div>
            </div>
           
            <div class="row image-grid">
                <?php
                $imageDirectory = 'frontimage/';
                $images = glob($imageDirectory . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

                foreach ($images as $image) {
                    echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">';
                    echo '<img src="' . $image . '" class="img-responsive img-thumbnail donate-image" alt="Image">';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.donate-image').on('click', function() {
                var imageSrc = $(this).attr('src');
                var confirmMessage = confirm("Are you sure you want to donate?");
                if (confirmMessage) {
                    window.location.href = 'adddonate.php?image=' + encodeURIComponent(imageSrc);
                } else {
                    console.log("Donation canceled");
                }
            });
        });
    </script>
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

    .image-grid {
        margin-top: 20px;
    }

    .image-grid .col-lg-4,
    .image-grid .col-md-4,
    .image-grid .col-sm-6 {
        padding: 10px;
    }

    .img-thumbnail {
        width: 100%;
        height: auto;
    }

    .img-row img {
        width: 150px;
        height: 200px;
        margin-right: 5px;
    }

    .img-row {
        display: flex;
        justify-content: left;
        flex-wrap: wrap;
    }
</style>
</html>
