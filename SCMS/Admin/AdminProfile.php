<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Initialize status message variable
$statusMsg = '';

//------------------------SAVE--------------------------------------------------
if (isset($_POST['save'])) {
    $AdminName = $_POST['AdminName'];
    $AdminIc = $_POST['AdminIc'];
    $AdminEmail = $_POST['AdminEmail'];

    // Check if the admin already exists
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE AdminName ='$AdminName' AND AdminEmail= '$AdminEmail'");
    $ret = mysqli_fetch_array($query);

    if ($ret > 0) {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Admin Already Exists!</div>";
    } else {
        // Insert new admin
        $query = mysqli_query($conn, "INSERT INTO admin (AdminName, AdminIc, AdminEmail) VALUES ('$AdminName', '$AdminIc', '$AdminEmail')");

        if ($query) {
            $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//------------------------EDIT--------------------------------------------------
if (isset($_GET['AdminID']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $AdminID = $_GET['AdminID'];

    // Fetch admin data
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE AdminID ='$AdminID'");
    $row = mysqli_fetch_array($query);

    //------------UPDATE-----------------------------
    if (isset($_POST['update'])) {
        $AdminName = $_POST['AdminName'];
        $AdminIc = $_POST['AdminIc'];
        $AdminEmail = $_POST['AdminEmail'];

        $query = mysqli_query($conn, "UPDATE admin SET AdminName='$AdminName', AdminIc='$AdminIc', AdminEmail='$AdminEmail' WHERE AdminID='$AdminID'");

        if ($query) {
            echo "<script type='text/javascript'>window.location = ('adminProfile.php');</script>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//--------------------CANCEL EDIT------------------------------------------------
if (isset($_POST['cancel'])) {
    echo "<script type='text/javascript'>window.location = ('adminProfile.php');</script>";
}

//--------------------DELETE-----------------------------------------------------
if (isset($_GET['AdminID']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $AdminID = $_GET['AdminID'];

    $query = mysqli_query($conn, "DELETE FROM admin WHERE AdminID='$AdminID'");

    if ($query == TRUE) {
        echo "<script type='text/javascript'>window.location = ('adminProfile.php');</script>";
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/logo/attnlg.jpg" rel="icon">
    <?php include 'includes/title.php';?>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include "Includes/sidebar.php";?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include "Includes/topbar.php";?>
                <!-- Topbar -->

                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Admin Profile</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Admin Profile</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Basic -->
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Edit Profile</h6>
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Admin Username<span class="text-danger ml-2">*</span></label>
                                                <input type="text" name="AdminName" class="form-control" value="<?php echo isset($row['AdminName']) ? $row['AdminName'] : ''; ?>" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Admin Ic Number<span class="text-danger ml-2">*</span></label>
                                                <input type="password" name="AdminIc" class="form-control" value="<?php echo isset($row['AdminIc']) ? $row['AdminIc'] : ''; ?>" required pattern="\d{12}" title="Ic number must be 12 numeric" maxlength="12">
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                                                <input type="email" name="AdminEmail" class="form-control" value="<?php echo isset($row['AdminEmail']) ? $row['AdminEmail'] : ''; ?>" required>
                                            </div>
                                    </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-12">
                                                <?php
                                                if (isset($AdminID))
                                                {
                                                ?>
                                                <button type="submit" name="update" class="btn btn-warning">Update</button>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button>
                                                <?php
                                                } else {           
                                                ?>
                                                <button type="submit" name="save" class="btn btn-primary">Save</button>
                                                <?php
                                                }         
                                                ?>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Display Admin Data -->
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">All Admins</h6>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Admin Name</th>
                                                <th>Admin Ic Number</th>
                                                <th>Admin Email</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM admin";
                                            $result = mysqli_query($conn, $query);
                                            $num = mysqli_num_rows($result);
                                            $sn = 0;
                                            if ($num > 0) {
                                                while ($rows = mysqli_fetch_assoc($result)) {
                                                    $sn++;
                                                    echo "
                                                    <tr>
                                                        <td>".$sn."</td>
                                                        <td>".$rows['AdminName']."</td>
                                                        <td>".$rows['AdminIc']."</td>
                                                        <td>".$rows['AdminEmail']."</td>
                                                        <td><a href='?action=edit&AdminID=".$rows['AdminID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                                        <td><a href='?action=delete&AdminID=".$rows['AdminID']."' style='color:red;' onclick='return confirm(\"Do you really want to delete?\");'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                                    </tr>";
                                                }
                                            } else {
                                                echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!---Container Fluid-->
            </div>
            <!-- Footer -->
            <?php include "Includes/footer.php";?>
            <!-- Footer -->
        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
    $(document).ready(function () {
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>
