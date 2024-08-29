<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <?php include 'session.php';
        include 'includes/donornav.php'; ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 breadcrumb-container">
                        <marquee style="font-size: 20px; color: black;">Please Come to the DropOff location accordingly.</marquee>
                        <h1 class="page-header">List of Donate Item</h1>
                        <ol class="breadcrumb">
                            <li><a href="userdashboard.php">Home</a></li>
                            <li class="active">Donate Item</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class=".col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Total Records of To be Pickup Item
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="donate-items-table">
                                        <thead>
                                            <tr>
                                                
                                                <th>Item Name</th>
                                                <th>Event Name</th>
                                                <th>Event Date</th>
                                                <th>Drop-Off Location</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Pickup Status</th>
                                                <th>Point Received</th>
                                                <th>Item Dropoff</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            include "../dbcon.php";
                                            $qry = "SELECT donate_item.donate_id,donate_item.item_id, donate_item.event_id, donate_item.item_point, recycle_event.event_name, recycle_event.event_loc, recycle_event.event_date, recycle_event.event_start_time, recycle_event.event_end_time, item_category.item_name, pickup_session.pickup_status,pickup_session.dropoff_destination 
                                                    FROM donate_item 
                                                    JOIN item_category ON donate_item.item_id = item_category.item_id
                                                    JOIN recycle_event ON donate_item.event_id = recycle_event.event_id 
                                                    LEFT JOIN pickup_session ON donate_item.event_id = pickup_session.event_id
                                                    WHERE pickup_session.pickup_status = 'To Be Pickup' AND donate_item.user_id = $session_id";
                                            $result = mysqli_query($con, $qry);
                                            while ($row = mysqli_fetch_array($result)) {
                                                $formatted_date = date('d-m-Y', strtotime($row['event_date']));
                                                echo "<tr class='gradeA'>
                                                
                                                <td>" . $row['item_name'] . "</td>
                                                <td>" . $row['event_name'] . "</td>
                                                <td>" . $formatted_date . "</td>
                                                <td>" . $row['event_loc'] . "</td>
                                                <td>" . $row['event_start_time'] . "</td>
                                                <td>" . $row['event_end_time'] . "</td>
                                                <td>" . $row['pickup_status'] . "</td>
                                                <td>" . $row['item_point'] . "</td>
                                                <td>" . $row['dropoff_destination'] . "</td>
                                                <td><a href='delete_donateitem.php?donate_id=" . $row['donate_id'] . "' onclick='return confirmDelete();' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></a></td>
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

                <!-- Second Table -->
                <div class="container-fluid">
                    <div class="row">
                        <div class=".col-lg-12">
                            <h1 class="page-header">Donate Item History</h1>
                        </div>
                    </div>

                    <div class="row">
                        <div class=".col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Total Records of Item
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="donate-history-table">
                                            <thead>
                                                <tr>
                                                    <th>Item Name</th>
                                                    <th>Event Name</th>
                                                    <th>Event Date</th>
                                                    <th>Location</th>
                                                    <th>Pickup Status</th>
                                                    <th>Item Dropoff</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                include "../dbcon.php";
                                                $qry = "SELECT donate_item.item_id, donate_item.event_id, recycle_event.event_name, recycle_event.event_loc, recycle_event.event_date, item_category.item_name, pickup_session.pickup_status,pickup_session.dropoff_destination
                                                        FROM donate_item 
                                                        JOIN item_category ON donate_item.item_id = item_category.item_id
                                                        JOIN recycle_event ON donate_item.event_id = recycle_event.event_id 
                                                        LEFT JOIN pickup_session ON donate_item.event_id = pickup_session.event_id
                                                        WHERE pickup_session.pickup_status = 'Already Pickup' AND donate_item.user_id = $session_id";
                                                $result = mysqli_query($con, $qry);
                                                while ($row = mysqli_fetch_array($result)) {
                                                    $formatted_date = date('d-m-Y', strtotime($row['event_date']));
                                                    echo "<tr class='gradeA'>
                                                    <td>" . $row['item_name'] . "</td>
                                                    <td>" . $row['event_name'] . "</td>
                                                    <td>" . $formatted_date . "</td>
                                                    <td>" . $row['event_loc'] . "</td>
                                                    <td>" . $row['pickup_status'] . "</td>
                                                    <td>" . $row['dropoff_destination'] . "</td>
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

        <script>
            $(document).ready(function() {
                $('#donate-items-table').DataTable({
                    responsive: true
                });
                $('#donate-history-table').DataTable({
                    responsive: true
                });

                // Select/Deselect all checkboxes
                $('#select-all').click(function() {
                    $('.select-item').prop('checked', this.checked);
                });

                $('.select-item').change(function() {
                    if (!this.checked) {
                        $('#select-all').prop('checked', false);
                    }
                });
            });
        </script>
        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this donation?");
            }
            </script>
    </div>

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
</body>

</html>
