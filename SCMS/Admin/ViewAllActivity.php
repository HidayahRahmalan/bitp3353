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
    $ActivityDate = date("Y-m-d");
   
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



//--------------------EDIT------------------------------------------------------------

 if (isset($_GET['ActivityID']) && isset($_GET['action']) && $_GET['action'] == "edit")
    {
        $ActivityID= $_GET['ActivityID'];

        $query=mysqli_query($conn,"select * from activity where ActivityID ='$ActivityID'");
        $row=mysqli_fetch_array($query);

        //------------UPDATE-----------------------------

        if(isset($_POST['update'])){
    
          $ClubID=$_POST['ClubID'];
          $ActivityName=$_POST['ActivityName'];
          $ActivityVenue=$_POST['ActivityVenue'];
          $ActivityMark=$_POST['ActivityMark'];
          $ActivityDate=date("Y-m-d");
          // $ActivityTime=$_POST['ActivityTime'];

            $query=mysqli_query($conn,"update activity set ClubID = '$ClubID', ActivityName='$ActivityName', ActivityVenue='$ActivityVenue', ActivityMark='$ActivityMark' where ActivityID='$ActivityID'");

            if ($query) {
                
                echo "<script type = \"text/javascript\">
                window.location = (\"ViewAllActivity.php\")
                </script>"; 
            }
            else
            {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
            }
        }
    }


//--------------------------------DELETE------------------------------------------------------------------

  if (isset($_GET['ActivityID']) && isset($_GET['action']) && $_GET['action'] == "delete")
    {
        $ActivityID= $_GET['ActivityID'];

        $query = mysqli_query($conn,"DELETE FROM activity WHERE ActivityID='$ActivityID'");

        if ($query == TRUE) {

                echo "<script type = \"text/javascript\">
                window.location = (\"ViewAllActivity.php\")
                </script>";  
        }
        else{

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
            <h1 class="h3 mb-0 text-gray-800">View All Activity</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View All Activity</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Filter Form -->
              <form method="GET" action="" class="form-inline mb-4">
                <div class="form-group mr-2">
                  <label for="clubID" class="mr-2">Select Club:</label>
                  <select class="form-control form-control-sm" id="clubID" name="clubID">
                    <option value="">All</option>
                    <?php
                    // Fetch clubs from the database
                    $clubQuery = "SELECT ClubID, ClubName FROM club";
                    $clubResult = $conn->query($clubQuery);
                    if ($clubResult->num_rows > 0) {
                        while ($clubRow = $clubResult->fetch_assoc()) {
                            echo "<option value='".$clubRow['ClubID']."'>".$clubRow['ClubName']."</option>";
                        }
                    }
                    ?>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
              </form>

              <!-- Table to Display Activities -->
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
                        <th>Venue</th>
                        <th>Date</th>
                        <th>Mark</th>
                        <th>Student List</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // Get the selected club ID from the filter form
                      $selectedClubID = isset($_GET['clubID']) ? $_GET['clubID'] : '';

                      // Modify the query to include the selected club filter
                      $query = "SELECT activity.ActivityID, club.ClubName, activity.ActivityName, activity.ActivityVenue, activity.ActivityDate, activity.ActivityMark
                                FROM activity
                                INNER JOIN club ON club.ClubID = activity.ClubID";

                      if ($selectedClubID != '') {
                          $query .= " WHERE club.ClubID = '$selectedClubID'";
                      }

                      $query .=  " ORDER BY STR_TO_DATE(activity.ActivityDate, '%d-%m-%Y') DESC";
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
                                      <td><a href='ViewActivityStudent.php?ActivityID=".$rows['ActivityID']."' id='ActivityStudent'>View Students</a></td>
                                    </tr>";
                          }
                      } else {
                          echo "<tr>
                                  <td colspan='7'>No Record Found!</td>
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
