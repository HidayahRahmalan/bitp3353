<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Fetch teacher's club ID from the session
$teacherID = $_SESSION['userId'];
$clubQuery = "SELECT ClubID FROM registerteacher WHERE TeacherID = '$teacherID'";
$clubResult = mysqli_query($conn, $clubQuery);
$clubRow = mysqli_fetch_assoc($clubResult);
$teacherClubID = $clubRow['ClubID'];
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
  <title>Dashboard</title>
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
            <h1 class="h3 mb-0 text-gray-800">View Competition Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Competition Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Competition Attendance</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Date<span class="text-danger ml-2">*</span></label>
                        <input type="date" class="form-control" name="dateTaken" id="exampleInputDate" placeholder="Select Date" required>
                      </div>
                    </div>
                    <button type="submit" name="view" class="btn btn-primary">View Competition Attendance</button>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Competition Attendance</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Competition</th>
                            <th>Status</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if(isset($_POST['view'])){
                            $dateTaken = $_POST['dateTaken'];
                            $formattedDate = date('d-m-Y', strtotime($dateTaken));
                            $selectedYear = date('Y', strtotime($dateTaken));

                            // Query to fetch attendance based on the selected date and teacher's club ID
                            $query = "SELECT 
                            s.StudentName, 
                            rs.StudentClass, 
                            a.CompetitionName, 
                            att.AttendanceStatus, 
                            att.DateTimeTaken 
                            FROM attendance att
                            JOIN student s ON att.StudentID = s.StudentID
                            JOIN registerstudent rs ON s.StudentID = rs.StudentID
                            JOIN competition a ON att.CompetitionID = a.CompetitionID
                            WHERE DATE_FORMAT(STR_TO_DATE(att.DateTimeTaken, '%d-%m-%Y'), '%d-%m-%Y') = '$formattedDate'
                            AND a.ClubID = '$teacherClubID'
                            AND rs.CurrentYear = '$selectedYear'
                            GROUP BY s.StudentName, rs.StudentClass, a.CompetitionName, att.AttendanceStatus, att.DateTimeTaken";

                            $rs = $conn->query($query);
                            $num = $rs->num_rows;
                            $sn = 0;
                            if($num > 0) { 
                              while ($rows = $rs->fetch_assoc()) {
                                $AttendanceStatus = $rows['AttendanceStatus'];
                                $colour = ($AttendanceStatus == 'Present') ? "#00FF00" : "#FF0000";
                                $sn++;
                                echo "
                                  <tr>
                                    <td>".$sn."</td>
                                    <td>".$rows['StudentName']."</td>
                                    <td>".$rows['StudentClass']."</td>
                                    <td>".$rows['CompetitionName']."</td>
                                    <td style='background-color:".$colour."'>".$AttendanceStatus."</td>
                                    <td>".$rows['DateTimeTaken']."</td>
                                  </tr>";
                              }
                            } else {
                              echo "<tr><td colspan='6' class='text-center'>No Record Found!</td></tr>";
                            }
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!--Row-->
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
