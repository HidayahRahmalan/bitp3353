
<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

$userId =(isset($_SESSION['userId']) ? $_SESSION['userId'] : null); 


// Fetch distinct years
$yearQuery = "SELECT DISTINCT CurrentYear FROM registerstudent ORDER BY CurrentYear";
$yearResult = mysqli_query($conn, $yearQuery);
$years = [];
while ($yearRow = mysqli_fetch_assoc($yearResult)) {
    $years[] = $yearRow['CurrentYear'];
}

$selectedYear = $_POST['year'] ?? null;


  $query = "SELECT teacher.TeacherName, registerteacher.registerTeacherID, registerteacher.ClubID
          FROM teacher
          INNER JOIN registerteacher ON registerteacher.TeacherID = teacher.TeacherID
          INNER JOIN club ON club.ClubID = registerteacher.ClubID
          WHERE teacher.TeacherID = '{$_SESSION['userId']}'";
    $rs = $conn->query($query);
    $num = $rs->num_rows;
    $rrw = $rs->fetch_assoc();

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
            <h1 class="h3 mb-0 text-gray-800">Teacher Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
          <!-- New User Card Example -->

          <?php
          $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
          $query = "SELECT teacher.TeacherName, registerteacher.registerTeacherID, registerteacher.ClubID
                    FROM teacher
                    INNER JOIN registerteacher ON registerteacher.TeacherID = teacher.TeacherID
                    INNER JOIN club ON club.ClubID = registerteacher.ClubID
                    WHERE teacher.TeacherID = ?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("i", $userId);
          $stmt->execute();
          $rs = $stmt->get_result();
          $rrw = $rs->fetch_assoc();
          $teacherClubID = $rrw['ClubID']; // Assuming a single club for simplicity; adjust if needed
          $stmt->close();

          if (isset($teacherClubID)) {
            $query = "SELECT COUNT(*) as totalStudents FROM registerstudent WHERE ClubID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $teacherClubID);
            $stmt->execute();
            $stmt->bind_result($totalStudents);
            $stmt->fetch();
            $stmt->close();
            }
            ?>
                      <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card h-100">
                          <div class="card-body">
                            <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Your Students</div>
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totalStudents;?></div>
                                <div class="mt-2 mb-0 text-muted text-xs">
                                  <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 20.4%</span>
                                  <span>Since last month</span> -->
                                </div>
                              </div>
                              <div class="col-auto">
                                <i class="fas fa-users fa-2x text-info"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>


<?php
$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
$query = "SELECT teacher.TeacherName, registerteacher.registerTeacherID, registerteacher.ClubID
          FROM teacher
          INNER JOIN registerteacher ON registerteacher.TeacherID = teacher.TeacherID
          INNER JOIN club ON club.ClubID = registerteacher.ClubID
          WHERE teacher.TeacherID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$rs = $stmt->get_result();
$rrw = $rs->fetch_assoc();
$teacherClubID = $rrw['ClubID']; // Assuming a single club for simplicity; adjust if needed
$stmt->close();

$totalActivities = 0;
$totalCompetitions = 0;

if (isset($teacherClubID)) {
    // Count total activities
    $queryActivities = "SELECT COUNT(*) AS totalActivities FROM activity WHERE ClubID = ?";
    $stmtActivities = $conn->prepare($queryActivities);
    $stmtActivities->bind_param("i", $teacherClubID);
    $stmtActivities->execute();
    $stmtActivities->bind_result($totalActivities);
    $stmtActivities->fetch();
    $stmtActivities->close();

    // Count total competitions
    $queryCompetitions = "SELECT COUNT(*) AS totalCompetitions FROM competition WHERE ClubID = ?";
    $stmtCompetitions = $conn->prepare($queryCompetitions);
    $stmtCompetitions->bind_param("i", $teacherClubID);
    $stmtCompetitions->execute();
    $stmtCompetitions->bind_result($totalCompetitions);
    $stmtCompetitions->fetch();
    $stmtCompetitions->close();
}
?>
                <!-- Activity Card -->

                <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card h-100">
                    <div class="card-body">
                      <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-uppercase mb-1">Activity</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalActivities;?></div>
                        </div>
                        <div class="col-auto">
                          <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Competition Card -->

                <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card h-100">
                    <div class="card-body">
                      <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-uppercase mb-1">Competition</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalCompetitions;?></div>
                        </div>
                        <div class="col-auto">
                          <i class="fas fa-th fa-2x text-info"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> 
          
       </div>
       
<!-- Charts Row -->
<div class="row">
  <!-- Bar Chart -->
  <div class="col-lg-6">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Average Student Marks (per year)</h6>
      </div>
      <div class="card-body">
        <div class="chart-bar">
          <canvas id="myBarChart"></canvas>
        </div>
        <hr>
      </div>
    </div>
  </div>

  <!-- Pie Chart -->
  <div class="col-lg-6">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Top 3 Students</h6>
      </div>

      <form action="" method="post">
        <label for="year">Select Year:</label>
        <select name="year" id="year" onchange="this.form.submit()">
          <option value="">--Select Year--</option>
          <?php foreach ($years as $year) : ?>
            <option value="<?php echo $year; ?>" <?php if ($selectedYear == $year) echo 'selected'; ?>><?php echo $year; ?></option>
          <?php endforeach; ?>
        </select>
      </form>

      <div class="card-body">
        <div class="chart-pie">
          <canvas id="myPieChart"></canvas>
        </div>
        <hr>
      </div>
    </div>
  </div>
</div>

<?php
$year2020=0;
$query = "SELECT AVG(TotalMark) AS n FROM registerstudent rs, registerteacher rt WHERE rs.ClubID=rt.ClubID AND rt.TeacherID=$userId AND CurrentYear=2020";
$rs = mysqli_query($conn, $query);
while ($rows = $rs->fetch_assoc()) {
  $year2020 = $rows['n'];
}

$year2021=0;
$query = "SELECT AVG(TotalMark) AS n FROM registerstudent rs, registerteacher rt WHERE rs.ClubID=rt.ClubID AND rt.TeacherID=$userId AND CurrentYear=2021";    
$rs = mysqli_query($conn, $query);
while ($rows = $rs->fetch_assoc()) {
  $year2021 = $rows['n'];
}

$year2022=0;
$query = "SELECT AVG(TotalMark) AS n FROM registerstudent rs, registerteacher rt WHERE rs.ClubID=rt.ClubID AND rt.TeacherID=$userId AND CurrentYear=2022";    
$rs = mysqli_query($conn, $query);
while ($rows = $rs->fetch_assoc()) {
  $year2022 = $rows['n'];
}

$year2023=0;
$query = "SELECT AVG(TotalMark) AS n FROM registerstudent rs, registerteacher rt WHERE rs.ClubID=rt.ClubID AND rt.TeacherID=$userId AND CurrentYear=2023";    
$rs = mysqli_query($conn, $query);
while ($rows = $rs->fetch_assoc()) {
  $year2023 = $rows['n'];
}

$year2024=0;
$query = "SELECT AVG(TotalMark) AS n FROM registerstudent rs, registerteacher rt WHERE rs.ClubID=rt.ClubID AND rt.TeacherID=$userId AND CurrentYear=2024";    
$rs = mysqli_query($conn, $query);
while ($rows = $rs->fetch_assoc()) {
  $year2024 = $rows['n'];
}
?>

<script src="../vendor/chart.js/Chart.min.js"></script>
<script src="js/demo/chart-bar-demo.js"></script>  

<script>
// Bar Chart Example

var ctx = document.getElementById("myBarChart");
var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ["2020", "2021", "2022", "2023", "2024"],
    datasets: [{
      label: "Average Mark",
      backgroundColor: "#4e73df",
      hoverBackgroundColor: "#2e59d9",
      borderColor: "#4e73df",
      data: [<?php echo $year2020 ?>, <?php echo $year2021 ?>, <?php echo $year2022 ?>, <?php echo $year2023 ?>, <?php echo $year2024 ?>],
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'year'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 5
        },
        maxBarThickness: 25,
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 100,
          maxTicksLimit: 5,
          padding: 10,
          callback: function(value, index, values) {
            return number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ' ' + number_format(tooltipItem.yLabel);
        }
      }
    },
  }
});
</script>






 <?php
// Fetch the teacher's club ID from the session
$userId = (isset($_SESSION['userId']) ? $_SESSION['userId'] : null);

// Fetch distinct years
$yearQuery = "SELECT DISTINCT CurrentYear FROM registerstudent ORDER BY CurrentYear";
$yearResult = mysqli_query($conn, $yearQuery);
$years = [];
while ($yearRow = mysqli_fetch_assoc($yearResult)) {
    $years[] = $yearRow['CurrentYear'];
}

$selectedYear = $_POST['year'] ?? null;

// Fetch the student with the highest marks from the same club as the teacher for the selected year
$query = "SELECT s.StudentName, rs.TotalMark, c.ClubName
          FROM registerstudent rs
          INNER JOIN student s ON rs.StudentID = s.StudentID
          INNER JOIN club c ON rs.ClubID = c.ClubID
          INNER JOIN registerteacher rt ON rs.ClubID = rt.ClubID
          WHERE rt.TeacherID = ? AND rs.CurrentYear = ?
          ORDER BY rs.TotalMark DESC
          LIMIT 3"; // LIMIT 3 to get the top 3 students

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $userId, $selectedYear);
$stmt->execute();
$result = $stmt->get_result();

// Format data for Chart.js
$labels = [];
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Format the label with the student's name
    $labels[] = $row['StudentName'];
    $data[] = $row['TotalMark'];
}
$stmt->close();
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
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: true
                },
                cutoutPercentage: 0,
            },
        });
    </script>
</body>
</html>



       
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include 'includes/footer.php';?>
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
  
  
</body>

</html>