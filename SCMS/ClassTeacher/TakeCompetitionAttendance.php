<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Includes/dbcon.php';
include '../Includes/session.php';

$CompetitionID = isset($_GET['CompetitionID']) ? $_GET['CompetitionID'] : null;

// Get the teacher's ClubID
$query = "SELECT club.ClubName, club.ClubID 
          FROM registerteacher
          INNER JOIN club ON club.ClubID = registerteacher.ClubID
          WHERE registerteacher.TeacherID = '$_SESSION[userId]'";
$rs = $conn->query($query);
$rrw = $rs->fetch_assoc();
$teacherClubID = $rrw['ClubID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $CompetitionID = $_POST['CompetitionID'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $CompetitionID = $_POST['CompetitionID'];
    $totalRecord = $_POST['totalRecord'];
    $currentDateTime = date('d-m-Y H:i:s');

    // Function to calculate grade based on total mark
    function calculateGrade($totalMark) {
        if ($totalMark >= 80) {
            return 'A';
        } elseif ($totalMark >= 65) {
            return 'B';
        } elseif ($totalMark >= 50) {
            return 'C';
        } elseif ($totalMark >= 35) {
            return 'D';
        } else {
            return 'E';
        }
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Fetch the Competition mark
        $qry = "SELECT CompetitionMark, ClubID FROM competition WHERE CompetitionID = ?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $CompetitionID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $CompetitionMark = $row['CompetitionMark'];
        $clubID = $row['ClubID'];

        for ($i = 0; $i < $totalRecord; $i++) {
            $studentID = $_POST["sid$i"];
            $attendanceStatus = isset($_POST["chk$i"]) ? 'Present' : 'Absent';

            // Check if attendance record exists
            $query = "SELECT AttendanceID, AttendanceStatus FROM attendance WHERE CompetitionID = ? AND StudentID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $CompetitionID, $studentID);
            $stmt->execute();
            $result = $stmt->get_result();
            $existingRecord = $result->fetch_assoc();

            if ($existingRecord) {
                $previousStatus = $existingRecord['AttendanceStatus'];

                if ($attendanceStatus === 'Present' && $previousStatus !== 'Present') {
                    // Update existing record with new DateTimeTaken and set AttendanceStatus to Present
                    $updateQuery = "UPDATE attendance
                                    SET DateTimeTaken = ?, AttendanceStatus = ?
                                    WHERE CompetitionID = ? AND StudentID = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param("ssii", $currentDateTime, $attendanceStatus, $CompetitionID, $studentID);
                    $stmt->execute();

                    // Update total mark in registerstudent table
                    $updateQuery = "UPDATE registerstudent
                                    SET TotalMark = TotalMark + ?
                                    WHERE StudentID = ? AND CurrentYear = YEAR(CURDATE()) AND ClubID = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param("iii", $CompetitionMark, $studentID, $clubID);
                    $stmt->execute();


                } elseif ($attendanceStatus === 'Absent' && $previousStatus === 'Present') {
                    // Update existing record to set AttendanceStatus to Absent
                    $updateQuery = "UPDATE attendance
                                    SET  DateTimeTaken = ?, AttendanceStatus = ?
                                    WHERE CompetitionID = ? AND StudentID = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param("ssii", $currentDateTime, $attendanceStatus, $CompetitionID, $studentID);
                    $stmt->execute();

                    // Adjust total marks
                    $updateQuery = "UPDATE registerstudent
                                    SET TotalMark = TotalMark - ?
                                    WHERE StudentID = ? AND CurrentYear = YEAR(CURDATE()) AND ClubID = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param("iii", $CompetitionMark, $studentID, $clubID);
                    $stmt->execute();
                } elseif ($attendanceStatus === 'Absent') {
                    // Update existing record to set AttendanceStatus to Absent
                    $updateQuery = "UPDATE attendance
                                    SET  DateTimeTaken = ?, AttendanceStatus = ?
                                    WHERE CompetitionID = ? AND StudentID = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param("ssii", $currentDateTime, $attendanceStatus, $CompetitionID, $studentID);
                    $stmt->execute();

                   // Adjust total marks
                  $zero = 0;
                  $updateQuery = "UPDATE registerstudent
                                  SET TotalMark = TotalMark + ?
                                  WHERE StudentID = ? AND CurrentYear = YEAR(CURDATE()) AND ClubID = ?";
                  $stmt = $conn->prepare($updateQuery);

                  // Here, we bind the $zero, $studentID, and $clubID to the query
                  $stmt->bind_param("iii", $zero, $studentID, $clubID);
                  $stmt->execute();
                                  }
            } else {
                // Insert new record
                $insertQuery = "INSERT INTO attendance (CompetitionID, StudentID, DateTimeTaken, AttendanceStatus)
                                VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("iiss", $CompetitionID, $studentID, $currentDateTime, $attendanceStatus);
                $stmt->execute();

                // Update total mark in registerstudent table if the student is present
                if ($attendanceStatus === 'Present') {
                    $updateQuery = "UPDATE registerstudent
                                    SET TotalMark = TotalMark + ?
                                    WHERE StudentID = ? AND CurrentYear = YEAR(CURDATE()) AND ClubID = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param("iii", $CompetitionMark, $studentID, $clubID);
                    $stmt->execute();
                }
            }

            // Fetch updated total mark
            $qry = "SELECT TotalMark FROM registerstudent WHERE StudentID = ? AND CurrentYear = YEAR(CURDATE()) AND ClubID = ?";
            $stmt = $conn->prepare($qry);
            $stmt->bind_param("ii", $studentID, $clubID);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $totalMark = $row['TotalMark'];

            // Calculate and update grade based on the updated total mark
            $grade = calculateGrade($totalMark);

            $updateQuery = "UPDATE registerstudent
                            SET Grade = ?
                            WHERE StudentID = ? AND CurrentYear = YEAR(CURDATE()) AND ClubID = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("sii", $grade, $studentID, $clubID);
            $stmt->execute();
        }

        // Commit transaction
        $conn->commit();
        $statusMsg = "Attendance taken successfully!";
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $statusMsg = "Failed to take attendance. Please try again.";
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
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Take Attendance (Today's Date: <?php echo date("d-m-Y"); ?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Students in Competition</li>
            </ol>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <form method="post">
                <div class="form-group row mb-3">
                  <div class="col-xl-6">
                    <label class="form-control-label">Select Competition<span class="text-danger ml-2">*</span></label>
                    <select required name="CompetitionID" class="form-control mb-3">
                      <option value="">--Select Competition--</option>
                      <?php
                      // Fetch competitions for the teacher's club
                      $qry = "SELECT competition.CompetitionID, competition.CompetitionName 
                              FROM competition
                              WHERE competition.ClubID = '$teacherClubID'
                              ORDER BY competition.CompetitionName ASC";
                      $result = mysqli_query($conn, $qry);
                      while ($row = mysqli_fetch_assoc($result)){
                        $cid = $row['CompetitionID'];
                      ?>
                        <option value="<?php echo $cid ?>" <?php if($cid==$CompetitionID) { echo "selected"; } ?> ><?php echo $row['CompetitionName'] ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-xl-6">
                    <button type="submit" name="search" class="btn btn-primary mt-4">Search</button>
                  </div>
                </div>
              </form>

              <?php if (isset($statusMsg)): ?>
                <div class="alert alert-success">
                  <?php echo $statusMsg; ?>
                </div>
              <?php elseif ($CompetitionID): ?>
                <form method="post">
                  <input type="hidden" name="CompetitionID" value="<?php echo $CompetitionID ?>" />
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Students in Competition</h6>
                      <h6 class="m-0 font-weight-bold text-danger">Note: <i>Click on the checkboxes beside each student to take attendance!</i></h6>
                    </div>
                    <div class="table-responsive p-3">
                      <?php echo isset($statusMsg) ? $statusMsg : ''; ?>
                      <table class="table align-items-center table-flush table-hover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Competition Name</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Check</th>
                            <th>DateTime Taken</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $CurrentYear = date('Y');
                          $query = "SELECT DISTINCT
                                    student.StudentName, 
                                    competition.CompetitionName, 
                                    registerstudent.StudentClass,
                                    student.StudentID,
                                    attendance.DateTimeTaken
                                    FROM 
                                    registerstudent
                                    INNER JOIN 
                                    student ON registerstudent.StudentID = student.StudentID
                                    LEFT JOIN 
                                    attendance ON registerstudent.StudentID = attendance.StudentID AND attendance.CompetitionID = ?
                                    INNER JOIN 
                                    competition ON attendance.CompetitionID = competition.CompetitionID
                                    INNER JOIN 
                                    club ON competition.ClubID = club.ClubID
                                    INNER JOIN 
                                    registerteacher ON registerteacher.ClubID = club.ClubID
                                    WHERE 
                                    registerteacher.TeacherID = ? 
                                    AND competition.CompetitionID = ? AND registerstudent.CurrentYear = '$CurrentYear'";
                          
                          // Prepare and execute the query
                          if ($stmt = $conn->prepare($query)) {
                            $stmt->bind_param("iii", $CompetitionID, $_SESSION['userId'], $CompetitionID);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $num = $result->num_rows;
                            $sn = 0;

                            if ($num > 0) {
                              while ($rows = $result->fetch_assoc()) {
                                $dttaken = $rows['DateTimeTaken'];
                                $checked = "";
                                $dtval = "";
                                if (!is_null($dttaken)) {
                                  $checked = "checked";
                                  $dtval = date('d-M-Y H:i', strtotime($dttaken));
                                }

                                echo "
                                <tr>
                                  <td>". ($sn+1) ."</td>
                                  <td>".$rows['CompetitionName']."</td>
                                  <td>".$rows['StudentName']."</td>
                                  <td>".$rows['StudentClass']."</td>
                                  <td><input name='chk" . $sn . "' type='checkbox' value='".$rows['StudentID']."' style='width:20px; height:20px' " . $checked . " ></td>
                                  <td>".$dtval."</td>
                                </tr>";
                                echo "<input name='sid" . $sn . "' value='".$rows['StudentID']."' type='hidden' class='form-control'>";
                                $sn++;
                              }
                              echo "<input type='hidden' name='totalRecord' value='".$sn."' />";
                            } else {
                              echo "<tr><td colspan='6'><div class='alert alert-danger' role='alert'>No Record Found!</div></td></tr>";
                            }

                            $stmt->close();
                          } else {
                            echo "Error preparing the query: " . $conn->error;
                          }
                          ?>
                        </tbody>
                      </table>
                      <br>
                      <button type="submit" name="submit" value="att" class="btn btn-primary">Take Attendance</button>
                    </div>
                  </div>
                </form>
              <?php endif; ?>
            </div>
          </div>
          <!--Row-->
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
