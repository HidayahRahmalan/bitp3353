<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = ''; // Initialize status message variable

$selectedActivityID = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    // Retrieve the selected ActivityID from the POST request
    $selectedActivityID = $_POST['ActivityID'];
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
            <h1 class="h3 mb-0 text-gray-800">View Activity</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Activity</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Activity</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Club Type<span class="text-danger ml-2">*</span></label>
                        <select required name="ClubTypeID" id="ClubTypeID" class="form-control mb-3">
                          <option value="">--Select Club Type--</option>
                          <?php
                          $qry = "SELECT * FROM clubtype ORDER BY ClubTypeName ASC";
                          $result = mysqli_query($conn, $qry);
                          while ($row = mysqli_fetch_assoc($result)){
                            echo '<option value="'.$row['ClubTypeID'].'" >'.$row['ClubTypeName'].'</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Club<span class="text-danger ml-2">*</span></label>
                        <select required name="ClubID" id="ClubID" class="form-control mb-3">
                          <option value="">--Select Club--</option>
                        </select>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Activity<span class="text-danger ml-2">*</span></label>
                        <select required name="ActivityID" id="ActivityID" class="form-control mb-3">
                          <option value="">--Select Activity--</option>
                        </select>
                      </div>
                    </div>
                    <button type="submit" name="search" class="btn btn-primary">Search</button>
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
                            <th>Venue</th>
                            <th>Date</th>
                            <th>Mark</th>
                            <th>Student List</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = "SELECT activity.ActivityID, club.ClubName, activity.ActivityName, activity.ActivityVenue, activity.ActivityDate, activity.ActivityMark
                                    FROM activity
                                    INNER JOIN club ON club.ClubID = activity.ClubID";
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
                                      <td><a href='?action=edit&ActivityID=".$rows['ActivityID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                      <td><a href='?action=delete&ActivityID=".$rows['ActivityID']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
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

      $('#ClubTypeID').change(function() {
        var clubTypeID = $(this).val();
        if(clubTypeID) {
          $.ajax({
            type: 'POST',
            url: 'ajaxData.php',
            data: 'ClubTypeID=' + clubTypeID,
            success: function(html) {
              $('#ClubID').html(html);
              $('#ActivityID').html('<option value="">--Select Activity--</option>'); 
            }
          }); 
        } else {
          $('#ClubID').html('<option value="">--Select Club--</option>');
          $('#ActivityID').html('<option value="">--Select Activity--</option>'); 
        }
      });

      $('#ClubID').change(function() {
        var clubID = $(this).val();
        if(clubID) {
          $.ajax({
            type: 'POST',
            url: 'ajaxData.php',
            data: 'ClubID=' + clubID,
            success: function(html) {
              $('#ActivityID').html(html);
            }
          }); 
        } else {
          $('#ActivityID').html('<option value="">--Select Activity--</option>'); 
        }
      });
    });
  </script>
</body>

</html>
