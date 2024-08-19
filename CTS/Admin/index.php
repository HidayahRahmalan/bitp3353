<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('header.php');
include('../include/connection.php');

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

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
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

            <!-- Revenue Card -->
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
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
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

            </div><!-- End Customers Card -->

            <!-- Reports -->
            <div class="col-12">
              <div class="card">

                <div class="card-body">
                  <h5 class="card-title">Total of courses transfer chosen by student</h5>
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

  </main><!-- End #main -->

  <?php
  include('footer.php');
  ?>
</body>

</html>