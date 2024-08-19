<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = ''; // Initialize status message variable

if(isset($_POST['save'])){
    $ClubID = $_POST['ClubID'];
    $ActivityName = $_POST['ActivityName'];
    $ActivityVenue = $_POST['ActivityVenue'];
    $ActivityMark = $_POST['ActivityMark'];
    $ActivityDate = $_POST['ActivityDate']; // Get the selected date from the form

     // Convert ActivityDate to d-m-Y format before storing it in the database
    $ActivityDate = date('d-m-Y', strtotime($ActivityDate));

    // Check if the activity already exists
    $query = "SELECT * FROM activity WHERE ClubID = '$ClubID' AND ActivityName = '$ActivityName'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){ 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Activity Already Exists!</div>";
    }
    else{
        // Insert new activity
        $insertQuery = "INSERT INTO activity (ClubID, ActivityName, ActivityVenue, ActivityMark, ActivityDate) VALUES ('$ClubID', '$ActivityName', '$ActivityVenue', '$ActivityMark', '$ActivityDate')";
        if (mysqli_query($conn, $insertQuery)) {
            $statusMsg = "<div class='alert alert-success'  style='margin-right:700px;'>Created Successfully!</div>";
        }
        else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

// Retrieve the club ID of the teacher from the session
$teacherID = $_SESSION['userId'];
$clubQuery = "SELECT ClubID FROM registerteacher WHERE TeacherID = '$teacherID'";
$clubResult = mysqli_query($conn, $clubQuery);
$clubRow = mysqli_fetch_assoc($clubResult);
$teacherClubID = $clubRow['ClubID'];

// Initialize variables for edit mode
$ActivityID = '';
$ClubID = '';
$ActivityName = '';
$ActivityVenue = '';
$ActivityMark = '';
$ActivityDate = ''; // Initialize ActivityDate for edit mode

//--------------------EDIT------------------------------------------------------------
if (isset($_GET['ActivityID']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $ActivityID = $_GET['ActivityID'];

    $query = mysqli_query($conn, "SELECT * FROM activity WHERE ActivityID ='$ActivityID'");
    $row = mysqli_fetch_array($query);

    $ClubID = $row['ClubID'];
    $ActivityName = $row['ActivityName'];
    $ActivityVenue = $row['ActivityVenue'];
    $ActivityMark = $row['ActivityMark'];
    $ActivityDate = date('d-m-Y', strtotime($row['ActivityDate'])); // Convert to Y-m-d format for the form

    //------------UPDATE-----------------------------
    if (isset($_POST['update'])) {
        $ClubID = $_POST['ClubID'];
        $ActivityName = $_POST['ActivityName'];
        $ActivityVenue = $_POST['ActivityVenue'];
        $ActivityMark = $_POST['ActivityMark'];
        $ActivityDate = $_POST['ActivityDate']; // Get the selected date from the form

         // Convert ActivityDate to d-m-Y format before updating it in the database
        $ActivityDate = date('d-m-Y', strtotime($ActivityDate));

        $query = mysqli_query($conn, "UPDATE activity SET ClubID = '$ClubID', ActivityName = '$ActivityName', ActivityVenue = '$ActivityVenue', ActivityMark = '$ActivityMark', ActivityDate = '$ActivityDate' WHERE ActivityID = '$ActivityID'");

        if ($query) {
            echo "<script type = \"text/javascript\">
            window.location = (\"CreateActivity.php\")
            </script>"; 
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//--------------------CANCEL EDIT------------------------------------------------

if (isset($_POST['cancel'])) {
    echo "<script type = \"text/javascript\">
    window.location = (\"CreateActivity.php\")
    </script>"; 
}

//--------------------------------DELETE------------------------------------------------------------------
if (isset($_GET['ActivityID']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $ActivityID = $_GET['ActivityID'];

    $query = mysqli_query($conn, "DELETE FROM activity WHERE ActivityID='$ActivityID'");

    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">
        window.location = (\"CreateActivity.php\")
        </script>";  
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
            <h1 class="h3 mb-0 text-gray-800">Create Activity</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Activity</li>
            </ol>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
<div class="card mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Create Activity</h6>
        <?php echo $statusMsg; ?>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="form-group row mb-3">
                <div class="col-xl-6">
                <label class="form-control-label">Your Club <span class="text-danger ml-2">*</span></label>
                <select required name="ClubID" class="form-control mb-3">
                    <?php
                    // Retrieve club names from the Club table based on the teacher's club ID
                    $clubQuery = "SELECT * FROM Club WHERE ClubID = '$teacherClubID' ORDER BY ClubName ASC";
                    $clubResult = mysqli_query($conn, $clubQuery);
                    while ($clubRow = mysqli_fetch_assoc($clubResult)){
                        $selected = ($clubRow['ClubID'] == $ClubID) ? 'selected' : '';
                        echo '<option value="'.$clubRow['ClubID'].'" '.$selected.'>'.$clubRow['ClubName'].'</option>';
                    }
                    ?>
                </select>
            </div>
                <div class="col-xl-6">
                    <label class="form-control-label">Activity Name<span class="text-danger ml-2">*</span></label>
                    <input type="text" class="form-control" name="ActivityName" id="exampleInputActivityName" placeholder="Activity Name" required pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed" value="<?php echo $ActivityName; ?>">
                </div>
                <div class="col-xl-6">
                    <label class="form-control-label">Activity Venue<span class="text-danger ml-2">*</span></label>
                    <input type="text" class="form-control" name="ActivityVenue" id="exampleInputActivityVenue" placeholder="Activity Venue" required="" value="<?php echo $ActivityVenue; ?>">
                </div>
                <div class="col-xl-6">
                    <label class="form-control-label">Activity Mark<span class="text-danger ml-2">*</span></label>
                    <input type="text" class="form-control" name="ActivityMark" id="exampleInputActivityMark" placeholder="Activity Mark" required pattern="\d{1,100}" title="Phone number must be 1 until 100 numeric" minlength="1" maxlength="3" value="<?php echo $ActivityMark; ?>">
                </div>
                <div class="col-xl-6">
                    <label class="form-control-label">Activity Date<span class="text-danger ml-2">*</span></label>
                    <input type="date" class="form-control" name="ActivityDate" id="exampleInputActivityDate" required value="<?php echo $ActivityDate; ?>">
                </div>
            </div>
                    <?php
                    if (isset($ActivityID) && !empty($ActivityID)) {
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
        </form>
    </div>
</div>
              <!-- Input Group -->
                 <div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">All Activity</h6>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Club Name</th>
                        <th>Activity Name</th>
                        <th>Activity Venue</th>
                        <th>Activity Date</th>
                        <th>Activity Mark</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                   <tbody>
                    <?php
                    // Fetch the teacher's ClubID
                    $teacherID = $_SESSION['userId'];
                    $query = "SELECT club.ClubID 
                              FROM registerteacher
                              INNER JOIN club ON club.ClubID = registerteacher.ClubID
                              WHERE registerteacher.TeacherID = '$teacherID'";
                    $rs = mysqli_query($conn, $query);
                    $rrw = mysqli_fetch_assoc($rs);
                    $teacherClubID = $rrw['ClubID'];

                    // Fetch activities that belong to the teacher's club
                    $query = "SELECT activity.ActivityID, club.ClubName, activity.ActivityName, activity.ActivityVenue, activity.ActivityDate, activity.ActivityMark
                              FROM activity
                              INNER JOIN club ON club.ClubID = activity.ClubID
                              WHERE activity.ClubID = '$teacherClubID'";
                    $rs = $conn->query($query);
                    $sn = 0;
                    if ($rs && $rs->num_rows > 0) {
                        while ($rows = $rs->fetch_assoc()) {
                            $sn++;
                            echo "<tr>
                                    <td>".$sn."</td>
                                    <td>".$rows['ClubName']."</td>
                                    <td>".$rows['ActivityName']."</td>
                                    <td>".$rows['ActivityVenue']."</td>
                                    <td>".$rows['ActivityDate']."</td>
                                    <td>".$rows['ActivityMark']."</td>
                                    <td><a href='?action=edit&ActivityID=".$rows['ActivityID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                    <td><a href='?action=delete&ActivityID=".$rows['ActivityID']."' style='color:red;' onclick='return confirm(\"Do you really want to delete?\");'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr>
                                <td colspan='9'>No Record Found!</td>
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
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>
</html>
