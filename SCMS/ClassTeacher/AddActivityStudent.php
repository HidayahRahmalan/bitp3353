<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = '';

//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {
    $ActivityID = $_POST['ActivityID'];
    $RegisterStudentIDs = $_POST['RegisterStudentID']; // This will be an array

    foreach ($RegisterStudentIDs as $RegisterStudentID) {
        // Check if the combination of StudentID and ActivityID already exists
        $checkQuery = "SELECT * FROM attendance WHERE RegisterStudentID = '$RegisterStudentID' AND ActivityID = '$ActivityID'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) == 0) {
            // Get StudentID first by RegisterStudentID
            $sql = "SELECT * FROM registerstudent WHERE RegisterStudentID = $RegisterStudentID";
            $result = $conn->query($sql);
            $registerstudent = $result->fetch_assoc();

            // Insert the data into the database
            $insertQuery = "INSERT INTO attendance (StudentID, RegisterStudentID, ActivityID) 
                            VALUES ('{$registerstudent['StudentID']}', '$RegisterStudentID', '$ActivityID')";
            if (!mysqli_query($conn, $insertQuery)) {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
            }
        }
    }

    $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
}

//---------------------------------------EDIT-------------------------------------------------------------

if (isset($_GET['AttendanceID']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $AttendanceID = $_GET['AttendanceID'];

  $query = mysqli_query($conn, "SELECT * FROM attendance WHERE AttendanceID ='$AttendanceID'");
  $row = mysqli_fetch_array($query);

  //------------UPDATE-----------------------------

  if(isset($_POST['update'])){
    $ActivityID = $_POST['ActivityID'];
    $RegisterStudentID = $_POST['RegisterStudentID'];

    // Get StudentID first by RegisterStudentID
    $sql = "SELECT * FROM registerstudent WHERE RegisterStudentID={$RegisterStudentID}";
    $result = $conn->query( $sql );
    $registerstudent = $result->fetch_assoc();

    $query = mysqli_query($conn, "UPDATE attendance SET StudentID='{$registerstudent['StudentID']}', RegisterStudentID='$RegisterStudentID', ActivityID='$ActivityID' WHERE AttendanceID='$AttendanceID'");

    if ($query) {
      echo "<script type='text/javascript'>window.location = 'AddActivityStudent.php'</script>"; 
    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['AttendanceID']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $AttendanceID= $_GET['AttendanceID'];

  $query = mysqli_query($conn,"DELETE FROM attendance WHERE AttendanceID='$AttendanceID'");

  if ($query == TRUE) {
    echo "<script type='text/javascript'>window.location = 'AddActivityStudent.php'</script>";  
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
      <h1 class="h3 mb-0 text-gray-800">Add Activity Student</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Activity Student</li>
      </ol>
      </div>

      <div class="row">
      <div class="col-lg-12">
        <!-- Form Basic -->
        <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Add Activity Student</h6>
          <?php echo $statusMsg; ?>
        </div>
         <div class="card-body">
  <form method="post">
    <!-- Add a checkbox to select all students -->
    <div class="form-group row mb-3">
        <div class="col-xl-6">
            <label class="form-control-label">Select Activity<span class="text-danger ml-2">*</span></label>
            <select required name="ActivityID" class="form-control mb-3">
                <option value="">--Select Activity--</option>
                <?php
                // Fetch activities for the teacher's club
                $teacherID = $_SESSION['userId'];
                $query = "SELECT club.ClubID 
                          FROM registerteacher
                          INNER JOIN club ON club.ClubID = registerteacher.ClubID
                          WHERE registerteacher.TeacherID = '$teacherID'";
                $rs = mysqli_query($conn, $query);
                $rrw = mysqli_fetch_assoc($rs);
                $teacherClubID = $rrw['ClubID'];

                // Fetch activities for the teacher's club
                $qry = "SELECT activity.ActivityID, activity.ActivityName 
                        FROM activity
                        WHERE activity.ClubID = '$teacherClubID'
                        ORDER BY activity.ActivityName ASC";
                $result = mysqli_query($conn, $qry);
                while ($row = mysqli_fetch_assoc($result)){
                  echo '<option value="'.$row['ActivityID'].'">'.$row['ActivityName'].'</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-xl-6">
            <label class="form-control-label">Select Student<span class="text-danger ml-2">*</span></label>
            <select required id="studentSelect" name="RegisterStudentID[]" class="form-control mb-3" multiple>
                <?php
                // Fetch students for the teacher's club
                $teacherID = $_SESSION['userId'];
                $currentYear = date('Y');
                $sql = "SELECT rs.RegisterStudentID,
                               s.StudentName, rs.CurrentYear
                        FROM registerstudent rs
                        LEFT JOIN student s ON s.StudentID = rs.StudentID
                        LEFT JOIN registerteacher rt ON rt.ClubID = rs.ClubID
                        WHERE rt.TeacherID = {$teacherID} AND rs.CurrentYear = '{$currentYear}'";
                $result = $conn->query($sql);
                $registerStudents = $result->fetch_all(MYSQLI_ASSOC);
                ?>
                <?php foreach($registerStudents as $row): ?>
                    <option value="<?= $row['RegisterStudentID'] ?>"><?= $row['StudentName'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" id="selectAllStudents" class="btn btn-primary mt-2">Select All</button>
        </div>
    </div>

      <?php if (isset($AttendanceID)) { ?>
        <button type="submit" name="update" class="btn btn-warning">Update</button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button>
      <?php } else { ?>           
        <button type="submit" name="save" class="btn btn-primary">Save</button>
      <?php } ?>         
  </form>
</div>
</div>

        <!-- Input Group -->
         <div class="row">
  <div class="col-lg-12">
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">All Student Registered</h6>
      </div>

      <div class="table-responsive p-3">
          <?php 
          $teacherID = $_SESSION['userId'];
          $sql = "SELECT a.AttendanceID,
                         ac.ActivityName,
                         DATE_FORMAT(STR_TO_DATE(ac.ActivityDate, '%d-%m-%Y'), '%d-%m-%Y') AS formattedDate,
                         s.StudentName
                  FROM activity ac
                  LEFT JOIN attendance a ON a.ActivityID = ac.ActivityID
                  LEFT JOIN registerstudent rs ON rs.RegisterStudentID = a.RegisterStudentID
                  LEFT JOIN student s ON s.StudentID = rs.StudentID
                  LEFT JOIN registerteacher rt ON rt.ClubID = rs.ClubID
                  WHERE rt.TeacherID = $teacherID
                  ORDER BY STR_TO_DATE(ac.ActivityDate, '%d-%m-%Y') DESC";

          $result = $conn->query($sql);
          $attendances = $result->fetch_all(MYSQLI_ASSOC);
          ?>
          <table class="table align-items-center table-flush table-hover" id="dataTableHover">
              <thead class="thead-light">
                  <tr>
                      <th>#</th>
                      <th>Activity Name</th>
                      <th>Activity Date</th>
                      <th>Student Name</th>
                      <th>Delete</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($attendances as $i => $row): ?>
                      <tr>
                          <td><?= $i+1 ?></td>
                          <td><?= $row['ActivityName'] ?></td>
                          <td><?= $row['formattedDate'] ?></td>
                          <td><?= $row['StudentName'] ?></td>
                          <td><a href='?action=delete&AttendanceID=<?= $row['AttendanceID'] ?>' onclick='return confirm("Do you really want to delete?");'><i style='color:red;' class='fas fa-fw fa-trash'></i>Delete</a></td>
                      </tr>
                  <?php endforeach ?>
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

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('selectAllStudents').addEventListener('click', function() {
          var select = document.getElementById('studentSelect');
          for (var i = 0; i < select.options.length; i++) {
              select.options[i].selected = true;
          }
      });
  });
  </script>

</body>

</html>
