<?php
require_once 'vendor/autoload.php'; // Include Composer autoload

use Dompdf\Dompdf;
use Dompdf\Options;

include('header.php');
include('../include/connection.php');
//session_start();

// Retrieve student data
$query = "SELECT s.stud_id, s.name, s.icno, s.faculty, p.prog_name, p.prog_code, s.session, s.email, s.phone, s.username
          FROM student s
          JOIN programme p ON s.prog_id = p.prog_id
          WHERE stud_id = " . $_SESSION['stud_id'];
$rs = $conn->query($query);

if ($rs && $rs->num_rows > 0) {
    $rows = $rs->fetch_assoc();
    $stud_id = $rows['stud_id'];
    $name = $rows['name'];
    $icno = $rows['icno'];
    $faculty = $rows['faculty'];
    $prog_name = $rows['prog_name'];
    $prog_code = $rows['prog_code'];
    $session = $rows['session'];
    $email = $rows['email'];
    $phone = $rows['phone'];
    $matric_number = $rows['username'];
} else {
    // Handle the case where no rows are returned
    echo "<p>No student record found.</p>";
    exit;
}

// Retrieve transfer statuses
$stud_id = $_SESSION['stud_id'];
$status_query = "SELECT t.aa_status, t.tda_status, t.dean_status
                FROM transfer t
                JOIN grade g ON t.grade_id = g.grade_id
                JOIN course c ON g.course_id = c.course_id
                WHERE stud_id = '$stud_id'";
$status_result = $conn->query($status_query);

$aa_status = '';
$tda_status = '';
$dean_status = '';
$message = '';

if ($status_result && $status_result->num_rows > 0) {
  $statuses = $status_result->fetch_assoc();
  $aa_status = $statuses['aa_status'];
  $tda_status = $statuses['tda_status'];
  $dean_status = $statuses['dean_status'];

  if ($aa_status == 'Accepted' && $tda_status == 'Accepted' && $dean_status == 'Accepted') {
      $message = '<i class="bi bi-check-circle me-1"></i> <b>Successful</b> transfer credit';
      $alert_type = "success";
  } elseif ($aa_status == 'Rejected' || $tda_status == 'Rejected' || $dean_status == 'Rejected') {
      $message = '<i class="bi bi-exclamation-octagon me-1"></i> Transfer failed, status is <b>Rejected</b>, please remove sourse <a href="reject.php" style="color: white; font-weight: bold ; text-decoration: underline;">here</a> and try again';
      $alert_type = "danger";
  } else {
      $pending_statuses = [];
      if ($aa_status == 'Pending') $pending_statuses[] = "academic advisor";
      if ($tda_status == 'Pending') $pending_statuses[] = "TDA";
      if ($dean_status == 'Pending') $pending_statuses[] = "dean";
      $pending_list = implode(", ", $pending_statuses);
      $message = '<i class="bi bi-info-circle me-1"></i> Transfer credit is <b>Pending</b>, waiting for '.$pending_list.' approval';
      $alert_type = "primary"; // Change to "info" for pending status
  }
} else {
  $message = '<i class="bi bi-exclamation-triangle me-1"></i> No transfer status found for the student.';
  $alert_type = "secondary"; // Change to "info" for no status found
}

// Function to generate PDF
function generatePDF($html, $filename) {
    // Load Dompdf library
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    $dompdf->stream($filename);
}

// Check if export button is clicked
if (isset($_POST['export_pdf'])) {
    // Prepare HTML content for "Part A" and "Part B"
    ob_start(); // Start output buffering

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>PDF Export</title>
      <style>
        /* Add your custom styles for PDF here */
        body {
          font-family: Arial, sans-serif;
        }
        .card-title {
          margin-bottom: 10px;
        }
        .form-control[readonly] {
          background-color: #f0f0f0;
        }
        .table {
          width: 100%;
          margin-bottom: 1rem;
          color: #212529;
          border-collapse: collapse;
        }
        .table th, .table td {
          padding: 0.75rem;
          vertical-align: top;
          border-top: 1px solid #dee2e6;
        }
        .table th {
          vertical-align: bottom;
          border-bottom: 2px solid #dee2e6;
        }
      </style>
    </head>
    <body>
      <h1>CREDIT EXEMPTION DETAILS</h1>
      <h2>PART A: STUDENT'S INFO</h2>
      <form>
        <label>Name:</label>
        <input type="text" value="<?php echo $name; ?>" readonly><br><br>
        <label>IC No:</label>
        <input type="text" value="<?php echo $icno; ?>" readonly><br><br>
        <label>Year / Programme:</label>
        <input type="text" value="<?php echo "$year - $prog_code"; ?>" readonly><br><br>
        <label>Faculty:</label>
        <input type="text" value="<?php echo $faculty; ?>" readonly><br><br>
        <label>Matriculation No:</label>
        <input type="text" value="<?php echo $matric_number; ?>" readonly><br><br>
        <label>Session / Semester:</label>
        <input type="text" value="<?php echo $session . ' - ' . $semester; ?>" readonly><br><br>
        <label>Total Credit Exemption obtained before:</label>
        <input type="text" value="0" readonly><br><br>
        <label>Total Credit Exemption applied this semester:</label>
        <input type="text" value="<?php echo $totalcredit; ?>" readonly><br><br>
      </form>

      <h2>PART B: COURSES' INFO</h2>
      <table border="1" class="table">
        <thead>
          <tr>
            <th>NO</th>
            <th>CODE</th>
            <th>TITLE</th>
            <th>CREDIT</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT * FROM student_courses WHERE stud_id = '$stud_id'";
          $sg = $conn->query($query);
          $no = 0;
          if ($sg && $sg->num_rows > 0) {
              while ($row = $sg->fetch_assoc()) {
                  $no++;
                  ?>
                  <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $row['course_code']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['credit_hour']; ?></td>
                  </tr>
                  <?php
              }
          } else {
              echo "<tr><td colspan='4'>No Record Found!</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </body>
    </html>
    <?php

    $html = ob_get_clean(); // Get the buffered output and clean the buffer

    // Generate PDF and output to browser
    generatePDF($html, 'credit_exemption_details.pdf');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard</title>
</head>
<body>
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <section class="section dashboard">
  <div class="row">
    <!-- Alert message -->
    <div class="col-lg-9">
      <div class="alert alert-<?php echo $alert_type; ?> bg-<?php echo $alert_type; ?> text-light" role="alert">
        <?php echo $message; ?>
      </div>
    </div>

    <!-- Left side columns -->
    <div class="col-lg-9">
      <div class="row">
        <div class="card">
          <div class="card-body">
            <h1 class="card-title text-center">CREDIT EXEMPTION DETAILS</h1>
            <h5 class="card-title">PART A: STUDENT'S INFO</h5>
            <!-- Multi Columns Form -->
            <form class="row g-3">
              <div class="col-md-12">
                <label for="inputName5" class="form-label">Name</label>
                <input type="text" class="form-control" id="inputName5" value="<?php echo $name?>" readonly>
              </div>

              <div class="col-md-4">
                <label for="inputEmail5" class="form-label">IC No</label>
                <input type="text" class="form-control" id="inputEmail5" value="<?php echo $icno?>" readonly>
              </div>

              <div class="col-md-4">
                <label for="inputPassword5" class="form-label">Year / Programme</label>
                <?php
                // Calculate total credit
                $stud_id = $_SESSION['stud_id'] ?? ''; // Use null coalescing operator to handle undefined index
                $total = "SELECT stud_id, totalCredit FROM student_total_credits WHERE stud_id = '$stud_id'";
                $rs2 = $conn->query($total);

                if ($rs2 && $rs2->num_rows > 0) {
                    $rows2 = $rs2->fetch_assoc();
                    $totalcredit = $rows2['totalCredit'];
                    if ($totalcredit <= 14) {
                        $year = 1;
                        $semester = 1;
                    } elseif ($totalcredit >= 15 && $totalcredit <= 27) {
                        $year = 1;
                        $semester = 2;
                    } elseif ($totalcredit >= 28 && $totalcredit <= 36) {
                        $year = 2;
                        $semester = 1;
                    } else {
                        $year = 2;
                        $semester = 2;
                    }
                } else {
                    $totalcredit = 0;
                    $year = 1; // Default to year 1 if no records found
                    $semester = 1; // Default to semester 1 if no records found
                }
                ?>
                <input type="text" class="form-control" id="inputPassword5" value="<?php echo "$year - $prog_code" ?>" readonly>
              </div>

              <div class="col-md-4">
                <label for="inputPassword5" class="form-label">Faculty</label>
                <input type="text" class="form-control" id="inputPassword5" value="<?php echo $faculty ?>" readonly>
              </div>

              <div class="col-md-6">
                <label for="inputEmail5" class="form-label">Matriculation No</label>
                <input type="text" class="form-control" id="inputEmail5" value="<?php echo $matric_number?>" readonly>
              </div>

              <div class="col-md-6">
                <label for="inputPassword5" class="form-label">Session / Semester</label>
                <input type="text" class="form-control" id="inputPassword5" value="<?php echo $session . ' - ' . $semester?>" readonly>
              </div>

              <div class="col-md-6">
                <label for="inputPassword5" class="form-label">Total Credit Exemption obtained before</label>
                <input type="text" class="form-control" id="inputPassword5" value="0" readonly>
              </div>

              <div class="col-md-6">
                <label for="inputPassword5" class="form-label">Total Credit Exemption applied this semester</label>
                <?php
                // Calculate total credit
                $stud_id = $_SESSION['stud_id'];
                $total = "SELECT stud_id, totalCredit FROM student_total_credits WHERE stud_id = '$stud_id'";
                $rs2 = $conn->query($total);

                if ($rs2 && $rs2->num_rows > 0) {
                    $rows2 = $rs2->fetch_assoc();
                    $totalcredit = $rows2['totalCredit'];
                } else {
                    $totalcredit = 0;
                }
                ?>
                <input type="text" class="form-control" id="inputPassword5" value="<?php echo $totalcredit ?>" readonly>
              </div>
            </form><!-- End Multi Columns Form -->

            <h5 class="card-title">PART B: COURSES' INFO</h5>
            <p style="text-align: center">COURSE GIVEN CREDIT EXEMPTION IN THIS SEMESTER</p>
            <!-- Bordered Table -->
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">NO</th>
                  <th scope="col">CODE</th>
                  <th scope="col">TITLE</th>
                  <th scope="col">CREDIT</th>
                </tr>
              </thead>
              <tbody>
                <?php
                 $stud_id = $_SESSION['stud_id'];

                 $query = "SELECT * FROM student_courses WHERE stud_id =  '$stud_id'";

                $sg = $conn->query($query);
                $no = 0;

                if ($sg && $sg->num_rows > 0) {
                    while ($row = $sg->fetch_assoc()) {
                        $no++;
                ?>
                <tr>
                  <th scope="row"><?php echo $no?></th>
                  <td><?php echo $row['course_code'] ?></td>
                  <td><?php echo $row['title'] ?></td>
                  <td><?php echo $row['credit_hour'] ?></td>
                </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No Record Found!</td></tr>";
                }
                ?>
              </tbody>
            </table>
            <!-- End Bordered Table -->
          </div>
        </div><!-- End Default Card -->
      </div>
    </div><!-- End Left side columns -->

    <!-- Right side columns -->
    <div class="col-lg-3">
    <!-- Recent Activity -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Recent Activity <span>| Today</span></h5>

            <div class="activity">
            <?php
            $stud_id = $_SESSION['stud_id'];
              // Fetch recent transfer activity from the database
              $query = "SELECT * FROM transfer t
                        JOIN grade g ON t.grade_id = g.grade_id
                        JOIN student s ON g.stud_id = s.stud_id
                        WHERE s.stud_id = '$stud_id'
                        ORDER BY transfer_date DESC LIMIT 5"; // Change the query according to your database schema and requirements
              $result = $conn->query($query);

              if ($result && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      // Calculate the time elapsed since the transfer
                      $transfer_date = strtotime($row['transfer_date']);
                      $current_time = time();
                      $elapsed_time = $current_time - $transfer_date;

                      // Convert elapsed time to hours
                      $elapsed_hours = floor($elapsed_time / 3600);

                      // Determine appropriate label and badge color based on elapsed time
                      $label = '';
                      $badge_color = '';

                      if ($elapsed_hours >= 48) {
                          // More than 48 hours
                          $label = floor($elapsed_hours / 24) . ' days';
                          $badge_color = 'text-warning';
                      } elseif ($elapsed_hours >= 1) {
                          // At least 1 hour
                          $label = $elapsed_hours . ' hrs';
                          $badge_color = 'text-primary';
                      } else {
                          // Less than 1 hour
                          $label = 'Less than 1 hr';
                          $badge_color = 'text-success';
                      }
                      ?>

                      <div class="activity-item d-flex">
                          <div class="activite-label"><?php echo $label; ?></div>
                          <i class='bi bi-circle-fill activity-badge <?php echo $badge_color; ?> align-self-start'></i>
                          <div class="activity-content">
                              <!-- Replace this part with your actual activity content -->
                              Student transferred credit
                          </div>
                      </div><!-- End activity item-->
                  <?php
                  }
              } else {
                  echo "<p No recent activity found.</p>";

               }

               ?>
</div>
</div>
</div>
</div><!-- End Right side columns -->
</main><!-- End #main -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
  <script>
    // Function to generate PDF
    function generatePDF() {
      const element = document.getElementById("pdf-content");

      html2canvas(element).then((canvas) => {
        const imgData = canvas.toDataURL("image/png");
        const pdf = new jsPDF();
        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
        
        pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
        pdf.save("dashboard.pdf");
      });
    }
  </script>
</body>
</html>

