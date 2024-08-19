<?php
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

// Retrieve unread notifications
$notification_query = "SELECT id, message FROM notifications WHERE stud_id = '$stud_id' AND status = 'unread'";
$notification_result = $conn->query($notification_query);
$notifications = [];

if ($notification_result && $notification_result->num_rows > 0) {
    while ($notification_row = $notification_result->fetch_assoc()) {
        $notifications[] = $notification_row;
    }
}

// Update notifications to 'read' status
$update_query = "UPDATE notifications SET status = 'read' WHERE stud_id = '$stud_id' AND status = 'unread'";
$conn->query($update_query);

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script> -->
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
        <div class="col-lg-12">
          <div class="alert alert-<?php echo $alert_type; ?> bg-<?php echo $alert_type; ?> text-light" role="alert">
            <?php echo $message; ?>
          </div>
        </div>

        <!-- Left side columns -->
        <div class="col-lg-12">
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

        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul id="notificationList" class="list-group">
                                <!-- Notifications will be appended here -->
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
      </div>

      </div>
    </section>
  </main><!-- End #main -->

  <script>
// // Function to generate and download PDF
// function saveToPDF() {
//   // Capture the whole content to convert to PDF
//   const element = document.getElementById('main');

//   html2canvas(element).then(canvas => {
//     const imgData = canvas.toDataURL('image/png');
//     const pdf = new jsPDF('p', 'pt', 'a4');
//     const imgProps= pdf.getImageProperties(imgData);
//     const pdfWidth = pdf.internal.pageSize.getWidth();
//     const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
    
//     pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
    
//     // Download the PDF file
//     pdf.save('dashboard.pdf');
//   });
// }

$(document).ready(function() {
        var notifications = <?php echo json_encode($notifications); ?>;
        
        if (notifications.length > 0) {
            var notificationList = $('#notificationList');
            notifications.forEach(function(notification) {
                notificationList.append('<li class="list-group-item">' + notification.message + '</li>');
            });

            // Show the notification modal
            $('#notificationModal').modal('show');
        }
    });

</script>

  <?php
  include('footer.php');
  ?>
</body>
</html>
