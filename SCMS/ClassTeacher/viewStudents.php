
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$query = "SELECT club.ClubID, registerteacher.RegisterTeacherID, registerteacher.ClubID, club.ClubName, teacher.TeacherName, teacher.TeacherID
    FROM registerteacher
    INNER JOIN club ON club.ClubID = registerteacher.ClubID
    INNER JOIN teacher ON teacher.TeacherID = registerteacher.TeacherID
    WHERE registerteacher.RegisterTeacherID = '{$_SESSION['userId']}'";

    $rs = $conn->query($query);
    $num = $rs->num_rows;
    $rrw = $rs->fetch_assoc();



// Retrieve the teacher's club name based on their ID
$teacher_id = $_SESSION['TeacherID'];
$query_teacher_club = "SELECT club.ClubName FROM registerteacher 
                        INNER JOIN club ON club.ClubID = registerteacher.ClubID 
                        WHERE registerteacher.TeacherID = ?";
$stmt_teacher_club = $conn->prepare($query_teacher_club);
$stmt_teacher_club->bind_param("i", $teacher_id);
$stmt_teacher_club->execute();
$result_teacher_club = $stmt_teacher_club->get_result();
$row_teacher_club = $result_teacher_club->fetch_assoc();
$teacher_club_name = $row_teacher_club['ClubName'];
$stmt_teacher_club->close();

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



   <script>
    function classArmDropdown(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
        xmlhttp.send();
    }
}
</script>
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
    <h1 class="h3 mb-0 text-gray-800">All Students in <?php echo $teacher_club_name; ?> Club</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">All Students in Club</li>
    </ol>
</div>
          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->


              <!-- Input Group -->


            <?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Assuming the teacher is logged in and you have teacher ID stored in $_SESSION['userId']
$teacherID = $_SESSION['userId'];

// Retrieve the club ID of the teacher
$query = "SELECT ClubID FROM registerteacher WHERE TeacherID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacherID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $clubID = $row['ClubID'];

    // Retrieve all students under the teacher's club
    $query = "SELECT student.StudentName, registerstudent.StudentClass, registerstudent.ClubPosition, club.ClubName, registerstudent.CurrentYear
              FROM registerstudent
              INNER JOIN club ON club.ClubID = registerstudent.ClubID
              INNER JOIN student ON student.StudentID = registerstudent.StudentID
              WHERE registerstudent.ClubID = ?
              ORDER BY CurrentYear DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $clubID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">All Students Under Your Club</h6>
                    </div>
                    <div class="table-responsive p-3">
                        <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Club</th>
                                    <th>Position</th>
                                    <th>Year</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sn = 0;
                                while ($rows = $result->fetch_assoc()) {
                                    $sn++;
                                    echo "
                                    <tr>
                                        <td>".$sn."</td>
                                        <td>".$rows['StudentName']."</td>
                                        <td>".$rows['StudentClass']."</td>
                                        <td>".$rows['ClubName']."</td>
                                        <td>".$rows['ClubPosition']."</td>
                                        <td>".$rows['CurrentYear']."</td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "<p>No students found under your club.</p>";
    }
} else {
    echo "<p>You are not registered with any club.</p>";
}



// Retrieve the teacher's club name based on their ID
$teacher_id = $_SESSION['TeacherID'];
$query_teacher_club = "SELECT club.ClubName FROM registerteacher 
                        INNER JOIN club ON club.ClubID = registerteacher.ClubID 
                        WHERE registerteacher.TeacherID = ?";
$stmt_teacher_club = $conn->prepare($query_teacher_club);
$stmt_teacher_club->bind_param("i", $teacher_id);
$stmt_teacher_club->execute();
$result_teacher_club = $stmt_teacher_club->get_result();
$row_teacher_club = $result_teacher_club->fetch_assoc();
$teacher_club_name = $row_teacher_club['ClubName'];
$stmt_teacher_club->close();

?>


          </div>
          <!--Row-->

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

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