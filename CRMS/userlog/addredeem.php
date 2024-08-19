<?php 
include "session.php";
include '../dbcon.php';

$session_id = $_SESSION['user_id']; // Ensure the session user_id is retrieved

// Query to get the total available points
$sql = "SELECT donate_id, item_point FROM donate_item WHERE point_redeem = 'No' AND user_id = ? ORDER BY donate_id ASC";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $session_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
$total_points = 0;

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $total_points += $row['item_point'];
}

if (isset($_POST['submit'])) {
    $total_point        = $_POST['total_point'];
    $total_ringgit      = $_POST['total_ringgit'];
    $bank_name          = $_POST['bank_name'];
    $account_num        = $_POST['account_num'];
    $approve_status     = 'To be approved';
    $user_id            = $_SESSION['user_id'];

    // Insert redemption data into the point_redeem table
    $sql = "INSERT INTO `point_redeem` (total_point, total_ringgit, bank_name, account_num, approve_status, user_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("idsssi", $total_point, $total_ringgit, $bank_name, $account_num, $approve_status, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $points_to_update = $total_point;

        // Update donate_item table
        foreach ($items as $item) {
            if ($points_to_update <= 0) {
                break;
            }

            $donate_id = $item['donate_id'];
            $item_points = $item['item_point'];

            if ($item_points <= $points_to_update) {
                // Mark this item as redeemed
                $update_sql = "UPDATE donate_item SET point_redeem = 'Yes' WHERE donate_id = ?";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bind_param("i", $donate_id);
                $update_stmt->execute();

                $points_to_update -= $item_points;
            } else {
                // If the current item's points are more than the remaining points to redeem, stop the process
                break;
            }
        }

        echo '<script>alert("Successfully redeemed. Your money will take 2-3 working days to be processed.")</script>';
        echo '<script> window.location.href = "addredeem.php";</script>';
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
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <div id="wrapper">
        <?php include 'includes/donornav.php'; ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12 breadcrumb-container">
                    <h1 class="page-header">Redeem Your Points</h1>
                    <ol class="breadcrumb">
                        <li><a href="userdashboard.php">Home</a></li>
                        <li class="active">Redeem</li>
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
                                            <label for="availablePoint" class="mr-2"> Available Points: </label>
                                            <input class="form-control" type="text" id="availablePoint" value="<?php echo $total_points; ?>" readonly>
                                            <small class="form-text text-muted">Note that 20 points equal to RM1.00</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="totalPoint" class="mr-2"> Total Points To Redeem: </label>
                                            <input class="form-control" type="number" id="totalPoint" name="total_point" min="100" max="<?php echo $total_points; ?>" required oninput="calculateMYR()">
                                            <small class="form-text text-muted">Minimum amount to redeem is 100.</small><br>
                                            <small class="form-text text-muted">You can redeem 100,200,300,400</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="totalRinggit" class="mr-2"> Total in MYR: </label>
                                            <input class="form-control" type="text" id="totalRinggit" name="total_ringgit" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="bankName" class="mr-2"> Bank Name: </label>
                                            <select class="form-control" id="bankName" name="bank_name" required>
                                                <option value="">--- Select Bank ---</option>
                                                <option value="Affin Bank">Affin Bank</option>
                                                <option value="Maybank">Maybank</option>
                                                <option value="Bank Islam">Bank Islam</option>
                                                <option value="RHB">RHB</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="accountNum" class="mr-2"> Account Number: </label>
                                            <input class="form-control" type="text" id="accountNum" name="account_num" maxlength="17" required>
                                        </div>

                                        <button type="submit" id="submitBtn" name="submit" class="btn btn-success btn-default" style="border-radius: 0%;">Submit Form</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    <script src="../dist/js/sb-admin-2.js"></script>

    <script>

         // Function to disable inputs if available points are less than 100
    function checkAvailablePoints() {
        var availablePoints = parseInt(document.getElementById('availablePoint').value);
        if (availablePoints < 100) {
            document.getElementById('totalPoint').disabled = true;
            document.getElementById('totalRinggit').disabled = true;
            document.getElementById('bankName').disabled = true;
            document.getElementById('accountNum').disabled = true;
            document.getElementById('submitBtn').disabled = true;
        }
    }

    // Run the function when the page loads
    window.onload = checkAvailablePoints;

        function calculateMYR() {
        const points = document.getElementById('totalPoint').value;
        const myr = points / 20;
        document.getElementById('totalRinggit').value = myr.toFixed(2); // Round to 2 decimal places
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
