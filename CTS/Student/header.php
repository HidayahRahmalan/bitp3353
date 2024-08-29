<?php
include('session.php');
include('../include/connection.php');

  $query = "SELECT * FROM student WHERE stud_id = ".$_SESSION['stud_id']."";
  $rs = $conn->query($query);
  $num = $rs->num_rows;
  $rows = $rs->fetch_assoc();
  $fullName = $rows['name'];

 // Prepare arrays to hold notifications
$transfer_notifications = [];
$teaching_plan_requests = [];
$teaching_plan_requests1 = []; // Add this array for new teaching plans

// Fetch Transfer Status Notifications
$stud_id = $_SESSION['stud_id'];
$status_query = "SELECT t.aa_status, t.tda_status, t.dean_status
                FROM transfer t
                JOIN grade g ON t.grade_id = g.grade_id
                JOIN course c ON g.course_id = c.course_id
                WHERE stud_id = '$stud_id'";
$status_result = $conn->query($status_query);

if ($status_result && $status_result->num_rows > 0) {
    $statuses = $status_result->fetch_assoc();
    $aa_status = $statuses['aa_status'];
    $tda_status = $statuses['tda_status'];
    $dean_status = $statuses['dean_status'];

    if ($aa_status == 'Accepted' && $tda_status == 'Accepted' && $dean_status == 'Accepted') {
        $transfer_notifications[] = [
            'icon' => 'bi-check-circle',
            'color' => 'success',
            'title' => 'Successful Transfer Credit',
            'message' => 'Your transfer credit has been successfully completed.',
            'time_ago' => 'Just now'
        ];
    } elseif ($aa_status == 'Rejected' || $tda_status == 'Rejected' || $dean_status == 'Rejected') {
        $transfer_notifications[] = [
            'icon' => 'bi-exclamation-octagon',
            'color' => 'danger',
            'title' => 'Transfer Failed',
            'message' => 'Transfer failed, status is rejected. Please <a href="reject.php" style="color: white; font-weight: bold; text-decoration: underline;">view details</a> and try again.',
            'time_ago' => 'Just now'
        ];
    } else {
        $pending_statuses = [];
        if ($aa_status == 'Pending') $pending_statuses[] = "academic advisor";
        if ($tda_status == 'Pending') $pending_statuses[] = "TDA";
        if ($dean_status == 'Pending') $pending_statuses[] = "dean";
        $pending_list = implode(", ", $pending_statuses);
        $transfer_notifications[] = [
            'icon' => 'bi-info-circle',
            'color' => 'primary',
            'title' => 'Transfer Pending',
            'message' => 'Transfer credit is pending, waiting for '.$pending_list.' approval.',
            'time_ago' => 'Just now'
        ];
    }
}

// Fetch Teaching Plan Request Notifications
$request_query = "SELECT c.course_code, c.title, r.message, r.status, r.link, r.request_date
    FROM request r
    JOIN course c ON r.course = c.course_id
    WHERE stud_id = '$stud_id'
    ORDER BY request_date DESC";
$request_result = $conn->query($request_query);

if ($request_result && $request_result->num_rows > 0) {
    while ($request = $request_result->fetch_assoc()) {
        $course_code = $request['course_code'];
        $title = $request['title'];
        $status = $request['status'];
        $message = $request['message'];
        $link = $request['link'];
        $request_date = $request['request_date'];

        if ($status == 'Accepted') {
            $teaching_plan_requests[] = [
                'icon' => 'bi-check-circle',
                'color' => 'success',
                'title' => 'Update Teaching Plan Accepted',
                'message' => 'Your update teaching plan for '.$course_code.': '.$title.' has been accepted.',
                'time_ago' => 'Just now'
            ];
        } elseif ($status == 'Rejected') {
            $teaching_plan_requests[] = [
                'icon' => 'bi-x-circle',
                'color' => 'danger',
                'title' => 'Update Teaching Plan Rejected',
                'message' => 'Your update teaching plan for '.$course_code.': '.$title.' has been rejected. <a href="'.$link.'" style="color: white; font-weight: bold; text-decoration: underline;">View details</a>',
                'time_ago' => 'Just now'
            ];
        } else {
            $teaching_plan_requests[] = [
                'icon' => 'bi-info-circle',
                'color' => 'primary',
                'title' => 'Update Teaching Plan Pending',
                'message' => 'Your update teaching plan for '.$course_code.': '.$title.' is pending.',
                'time_ago' => 'Just now'
            ];
        }
    }
}

$request_query1 = "SELECT code, title, credit_hour, status, tplink, date, review_date
                      FROM new_tp
                      WHERE stud_id = '$stud_id'
                      ORDER BY review_date DESC";
$request_result1 = $conn->query($request_query1);

if ($request_result1 && $request_result1->num_rows > 0) {
    while ($request1 = $request_result1->fetch_assoc()) {
        $code = $request1['code'];
        $title1 = $request1['title'];
        $status1 = $request1['status'];

        if ($status1 == 'Accepted') {
            $teaching_plan_requests1[] = [
                'icon' => 'bi-check-circle',
                'color' => 'success',
                'title' => 'Add New Teaching Plan Accepted',
                'message' => 'Your new teaching plan for '.$code.': '.$title1.' has been accepted.',
                'time_ago' => 'Just now'
            ];
        } elseif ($status1 == 'Rejected') {
            $teaching_plan_requests1[] = [
                'icon' => 'bi-x-circle',
                'color' => 'danger',
                'title' => 'Add New Teaching Plan Rejected',
                'message' => 'Your new teaching plan for '.$code.': '.$title1.' has been rejected. <a href="'.$link.'" style="color: white; font-weight: bold; text-decoration: underline;">View details</a>',
                'time_ago' => 'Just now'
            ];
        } else {
            $teaching_plan_requests1[] = [
                'icon' => 'bi-info-circle',
                'color' => 'primary',
                'title' => 'Add New Teaching Plan Pending',
                'message' => 'Your new teaching plan for '.$code.': '.$title1.' is pending.',
                'time_ago' => 'Just now'
            ];
        }
    }
}


// Combine all notifications
$notifications = array_merge($transfer_notifications, $teaching_plan_requests, $teaching_plan_requests1);
$notifications_count = count($notifications);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Student</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/transfer.png"rel="icon">
  <link href="../assets/img/transfer.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <style>
  .notifications {
      max-height: 300px; /* Adjust the height as needed */
      overflow-y: auto; /* Enables vertical scrolling */
  }
  </style>

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="../assets/img/logoftmk.png" alt="">
        <span class="d-none d-lg-block">Student CTS</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

      <li class="nav-item dropdown">

      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
    <i class="bi bi-bell"></i>
    <span class="badge bg-primary badge-number"><?php echo $notifications_count; ?></span>
</a><!-- End Notification Icon -->

<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
    <li class="dropdown-header">
        You have <?php echo $notifications_count; ?> new notifications
        <a href="view-req-update.php"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
    </li>
    <li>
        <hr class="dropdown-divider">
    </li>

    <?php foreach ($notifications as $notification): ?>
    <li class="notification-item">
        <i class="bi <?php echo $notification['icon']; ?> text-<?php echo $notification['color']; ?>"></i>
        <div>
            <h4><?php echo $notification['title']; ?></h4>
            <p><?php echo $notification['message']; ?></p>
            <p><?php echo $notification['time_ago']; ?></p>
        </div>
    </li>
    <li>
        <hr class="dropdown-divider">
    </li>
    <?php endforeach; ?>

    <li class="dropdown-footer">
        <a href="view-req-update.php">Show all notifications</a>
    </li>
</ul><!-- End Notification Dropdown Items -->




        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="../assets/img/graduate.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $fullName;?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $fullName?></h6>
              <span>FTMK STUDENT</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="../logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="index.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
  
      
      <!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="">
          <i class="bi bi-file-pdf"></i><span>Teaching Plan</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
            <a href="teaching-plan.php">
              <i class="bi bi-circle"></i><span>Bachelor Course TP</span>
            </a>
          </li>

          <li>
            <a href="view-dip-tp.php">
              <i class="bi bi-circle"></i><span>Diploma Course TP</span>
            </a>
          </li>

          <li>
            <a href="view-req-update.php">
              <i class="bi bi-circle"></i><span>View Update Status</span>
            </a>
          </li>
          <li>
        </ul>
      </li><!-- End Forms Nav -->

     <li class="nav-item">
        <a class="nav-link collapsed" href="add-grade.php">
          <i class="ri-draft-line"></i>
          <span>Add Diploma Grade</span>
        </a>
      </li> <!-- End Contact Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="view-grade.php">
          <i class="ri-award-line"></i>
          <span>View Diploma Grade</span>
        </a>
      </li> <!-- End Contact Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="transfer.php">
          <i class="ri-folder-shared-line"></i>
          <span>Transfer Credit</span>
        </a>
      </li><!-- End Register Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="reject.php">
          <i class="bx bx-message-alt-error"></i>
          <span>Reject Status</span>
        </a>
      </li><!-- End Register Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->
