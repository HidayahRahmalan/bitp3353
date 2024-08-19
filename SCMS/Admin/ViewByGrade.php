<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$Grade = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $Grade = (isset($_POST['Grade']) ? $_POST['Grade'] : null);  
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
            <h1 class="h3 mb-0 text-gray-800">Student List By Grade</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Student List By Grade</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Students</h6>
                </div>
               <div class="card-body">
         
        <form action="" method="post">
          <div class="form-group row mb-3">

            <div class="col-xl-6">
              <label class="form-control-label">Select Grade<span class="text-danger ml-2">*</span></label>
              <select name="Grade" class="form-control mb-3">
                <option value="">--Select Grade--</option>
                <option value="A" <?php if($Grade == 'A') { echo "selected"; } ?>>A</option>
                <option value="B" <?php if($Grade == 'B') { echo "selected"; } ?>>B</option>
                <option value="C" <?php if($Grade == 'C') { echo "selected"; } ?>>C</option>
                <option value="D" <?php if($Grade == 'D') { echo "selected"; } ?>>D</option>
                <option value="E" <?php if($Grade == 'E') { echo "selected"; } ?>>E</option>
              </select>
            </div>
<button type="submit" name="search" class="btn btn-primary mt-4">Search</button>
            <div class="col-xl-6">
              
               <?php
session_start();
include 'db_connection.php'; // Make sure to include your database connection

// Fetch the teacher's club ID from the session
$userId = (isset($_SESSION['userId']) ? $_SESSION['userId'] : null);

// Fetch the count of students with each grade from the same club as the teacher
$query = "SELECT rs.Grade, COUNT(rs.StudentID) as GradeCount
          FROM registerstudent rs
          GROUP BY rs.Grade";

$result = mysqli_query($conn, $query);

// Step 2: Format data for Chart.js
$labels = ['A', 'B', 'C', 'D', 'E'];
$data = array_fill(0, count($labels), 0); // Initialize counts for each grade to 0

while ($row = mysqli_fetch_assoc($result)) {
    $gradeIndex = array_search($row['Grade'], $labels);
    if ($gradeIndex !== false) {
        $data[$gradeIndex] = $row['GradeCount'];
    }
}

// Step 3: Display the Pie Chart
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pie Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 1500px; height: 500px;"> <!-- Adjust the width of the container -->
        <canvas id="myPieChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('myPieChart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ]
                }]
            },
            options: {
                responsive: false
            }
        });
    </script>
</body>
</html>

            </div>
          </div>
        </form>
      </div>
    </div>

      <!-- Input Group -->
      <div class="row">
        <?php if($Grade != null) { ?>
        <div class="col-lg-12">
          <div class="card mb-4">
            <div class="table-responsive p-3">
              <table class="table align-items-center table-flush table-hover" id="dataTableHover"> <!-- search  -->
                <thead class="thead-light">
                  <tr>
                    <th>#</th>                
                    <th>Student Name</th>
                    <th>Year</th>
                    <th>Club Type</th>
                    <th>Club Name</th>
                    <th>Class</th>
                    <th>Total Mark</th>
                    <th>Grade</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = "SELECT rs.*, s.StudentName, c.ClubName, ct.ClubTypeName 
                            FROM registerstudent rs 
                            INNER JOIN student s ON rs.StudentID = s.StudentID
                            INNER JOIN club c ON rs.ClubId = c.ClubId 
                            INNER JOIN clubtype ct ON c.ClubTypeID = ct.ClubTypeID 
                            WHERE rs.Grade = '$Grade'
                            ORDER BY CurrentYear DESC, ClubTypeName ASC";
                  
                  $rs = $conn->query($query);
                  $sn = 0;
                  if ($rs && $rs->num_rows > 0) {
                    while ($rows = $rs->fetch_assoc()) {
                      $sn++;
                      echo "<tr>
                          <td>".$sn."</td>
                          <td>".$rows['StudentName']."</td>
                          <td>".$rows['CurrentYear']."</td>
                          <td>".$rows['ClubTypeName']."</td>
                          <td>".$rows['ClubName']."</td>
                          <td>".$rows['StudentClass']."</td>
                          <td>".$rows['TotalMark']."</td>
                          <td>".$rows['Grade']."</td>
                          </tr>";
                    }
                  } else {
                    echo "<tr>
                        <td colspan='8'>No Record Found!</td>
                        </tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <?php } ?>
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
