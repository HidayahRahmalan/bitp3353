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
    <link rel="stylesheet" href="../icofont/icofont.min.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Custom Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .printable, .printable * {
                visibility: visible;
            }
            .printable {
                position: absolute;
                left: 0;
                top: 2;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <?php include 'includes/nav.php'?>

        <div id="page-wrapper">
            <div class="row">
            <div class="col-lg-12 breadcrumb-container ">
                    
            <h2 class="page-header printable">Donation Summary Based on Location</h2>
                            <ol class="breadcrumb">
                                <li><a href="index.php">Home</a></li>
                                <li class="active">Report</li>
                            </ol>
                        </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <form id="searchForm" class="">
                    
                        <div class="form-group">
                            <label for="event_name">Select Event Name:</label>
                            <select class="form-control" id="event_name" name="event_name">
                                <option value="">Select an event name</option>
                                <!-- Options will be populated here by PHP -->
                                <?php
                                include 'dbconnect.php';

                                // Check connection
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                // Fetch unique event names
                                $sql = "SELECT DISTINCT event_name FROM recycle_event";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row["event_name"] . "'>" . $row["event_name"] . "</option>";
                                    }
                                }
                                $conn->close();
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cloth_condition">Select Cloth Condition:</label>
                            <select class="form-control" id="cloth_condition" name="cloth_condition">
                                <option value="">Select a cloth condition</option>
                                <!-- Options for cloth condition -->
                                <option value="Brand New">Brand New</option>
                                <option value="Never Use">Never Use</option>
                                <option value="Good Condition">Good Condition</option>
                                <option value="Average Condition">Average Condition</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="searchEvents()">Search</button>
                     <button type='button' class='btn btn-success' onclick='printReport()'>Print</button>
                    </form>
            
                    <br>
                    <div id="totalCount" class="printable"></div>
                    <!-- Placeholder for total count -->
                    <div id="totalCount" class="printable"></div>
                    <br>
                    <table class="table table-bordered printable" id="resultsTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Item Name</th>
                                <th>Conditions</th>
                                <th>Gender</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Results will be displayed here -->
                        </tbody>
                    </table>
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
    <!-- Morris Charts JavaScript -->
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Custom JavaScript -->
    <script>
    function searchEvents() {
        var name = $('#event_name').val();
        var condition = $('#cloth_condition').val(); // Get the selected cloth condition

        if (name || condition) { // Ensure at least one filter is applied
            $.ajax({
                url: 'reportsearchdonate.php',
                type: 'GET',
                data: { event_name: name, cloth_condition: condition }, // Pass both parameters
                success: function(response) {
                    $('#resultsTable tbody').html(response);
                    // Update the total count display
                    var totalCount = $(response).filter('tr').length;
                    $('#totalCount').html("Total Results: " + totalCount);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
    }


    function printReport() {
        window.print();
    }
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
</style>
</html>
