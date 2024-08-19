<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Includes/dbcon.php';
include '../Includes/session.php';

// Retrieve the ActivityID from the URL
$CompetitionID = $_GET['CompetitionID'];

// Debugging: Check if ActivityID is received
echo "CompetitionID: ".$CompetitionID."<br>";

// Fetch the club name and students under the given activity ID
$query = "SELECT 
    student.StudentName, 
    competition.CompetitionName, 
    club.ClubName,
    registerstudent.StudentClass
FROM 
    attendance
INNER JOIN 
    competition ON attendance.CompetitionID = competition.CompetitionID
INNER JOIN 
    registerstudent ON attendance.StudentID = registerstudent.StudentID
INNER JOIN 
    student ON registerstudent.StudentID = student.StudentID
INNER JOIN 
    club ON competition.ClubID = club.ClubID
WHERE 
    competition.CompetitionID = ?
            AND registerstudent.CurrentYear = (
        SELECT MAX(rs.CurrentYear)
        FROM registerstudent rs
        WHERE rs.StudentID = student.StudentID
    )
ORDER BY registerstudent.CurrentYear DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $CompetitionID);
$stmt->execute();
$result = $stmt->get_result();

// Debugging: Check number of rows returned
echo "Number of rows: ".$result->num_rows."<br>";
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
            <h1 class="h3 mb-0 text-gray-800">All Students in <?php echo htmlspecialchars($CompetitionName ?? ''); ?> Competition</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">All Students in Competition</li>
            </ol>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <?php 
              if ($result->num_rows > 0) {
              ?>
              <div class="card mb-4">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Students Under Competition</h6>
                  </div>
                  <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                          <thead class="thead-light">
                              <tr>
                                  <th>#</th>
                                  <th>Student Name</th>
                                  <th>Class</th>
                                  <th>Competition</th>
                                  <th>Club</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php
                              $sn = 0;
                              while ($row = $result->fetch_assoc()) {
                                  $sn++;
                                  echo "
                                  <tr>
                                      <td>".$sn."</td>
                                      <td>".$row['StudentName']."</td>
                                      <td>".$row['StudentClass']."</td>
                                      <td>".$row['CompetitionName']."</td>
                                      <td>".$row['ClubName']."</td>
                                  </tr>";
                              }
                              ?>
                          </tbody>
                      </table>
                  </div>
              </div>
              <?php
              } else {
                  echo "<p>No students found under this competition.</p>";
              }
              ?>
          </div>
        </div>
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