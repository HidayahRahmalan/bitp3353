<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Driver - CRMS</title>
    <link href="../img/clothes-donation.png" rel="icon">
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

        <?php include 'includes/drivernav.php'; include '../dbcon.php'?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12 breadcrumb-container">
                    <h1 class="page-header">Add Duty</h1>
                    <ol class="breadcrumb">
                        <li><a href="driverdashboard.php">Home</a></li>
                        <li class="active">Duty</li>
                    </ol>
                </div>
            </div>

            <div class="row">
            <div class="col-lg-3">
                                    <div class="row" style="border: 2px solid #ddd; padding: 10px;">
                                        <div class="col-sm-6">
                                            <img src="img/lorry1.jpeg" alt="Lorry Image 1" width="100%">
                                            <label>PQT 1718</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <img src="img/lorry2.jpeg" alt="Lorry Image 2" width="100%">
                                            <label>WB 3475</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <img src="img/lorry3.jpg" alt="Lorry Image 3" width="100%">
                                            <label>MDG2839</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <img src="img/lorry4.jpg" alt="Lorry Image 3" width="100%">
                                            <label>PMS6495</label>
                                        </div>
                                    </div>
                                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Please fill up the form below:
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-9">
                                    <form role="form" action="addedduty.php" method="post" onsubmit="return validateTime()">
                                        <div class="form-group">
                                            <label for="filterBrand" class="mr-2">Lorry Plate Number: </label>
                                            <select name="lorry_id" class="form-control" required>
                                                <option value="">--- Select Lorry---</option>
                                                <?php
                                                $lorry = $con->query("SELECT * FROM pickup_lorry");
                                                while ($l = $lorry->fetch_assoc()) {
                                                    $ev[$l['lorry_id']] = $l['plate_number'];
                                                ?>
                                                    <option value="<?php echo $l['lorry_id'] ?>">
                                                        <?php echo $l['plate_number'] ?>
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Duty Date</label>
                                            <input class="form-control" type="date" name="duty_date" min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Duty Time</label>
                                            <input class="form-control" type="time" id="duty_time" name="duty_time" min="07:00" max="23:59" required>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-default" style="border-radius: 0%;">Submit Form</button>
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
    // JavaScript fallback for browsers not supporting HTML5 date input validation
    var today = new Date().toISOString().split('T')[0];
    document.getElementsByName("duty_date")[0].setAttribute('min', today);
</script>
    
    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <script>
        function validateTime() {
            var dutyTime = document.getElementById("duty_time").value;
            var dutyTimeHours = parseInt(dutyTime.split(':')[0]);

            if (dutyTimeHours < 7 || dutyTimeHours > 23) {
                alert("Duty time must be between 7 AM and 12 AM.");
                return false;
            }
            return true;
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
     
    }