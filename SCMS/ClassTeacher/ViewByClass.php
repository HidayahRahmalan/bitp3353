<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Check if form is submitted
if (isset($_POST['view'])) {
    $selectedClass = $_POST['StudentClass'];
}

// Fetch the teacher's ClubID
$teacherID = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
$teacherClubID = null;

if ($teacherID) {
    $query = "SELECT club.ClubID 
              FROM registerteacher
              INNER JOIN club ON club.ClubID = registerteacher.ClubID
              WHERE registerteacher.TeacherID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $teacherID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $teacherClubID = $row['ClubID'];
    $stmt->close();
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
            <h1 class="h3 mb-0 text-gray-800">View Students by Class</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Students by Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Select Class</h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Student Class<span class="text-danger ml-2">*</span></label>
                        <select name="StudentClass" class="form-control mb-3">
                          <option value="">--Select Class--</option>
                          <option value="1 Khadijah" <?php if($selectedClass == '1 Khadijah') { echo "selected"; } ?>>1 Khadijah</option>
                          <option value="1 Saodah" <?php if($selectedClass == '1 Saodah') { echo "selected"; } ?>>1 Saodah</option>
                          <option value="1 Aisyah" <?php if($selectedClass == '1 Aisyah') { echo "selected"; } ?>>1 Aisyah</option>
                          <option value="2 Khadijah" <?php if($selectedClass == '2 Khadijah') { echo "selected"; } ?>>2 Khadijah</option>
                          <option value="2 Saodah" <?php if($selectedClass == '2 Saodah') { echo "selected"; } ?>>2 Saodah</option>
                          <option value="2 Aisyah" <?php if($selectedClass == '2 Aisyah') { echo "selected"; } ?>>2 Aisyah</option>
                          <option value="3 Khadijah" <?php if($selectedClass == '3 Khadijah') { echo "selected"; } ?>>3 Khadijah</option>
                          <option value="3 Saodah" <?php if($selectedClass == '3 Saodah') { echo "selected"; } ?>>3 Saodah</option>
                          <option value="3 Aisyah" <?php if($selectedClass == '3 Aisyah') { echo "selected"; } ?>>3 Aisyah</option>
                          <option value="4 Ibnu Sina" <?php if($selectedClass == '4 Ibnu Sina') { echo "selected"; } ?>>4 Ibnu Sina</option>
                          <option value="4 Al-Khawarizmi" <?php if($selectedClass == '4 Al-Khawarizmi') { echo "selected"; } ?>>4 Al-Khawarizmi</option>
                          <option value="4 As-Syafie" <?php if($selectedClass == '4 As-Syafie') { echo "selected"; } ?>>4 As-Syafie</option>
                          <option value="5 Ibnu Sina" <?php if($selectedClass == '5 Ibnu Sina') { echo "selected"; } ?>>5 Ibnu Sina</option>
                          <option value="5 Al-Khawarizmi" <?php if($selectedClass == '5 Al-Khawarizmi') { echo "selected"; } ?>>5 Al-Khawarizmi</option>
                          <option value="5 As-Syafie" <?php if($selectedClass == '5 As-Syafie') { echo "selected"; } ?>>5 As-Syafie</option>
                        </select>
                      </div>
                    </div>
                    <button type="submit" name="view" class="btn btn-primary">View</button>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Students in Selected Class</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Club Name</th>
                            <th>Club Type</th>
                            <th>Current Year</th>
                            <th>Class</th>
                            <th>Form Level</th>
                            <th>Club Position</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (isset($selectedClass) && !empty($selectedClass) && $teacherClubID) {
                              $query = "SELECT registerstudent.RegisterStudentID, club.ClubName, student.StudentName, registerstudent.CurrentYear, registerstudent.StudentClass, registerstudent.StudentFormLevel, registerstudent.ClubPosition, clubtype.ClubTypeName
                                        FROM registerstudent
                                        INNER JOIN club ON club.ClubID = registerstudent.ClubID
                                        INNER JOIN student ON student.StudentID = registerstudent.StudentID
                                        INNER JOIN clubtype ON clubtype.ClubTypeID = club.ClubTypeID
                                        WHERE registerstudent.StudentClass = ? AND registerstudent.ClubID = ?
                                        ORDER BY CurrentYear DESC, StudentName ASC";
                              
                              // Prepare statement
                              $stmt = $conn->prepare($query);
                              $stmt->bind_param("si", $selectedClass, $teacherClubID);
                              $stmt->execute();
                              $rs = $stmt->get_result();
                              
                              $num = $rs->num_rows;
                              $sn = 0;
                              if ($num > 0) {
                                  while ($rows = $rs->fetch_assoc()) {
                                      $sn++;
                                      echo "<tr>
                                              <td>".$sn."</td>
                                              <td>".$rows['StudentName']."</td>
                                              <td>".$rows['ClubName']."</td>
                                              <td>".$rows['ClubTypeName']."</td>
                                              <td>".$rows['CurrentYear']."</td>
                                              <td>".$rows['StudentClass']."</td>
                                              <td>".$rows['StudentFormLevel']."</td>
                                              <td>".$rows['ClubPosition']."</td>
                                            </tr>";
                                  }
                              } else {
                                  echo "<tr>
                                          <td colspan='10'>No Record Found!</td>
                                        </tr>";
                              }
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
