<?php
include('session.php');
include('../include/connection.php');

// Retrieve lecturer details
$query = "SELECT * FROM lecturer WHERE lect_id = ".$_SESSION['lect_id']."";
$rs = $conn->query($query);
$num = $rs->num_rows;
$rows = $rs->fetch_assoc();
$fullName = $rows['lect_name'];
$role = $rows['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - Lecturer</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/transfer.png" rel="icon">
  <link href="../assets/img/transfer.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

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

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="../assets/img/logoftmk.png" alt="">
        <span class="d-none d-lg-block">Lecturer</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <!-- Notification and message icons -->
        <!-- Profile dropdown -->
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="../assets/img/school.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $fullName ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $fullName ?></h6>
              <span>FTMK LECTURER</span> <br>
              <span>(<?php echo $role ?>)</span>

            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item d-flex align-items-center" href="users-profile.php"><i class="bi bi-person"></i><span>My Profile</span></a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item d-flex align-items-center" href="../logout.php"><i class="bi bi-box-arrow-right"></i><span>Sign Out</span></a></li>
          </ul>
        </li><!-- End Profile Nav -->
      </ul>
    </nav><!-- End Icons Navigation -->
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link collapsed" href="index.php"><i class="bi bi-grid"></i><span>Dashboard</span></a>
      </li><!-- End Dashboard Nav -->

      <?php if ($role == "Academic Advisor"): ?>
        <!-- Academic Advisor nav -->
        <li class="nav-heading">Student</li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-journal-text"></i><span>Student Course Transfer</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li><a href="student-list.php"><i class="bi bi-circle"></i><span>Pending Credit Transfer</span></a></li>
            <!-- Add more items as needed -->
          </ul>

          <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li><a href="student-list-accept.php"><i class="bi bi-circle"></i><span>Finish Credit Transfer</span></a></li>
            <!-- Add more items as needed -->
          </ul>
<!-- 
          <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li><a href="student-list-inprogress.php"><i class="bi bi-circle"></i><span>Students in progress transfers</span></a></li>
          </ul>
        </li> -->
        <!-- End Academic Advisor Nav -->

        <li class="nav-item">
        <a class="nav-link collapsed" href="list-student-advisor.php">
          <i class="bi bi-people"></i>
          <span>Student advisor list</span>
        </a>
      </li>

        <li class="nav-heading">Teaching Plan Request</li>
        <li class="nav-item">
        <a class="nav-link collapsed" href="view-tp-req.php">
          <i class="bi bi-card-list"></i>
          <span>Teaching Plan Request</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="view-new-req-tp.php">
          <i class="bi bi-card-list"></i>
          <span>Request for Adding a New Teaching Plan</span>
        </a>
      </li>

      <?php endif; ?>

      <?php if ($role == "Academic Deputy Dean"): ?>
        <!-- Academic Deputy Dean Nav-->
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span>Student Course Transfer</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li><a href="tda-student-list.php"><i class="bi bi-circle"></i><span>Pending Credit Transfer</span></a></li>
            <li><a href="tda-student-list-accept.php"><i class="bi bi-circle"></i><span>Accept Credit Transfer</span></a></li>
            <li><a href="student-list-inprogress.php"><i class="bi bi-circle"></i><span>In-Progress Credit transfers</span></a></li>
          </ul>
        </li><!-- End Academic Deputy Dean Nav -->
      <?php endif; ?>

      <?php if ($role == "Dean"): ?>
        <!-- Dean Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-bar-chart"></i><span>Student Course Transfer</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li><a href="dean-student-list.php"><i class="bi bi-circle"></i><span>Pending Credit Transfer</span></a></li>
            <li><a href="dean-student-list-accept.php"><i class="bi bi-circle"></i><span>Accept Credit Transfer</span></a></li>
            <li><a href="student-list-inprogress.php"><i class="bi bi-circle"></i><span>In-Progress Credit transfers</span></a></li>
          </ul>
        </li><!-- End Dean Nav -->
      <?php endif; ?>

      <li class="nav-heading">Teaching Plan</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="teaching-plan.php"><i class="bi bi-file-pdf"></i><span>Bachelor Teaching Plan</span></a>
      </li>
      <!-- End Course Teaching Plan Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="dip-tp.php"><i class="bi bi-file-pdf"></i><span>Diploma Teaching Plan</span></a>
      </li>

    </ul>
  </aside><!-- End Sidebar -->
  
