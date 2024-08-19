<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('header.php');
include('../include/connection.php');

// Retrieve lecturer details
$query = "SELECT * FROM lecturer WHERE lect_id = ".$_SESSION['lect_id']."";
$rs = $conn->query($query);
$num = $rs->num_rows;
$rows = $rs->fetch_assoc();
$role = $rows['role'];

//session_start();

//abademic advisor index
// Function to get total number of students under the lecturer
function getTotalStudents($conn, $lect_id) {
  $query = "SELECT COUNT(*) AS total_students FROM student WHERE lect_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $lect_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row['total_students'];
}

// Function to get total pending aa_status requests
function getTotalPendingAAStatus($conn) {
  $query = "SELECT COUNT(*) AS total_pending_aa FROM transfer t
            JOIN grade g ON t.grade_id = g.grade_id
            JOIN student s ON g.stud_id = s.stud_id
            WHERE t.aa_status = 'Pending'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row['total_pending_aa'];
}

// Function to get total pending aa_status requests
function getTotalPendingTDADean($conn) {
  $query = "SELECT COUNT(DISTINCT s.stud_id) AS total_pending_tdadean
            FROM transfer t
            JOIN grade g ON t.grade_id = g.grade_id
            JOIN student s ON g.stud_id = s.stud_id
            WHERE  t.tda_status = 'Pending' AND t.dean_status = 'Pending'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row['total_pending_tdadean'];
}


// Function to get total approve aa_status requests
function getTotalApprovedTDADeanStatus($conn) {
  $query = "SELECT COUNT(DISTINCT s.stud_id) AS total_app_tdadean 
            FROM transfer t
            JOIN grade g ON t.grade_id = g.grade_id
            JOIN student s ON g.stud_id = s.stud_id
            WHERE t.aa_status='Accepted' AND t.tda_status = 'Accepted' AND t.dean_status = 'Accepted'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row['total_app_tdadean'];
}

//function calculate student in progress
function getTotalInProgressTDADeanStatus($conn) {
  $query = "SELECT COUNT(DISTINCT s.stud_id) AS total_inprogress_tdadean 
            FROM student s
            LEFT JOIN grade g ON s.stud_id = g.stud_id
            LEFT JOIN transfer t ON g.grade_id = t.grade_id
            WHERE g.grade_id IS NULL AND t.transfer_id IS NULL";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row['total_inprogress_tdadean'];
}


// Function to get total pending teaching plan requests
function getTotalPendingTeachingPlan($conn) {
  $query = "SELECT COUNT(*) AS total_pending_tp FROM new_tp WHERE status = 'Pending'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row['total_pending_tp'];
}

// Function to get total pending teaching plan requests
function getTotalPendingNTP($conn) {
  $query = "SELECT COUNT(*) AS total_pending_tp FROM request WHERE status = 'Pending'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  return $row['total_pending_tp'];
}

// Function to get transfer status for each student
function getTransferStatus($conn, $lect_id) {
  $query = "SELECT s.name, s.username, t.transfer_date, t.aa_status, t.tda_status,t.dean_status
            FROM transfer t
            JOIN grade g ON t.grade_id = g.grade_id
            JOIN student s ON g.stud_id = s.stud_id
            WHERE s.lect_id = ?
            GROUP BY s.stud_id";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $lect_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  return $result;
}

// Retrieve lecturer ID from session
$lect_id = $_SESSION['lect_id'];

// Calculate total students under lecturer
$totalStudents = getTotalStudents($conn, $lect_id);

// Calculate total pending aa_status requests
$totalPendingAAStatus = getTotalPendingAAStatus($conn);

// Calculate total number of students with pending transfers in all three statuses
$totalPendingTDADean = getTotalPendingTDADean($conn);

// Calculate total approved TDA/Dean status requests
$totalApprovedTDADeanStatus = getTotalApprovedTDADeanStatus($conn);

// Calculate total in-progress TDA/Dean status requests
$totalInProgressTDADeanStatus = getTotalInProgressTDADeanStatus($conn);


// Calculate total pending teaching plan requests
$totalPendingTeachingPlan = getTotalPendingTeachingPlan($conn);

// Calculate total pending new teaching plan requests
$totalPendingNTP = getTotalPendingNTP($conn);

// Fetch transfer status for each student
$transferStatus = getTransferStatus($conn, $lect_id);

// Query to count transferred and not transferred students
$query = "SELECT 
            COUNT(CASE WHEN t.transfer_id IS NOT NULL THEN 1 END) AS transferred_count,
            COUNT(CASE WHEN t.transfer_id IS NULL THEN 1 END) AS not_transferred_count
          FROM 
            grade g
          LEFT JOIN 
            transfer t ON g.grade_id = t.grade_id";

// Execute query
$result = $conn->query($query);

// Initialize variables for counts
$transferredCount = 0;
$notTransferredCount = 0;

// Process query results
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $transferredCount = $row['transferred_count'];
    $notTransferredCount = $row['not_transferred_count'];
}

// dean and tda index 
// Fetch totals from the database
$totals = [
  'students' => 0,
  'lecturers' => 0,
  'programmes' => 0,
  'courses' => 0,
  'degree' => 0,
  'institutions' => 0,
];

$query_students = "SELECT COUNT(*) AS total FROM student";
$query_lecturers = "SELECT COUNT(*) AS total FROM lecturer";
$query_programmes = "SELECT COUNT(*) AS total FROM programme";
$query_courses = "SELECT COUNT(*) AS total FROM course WHERE type = 'Diploma'";
$query_degree = "SELECT COUNT(*) AS total FROM course WHERE type = 'Bachelor'";
$query_institutions = "SELECT COUNT(*) AS total FROM institution";

if ($result = $conn->query($query_students)) {
  $row = $result->fetch_assoc();
  $totals['students'] = $row['total'];
}

if ($result = $conn->query($query_lecturers)) {
  $row = $result->fetch_assoc();
  $totals['lecturers'] = $row['total']; 
}

if ($result = $conn->query($query_programmes)) {
  $row = $result->fetch_assoc();
  $totals['programmes'] = $row['total'];
}

if ($result = $conn->query($query_courses)) {
  $row = $result->fetch_assoc();
  $totals['courses'] = $row['total'];
}

if ($result = $conn->query($query_degree)) {
  $row = $result->fetch_assoc();
  $totals['degree'] = $row['total'];
}

if ($result = $conn->query($query_institutions)) {
  $row = $result->fetch_assoc();
  $totals['institutions'] = $row['total'];
}

/// Fetch transfer report data
$transfer_data = [];
$query_transfer = "SELECT c.title, COUNT(DISTINCT g.stud_id) AS total_students 
                   FROM transfer t
                   JOIN grade g ON t.grade_id = g.grade_id
                   JOIN course c ON g.course_id = c.course_id
                   WHERE t.aa_status = 'accepted' AND t.tda_status = 'accepted' AND t.dean_status = 'accepted'
                   GROUP BY c.title";
if ($result = $conn->query($query_transfer)) {
  while ($row = $result->fetch_assoc()) {
    $transfer_data[] = $row;
  }
}

// Fetch total DE students for each programme
$de_student_data = [];
$query_de_students = "SELECT p.prog_code, COUNT(s.stud_id) AS total_students 
                      FROM student s
                      JOIN programme p ON s.prog_id = p.prog_id
                      GROUP BY p.prog_name";
if ($result = $conn->query($query_de_students)) {
  while ($row = $result->fetch_assoc()) {
    $de_student_data[] = $row;
  }
}

// Close connection
$conn->close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<main id="main" class="main">

<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- Academic advisor index -->
<?php if ($role == "Academic Advisor"): ?>
<section class="section dashboard">
  <div class="row">

    <!-- Left student columns -->
    <div class="col-lg-12">
      <div class="row">

        <!-- student Card -->
        <div class="col-xxl-3 col-md-6">
          <div class="card info-card sales-card">

            <div class="card-body">
              <h5 class="card-title">Total students </h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-people"></i>
                </div>
                <div class="ps-3">
                  <h6><?php echo $totalStudents; ?></h6>

                </div>
              </div>
            </div>

          </div>
        </div><!-- End student Card -->

        <!-- Pending transfer request Card -->
        <div class="col-xxl-3 col-md-6">
          <div class="card info-card revenue-card">

         

            <div class="card-body">
              <h5 class="card-title">Pending Transfer</h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-folder-symlink"></i>
                </div>
                <div class="ps-3">
                  <h6><?php echo $totalPendingAAStatus; ?></h6>

                </div>
              </div>
            </div>

          </div>
        </div><!-- requet Card -->

        <!-- Pending tp Card -->
        <div class="col-xxl-3 col-xl-12">

          <div class="card info-card customers-card">


            <div class="card-body">
              <h5 class="card-title">Pending Update TP</h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-file-earmark-pdf"></i>
                </div>
                <div class="ps-3">
                  <h6><?php echo $totalPendingTeachingPlan; ?></h6>

                </div>
              </div>

            </div>
          </div>

        </div><!-- End pending tpCard -->

        <!-- Pending ntp Card -->
        <div class="col-xxl-3 col-xl-12">

          <div class="card info-card customers-card">


            <div class="card-body">
              <h5 class="card-title">Pending New TP </h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-file-earmark-pdf"></i>
                </div>
                <div class="ps-3">
                  <h6><?php echo $totalPendingNTP; ?></h6>

                </div>
              </div>

            </div>
          </div>

        </div><!-- End pending ntpCard -->


       <!-- Transfer Status Card -->
       <div class="col-xxl-12 col-md-12">
          <div class="card recent-sales overflow-auto">
            <div class="card-body">
              <h5 class="card-title">Recent Transfer Status </h5>
              <table class="table table-borderless datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Matric No</th>
                    <th scope="col">Transfer Date</th>
                    <th scope="col">AA Status</th>
                    <th scope="col">TDA Status</th>
                    <th scope="col">Dean Status</th>


                  </tr>
                </thead>
                <tbody>
                <?php
                // Function to determine badge class based on status
                function getStatusBadgeClass($status) {
                    switch ($status) {
                        case 'Accepted':
                            return 'badge bg-success';
                        case 'Pending':
                            return 'badge bg-warning';
                        case 'Rejected':
                            return 'badge bg-danger';
                        default:
                            return 'badge bg-secondary'; // Default to gray if status doesn't match expected values
                    }
                }

                $count = 1;
                while ($row = $transferStatus->fetch_assoc()) {
                    echo "<tr>";
                    echo "<th scope='row'><a href='#'>" . $count . "</a></th>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td><a href='#' class='text-primary'>" . htmlspecialchars($row['username']) . "</a></td>";
                    echo "<td>" . htmlspecialchars(date('d/m/Y', strtotime($row['transfer_date']))) . "</td>";

                    // Display status badges with appropriate background colors
                    echo "<td><span class='" . getStatusBadgeClass($row['aa_status']) . "'>" . htmlspecialchars($row['aa_status']) . "</span></td>";
                    echo "<td><span class='" . getStatusBadgeClass($row['tda_status']) . "'>" . htmlspecialchars($row['tda_status']) . "</span></td>";
                    echo "<td><span class='" . getStatusBadgeClass($row['dean_status']) . "'>" . htmlspecialchars($row['dean_status']) . "</span></td>";

                    echo "</tr>";
                    $count++;
                }
                ?>


                </tbody>
              </table>
            </div>
          </div>
        </div><!-- End Transfer Status Card -->

      </div>
    </div><!-- End Left side columns -->

    <!-- Right side columns -->
    <!-- <div class="col-lg-12"> -->

  </div>
</section>
<?php endif; ?>

<!-- end of academic advidor index -->


<!-- Dean and TDA index -->
<?php if ($role == "Academic Deputy Dean" || $role == "Dean"): ?>
<section class="section dashboard">

      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- DE Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

                <div class="card-body">
                  <h5 class="card-title">DE Student </h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-user-heart-line"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $totals['students']; ?></h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End DE Card -->

            <!-- AA Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                <div class="card-body">
                  <h5 class="card-title">Academic Advisor</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $totals['lecturers']; ?></h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End AA Card -->

            <!-- Programme Card -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">
        

                <div class="card-body">
                  <h5 class="card-title">Programme </h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-book-open-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $totals['programmes']; ?></h6>
                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->

             <!-- Sales Card -->
             <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

                <div class="card-body">
                  <h5 class="card-title">Diploma Course </h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-terminal-box-line"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $totals['courses']; ?></h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Finish Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                <div class="card-body">
                  <h5 class="card-title">Degree Course </h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-terminal-box-line"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $totals['degree']; ?></h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Finish Card -->

            <!-- Pending Card -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">
        

                <div class="card-body">
                  <h5 class="card-title">Registered Institution </h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-hotel-line"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $totals['institutions']; ?></h6>
                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End pending Card -->

            <!-- pending Transfer Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

                <div class="card-body">
                  <h5 class="card-title">Pending Transfer </h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-information-line"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $totalPendingTDADean; ?></h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- pending Card -->

          
           
            

            <!-- finish Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                <div class="card-body">
                  <h5 class="card-title">Approved Transfer </h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-check-double-line"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $totalApprovedTDADeanStatus; ?></h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End finish Card -->

            <!-- inprogress Card -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">
        

                <div class="card-body">
                  <h5 class="card-title">Inprogress Transfer </h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bx bx-loader-circle"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $totalInProgressTDADeanStatus; ?></h6>
                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End inprogress Card -->

            <!-- Reports -->
            <div class="col-12">
              <div class="card">

                <div class="card-body">
                  <h5 class="card-title">Reports</h5>
                  <!-- Line Chart -->
                  <div id="reportsChart"></div>
                  <script>
                    document.addEventListener("DOMContentLoaded", () => {
                      const transferData = <?php echo json_encode($transfer_data); ?>;
                      const subjects = transferData.map(item => item.title);
                      const totalStudents = transferData.map(item => item.total_students);

                      new ApexCharts(document.querySelector("#reportsChart"), {
                        series: [{
                          name: 'Total Students',
                          data: totalStudents,
                        }],
                        chart: {
                          height: 350,
                          type: 'bar',
                          toolbar: {
                            show: false
                          },
                        },
                        colors: ['#4154f1'],
                        plotOptions: {
                          bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                          },
                        },
                        dataLabels: {
                          enabled: false
                        },
                        stroke: {
                          show: true,
                          width: 2,
                          colors: ['transparent']
                        },
                        xaxis: {
                          categories: subjects,
                        },
                        yaxis: {
                          title: {
                            text: 'Total Students'
                          }
                        },
                        fill: {
                          opacity: 1
                        },
                        tooltip: {
                          y: {
                            formatter: function (val) {
                              return val;
                            }
                          }
                        }
                      }).render();
                    });
                  </script>
                  <!-- End Line Chart -->
                </div>
              </div>
            </div><!-- End Reports -->
          </div>
        </div><!-- End Left side columns -->

          <!-- DE Student by Programme Chart -->
          <div class="col-12">
            <div class="card">
              <div class="card-body pb-0">
                <h5 class="card-title">DE Students by Programme</h5>
                <div id="deStudentChart" style="min-height: 400px;" class="echart"></div>
                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    const deStudentData = <?php echo json_encode($de_student_data); ?>;
                    const programmes = deStudentData.map(item => item.prog_code);
                    const totalStudents = deStudentData.map(item => item.total_students);

                    let colors = ['#4154f1', '#2eca6a', '#ff771d', '#f1415c', '#f1ac41']; // Define your colors here

                    let option = {
                      tooltip: {
                        trigger: 'item'
                      },
                      legend: {
                        top: '-1%',
                        left: 'center',
                        textStyle: {
                          fontSize: 18 // Adjust legend font size
                        }
                      },
                      series: [{
                        name: 'Total Students',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        avoidLabelOverlap: false,
                        label: {
                          show: false,
                          position: 'center'
                        },
                        emphasis: {
                          label: {
                            show: true,
                            fontSize: '18',
                            fontWeight: 'bold'
                          }
                        },
                        labelLine: {
                          show: false
                        },
                        data: programmes.map((name, index) => ({
                          value: totalStudents[index],
                          name: name,
                          itemStyle: {
                            color: colors[index % colors.length] // Assign colors based on index
                          }
                        }))
                      }]
                    };

                    let chart = echarts.init(document.querySelector("#deStudentChart"));
                    chart.setOption(option);
                  });
                </script>
              </div>
            </div>
          </div><!-- End DE Student by Programme Chart -->

        </div><!-- End Left side columns -->

      </div>
    </section>
    <?php endif; ?>

 <!-- end of dean and tda index -->

</main><!-- End #main -->

<?php
include('footer.php');
?>
</body>
</html>

  