<?php
session_start();
// The isset function to check if the username is already logged in and stored in the session
if (!isset($_SESSION['admin_id'])) {
    header('location:../index.php');
}
?>

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

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../icofont/icofont.min.css">

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">
        // Initialize Bootstrap tooltips and popovers
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip()
            $('[data-bs-toggle="popover"]').popover()
        });

        google.charts.load('current', {
            'packages': ['bar', 'corechart']
        });
        google.charts.setOnLoadCallback(drawChart);
        google.charts.setOnLoadCallback(drawLineChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Category', 'Number', {
                    role: 'style'
                }],
                <?php
                include "dbconnect.php";
                $qry = "SELECT donate_item.item_id, count(*) as number, item_category.item_name 
                        FROM donate_item 
                        JOIN item_category ON donate_item.item_id = item_category.item_id 
                        GROUP BY donate_item.item_id";
                $result = mysqli_query($conn, $qry);

                $colors = ["#FF5733", "#33FF57", "#3357FF", "#F1C40F", "#9B59B6", "#E74C3C", "#1ABC9C"]; // Define some colors
                $i = 0;

                while ($row = mysqli_fetch_array($result)) {
                    $color = $colors[$i % count($colors)];
                    echo "['" . $row["item_name"] . "', " . $row["number"] . ", '" . $color . "'],";
                    $i++;
                }
                ?>
            ]);

            var options = {
                chart: {
                    title: 'Total Available Item According to Category Groups'
                },
                bars: 'vertical', // Required for Material Bar Charts.
                hAxis: {
                    title: 'Category'
                },
                vAxis: {
                    title: 'Number'
                }
            };

            var chart = new google.charts.Bar(document.getElementById('barchart'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        function drawLineChart() {
        var data = google.visualization.arrayToDataTable([
            ['Event', 'Amount'],
            <?php
            include 'dbconnect.php';
            $line_qry = "SELECT CONCAT(recycle_event.event_name, ' (', DATE_FORMAT(recycle_event.event_date, '%d-%m-%Y'), ')') as event_label, COUNT(donate_item.donate_id) as total_amount 
                         FROM recycle_event 
                         JOIN donate_item ON recycle_event.event_id = donate_item.event_id 
                         GROUP BY recycle_event.event_name, recycle_event.event_date";
            $line_result = mysqli_query($conn, $line_qry);

            while ($line_row = mysqli_fetch_array($line_result)) {
                echo "['" . $line_row["event_label"] . "', " . $line_row["total_amount"] . "],";
            }
            ?>
        ]);

        var options = {
            title: 'Donation Amount by Event',
            hAxis: {title: 'Event'},
            vAxis: {title: 'Amount'},
            curveType: 'function',
            legend: { position: 'right' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('linechart'));
        chart.draw(data, options);
    }
    </script>
    <style>
        .activity {
    position: relative;
    padding-left: 10px;
}

.activity-item {
    position: relative;
    padding: 20px 0;
}

.activity-item::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #ddd;
}

.activity-item:first-child::before {
    top: 15px;
}

.activity-item:last-child::before {
    bottom: 15px;
}

.activity-label {
    position: relative;
    padding-left: 20px;
    display: flex;
    align-items: center;
}

.activity-badge {
    position: absolute;
    left: -5px;
    z-index: 1;
    background-color: white; /* Optional: adds a white background to the icon */
    border-radius: 50%;
    padding: 2px;
}

.connector {
    position: absolute;
    top: 50%;
    left: 9px;
    width: 2px;
    height: 100%;
    background-color: #ddd;
}

    </style>
</head>

<body>

    <div id="wrapper">
        <?php include 'includes/nav.php' ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12 breadcrumb-container">
                    <h1 class="page-header">Admin Dashboard</h1>
                    <ol class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Admin Dashboard</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!---total --->
            <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="icofont-meeting-add fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <?php include 'counter/eventcount.php';?> 
                                <div>Total Events</div>
                            </div>
                        </div>
                    </div>
                    <a href="viewevent.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="icofont-truck-alt icofont-6x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <?php include 'counter/lorrycount.php';?> 
                                <div>Available Lorry</div>
                            </div>
                        </div>
                    </div>
                    <a href="viewlorry.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
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
                                <i class="icofont-girl icofont-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <?php include 'counter/womencount.php';?> 
                                <div>Available Women Clothes</div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="icofont-boy icofont-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <?php include 'counter/mencount.php';?> 
                                <div>Available Men Clothes</div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-9">
                    <div id="content">
                        <div class="container-fluid">
                            <div class="row-fluid">
                                <div class="span12">
                                    <div id="barchart" style="width: 690px; height: 320px; margin-left:auto; margin-right:auto;"></div>
                                    <div id="linechart" style="width: 690px; height: 320px; margin-left:auto; margin-right:auto;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Right side columns UPCOMING EVENTS -->
                <div class="col-lg-3" style="border: 2px solid #ddd;">
                <!-- Upcoming Events -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Upcoming Recycling Events</h5>
                        <div class="activity">
                            <?php
                            include 'dbconnect.php';

                            $query = "SELECT * FROM recycle_event WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 5";
                            $result = $conn->query($query);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $event_date_str = $row['event_date'];
                                    $event_date = strtotime($event_date_str);
                                    $current_time = time();
                                    $time_until_event = $event_date - $current_time;
                                    $days_until_event = floor($time_until_event / (3600 * 24));

                                    $formatted_event_date = date('d-m-Y', $event_date);
                                    $label = '';
                                    $badge_color = '';

                                    if ($days_until_event > 1) {
                                        $label = $days_until_event . ' days from now (<span class="text-primary font-weight-bold">' . $formatted_event_date . '</span>)';
                                        $badge_color = 'text-primary';
                                    } elseif ($days_until_event == 1) {
                                        $label = '1 day from now (<span class="text-warning font-weight-bold">' . $formatted_event_date . '</span>)';
                                        $badge_color = 'text-warning';
                                    } else {
                                        $label = 'Today (<span class="text-success font-weight-bold">' . $formatted_event_date . '</span>)';
                                        $badge_color = 'text-success';
                                    }
                                    ?>

                                    <div class="activity-item d-flex">
                                        <div class="activity-label"><i class="bi bi-circle-fill activity-badge <?php echo $badge_color; ?> align-self-start"></i> <?php echo $label; ?></div>
                                        <div class="activity-content">
                                            &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['event_name']; ?> <br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;at <?php echo $row['event_loc']; ?>
                                        </div>
                                    </div><!-- End activity item-->
                            <?php
                                }
                            } else {
                                echo "<p>No upcoming events found.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>




                <!-- /.col-lg-3 -->
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

    <!-- Morris Charts JavaScript -->
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script>

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
