<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/logosaikhad.jpg" rel="icon">
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        <!-- Topbar -->
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Administrator Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- Students Card -->
            <?php 
            $query1 = mysqli_query($conn, "SELECT * from student");                       
            $students = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $students;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Club Type Card -->
            <?php 
            $query2 = mysqli_query($conn, "SELECT * from clubtype");                       
            $class = mysqli_num_rows($query2);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Club Type</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $class;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chalkboard fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Club Card -->
            <?php 
            $query3 = mysqli_query($conn, "SELECT * from club");                       
            $classArms = mysqli_num_rows($query3);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Club</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $classArms;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-code-branch fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Club Teachers Card -->
            <?php 
            $query5 = mysqli_query($conn, "SELECT * from teacher");                       
            $classTeacher = mysqli_num_rows($query5);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Club Teachers</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $classTeacher;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chalkboard-teacher fa-2x text-danger"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Activity Card -->
            <?php 
            $query6 = mysqli_query($conn, "SELECT * from activity");                       
            $sessTerm = mysqli_num_rows($query6);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Activity</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $sessTerm;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Competition Card -->
            <?php 
            $query7 = mysqli_query($conn, "SELECT * from competition");                       
            $termonly = mysqli_num_rows($query7);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Competition</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $termonly;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-th fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div> 
          </div>
        </div>

<!-- Bar and Pie Charts -->
<div class="row">
  <div class="col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <!-- <h6 class="m-0 font-weight-bold text-primary">Highest Student Marks (per year) and Total Club's Activity</h6> -->
      </div>
      <div class="card-body">
        <div class="row" style="display: flex; width: 100%;">
          <!-- Bar Chart -->
          <div class="col-md-8" style="padding: 10px; border-right: 1px solid #ddd;">
            <h6 class="m-0 font-weight-bold text-primary">Average Student Marks (per year)</h6>
            <canvas id="myBarChart"></canvas>
          </div>

          <!-- Pie Chart -->
          <div class="col-md-4" style="padding: 20px;">
            <h6 class="m-0 font-weight-bold text-primary">Total Club's Activity</h6>
            <br></br>
            <form action="" method="post">
              <label for="year">Select Year:</label>
              <select name="year" id="year" onchange="this.form.submit()">
                <option value="">--Select Year--</option>
                <?php 
                $years = [2020, 2021, 2022, 2023, 2024];
                foreach ($years as $year) : ?>
                  <option value="<?php echo $year; ?>" <?php if ($years == $year) echo 'selected'; ?>><?php echo $year; ?></option>
                <?php endforeach; ?>
              </select>
            </form>
            <canvas id="myPieChart" width="400" height="400"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
// Define default year
$selectedYear = isset($_POST['year']) ? intval($_POST['year']) : date('Y'); // Set default to current year

// Fetch data for Bar Chart
$years = [2020, 2021, 2022, 2023, 2024];
$marks = [];
foreach ($years as $year) {
  $query = "SELECT AVG(TotalMark) AS avg_mark FROM registerstudent WHERE CurrentYear = $year";
  $rs = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($rs);
  $marks[] = $row['avg_mark'] ?? 0;
}

// Fetch data for Pie Chart
$query = "SELECT club.ClubName, COUNT(activity.ActivityID) AS TotalActivities
  FROM club
  LEFT JOIN activity ON club.ClubID = activity.ClubID
  GROUP BY club.ClubID;";
$result = mysqli_query($conn, $query);

// Step 2: Format data for Chart.js
$labels = [];
$data = [];
$colors = []; // Array to store colors

while ($row = mysqli_fetch_assoc($result)) {
    // Format the label with the club name and total activities
    $labels[] = $row['ClubName'];
    $data[] = $row['TotalActivities'];

    // Generate a unique color for each activity
    $colors[] = sprintf(
        'rgba(%d, %d, %d, 0.5)', 
        rand(0, 255), // Red
        rand(0, 255), // Green
        rand(0, 255)  // Blue
    );
}
?>

<script>
// Bar Chart
var ctxBar = document.getElementById('myBarChart').getContext('2d');
var myBarChart = new Chart(ctxBar, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($years); ?>,
    datasets: [{
      label: 'Average Marks',
      data: <?php echo json_encode($marks); ?>,
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      borderColor: 'rgba(75, 192, 192, 1)',
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

// Pie Chart
var ctx = document.getElementById('myPieChart').getContext('2d');
var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            data: <?php echo json_encode($data); ?>,
            backgroundColor: <?php echo json_encode($colors); ?>, // Use the dynamically generated colors
        }]
    },
    options: {
        responsive: false
    }
});
</script>


        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include "Includes/footer.php"; ?>
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