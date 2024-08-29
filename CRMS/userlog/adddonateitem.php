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
</head>
<body>
    <div id="wrapper">
        <?php include 'includes/donornav.php'?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12 breadcrumb-container">
                    <h1 class="page-header">Add Donate Item</h1>
                    <ol class="breadcrumb">
                        <li><a href="userdashboard.php">Home</a></li>
                        <li class="active">Add donation</li>
                    </ol>
                </div>
            </div>
            <div class="row">
            <div class="col-lg-12">
                    <div class="row" style="border: 2px solid #ddd; padding: 10px;">
                        <?php
                        $qry = "SELECT * FROM item_category";
                        $result = mysqli_query($con, $qry);
                        while ($row = mysqli_fetch_array($result)) {
                            $imagePath = '../pages/images/' . $row['item_image'];
                            echo "
                            <div class='col-sm-6' style='margin-bottom: 10px;'>
                                <img src='" . $imagePath . "' alt='" . $row['item_name'] . "' width='30%' style='border: 1px solid #ddd; padding: 3px;'>
                                <label>" . $row['item_name'] . "</label>
                            </div>";
                        }
                        ?>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        Please fill up the form below:
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <?php
                                        include '../dbcon.php';
                                        $event_id = $_GET['event_id'];
                                        $qry = "SELECT * FROM recycle_event WHERE event_id='$event_id'";
                                        $result = mysqli_query($con, $qry);
                                        while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <form role="form" action="addeddonate.php" method="post" >
                                        <div class="form-group">
                                            <label>Event Name</label>
                                            <input class="form-control" type="text" name="event_name" value='<?php echo $row['event_name']; ?>' readonly required>
                                        </div>
                                        <div class="form-group">
                                            <label for="filterBrand" class="mr-2">Select Items To Donate: </label>
                                            <div>
                                                <?php
                                                $item = $con->query("SELECT * FROM item_category");
                                                while ($i = $item->fetch_assoc()) {
                                                    ?>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="item_id[]" value="<?php echo $i['item_id'] ?>">
                                                            <?php echo $i['item_name'] ?>
                                                        </label>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                 <label for="filterBrand" class="mr-2">(You can tick more than 1 )</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Clothes Condition: </label>
                                            <select class="form-control" name="cloth_condition" required>
                                                <option value="">--- Select Status---</option>
                                                <option value="Brand New">Brand New</option>
                                                <option value="Good Condition">Good Condition</option>
                                                <option value="Average Condition">Average Condition</option>
                                            </select>
                                        </div>

                                        <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                        <button type="submit" class="btn btn-success">Add Item</button>
                                    </form>
                                    <?php } ?>
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
