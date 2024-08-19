<?php
include('session.php');
include('../include/connection.php');

  $query = "SELECT * FROM admin WHERE admin_id = ".$_SESSION['admin_id']."";
  $rs = $conn->query($query);
  $num = $rs->num_rows;
  $rows = $rs->fetch_assoc();
  $fullName = $rows['name'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - Admin</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/transfer.png" rel="icon">
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

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="../assets/img/transfer.png" alt="">
        <span class="d-none d-lg-block">Admin</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->


    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="../assets/img/admin.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $fullName?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $fullName?></h6>
              <span>Administrator</span>
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

      <li class="nav-heading">Registration</li>


      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="">
          <i class="bi bi-journal-text"></i><span>Registration Forms</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
            <a href="rprogramme.php">
              <i class="bi bi-circle"></i><span>Register Programme</span>
            </a>
          </li>
          <li>

            <a href="rinstitution.php">
              <i class="bi bi-circle"></i><span>Register Institution</span>
            </a>
          </li>
          <li>
        </ul>
      </li><!-- End Forms Nav -->

      <li class="nav-heading">Courses</li>
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="ri-book-open-line"></i><span>Manage Course</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

          <li>
            <a href="add-course.php">
              <i class="bi bi-circle"></i><span>Add Course</span>
            </a>
          </li>

          <li>
            <a href="view-request.php">
              <i class="bi bi-circle"></i><span>TP Request Status</span>
            </a>
          </li>

          <li>
            <a href="new-tp.php">
              <i class="bi bi-circle"></i><span>New TP Status</span>
            </a>
          </li>

        </ul>
      </li> 
      <!-- End Course Nav -->

<!-- lecturer navigation -->
      <li class="nav-heading">Manage Lecturers</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="rlecturer.php">
        <i class="ri-user-star-line"></i>
        <span>Register Lecturer</span>
      </a>
    </li>

     <!-- student navigations -->
     <li class="nav-heading">Manage Students</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="rstudent.php">
          <i class="bi bi-person"></i>
          <span>Register Sudent</span>
        </a>
      </li>

    </ul>

  </aside><!-- End Sidebar-->

  <script>
    document.addEventListener("DOMContentLoaded", function () {
  const sidebarNav = document.getElementById("sidebar-nav");
  const sidebarNavItems = sidebarNav.querySelectorAll(".nav-item");

  sidebarNavItems.forEach((item) => {
    const collapseTrigger = item.querySelector('[data-bs-toggle="collapse"]');
    if (collapseTrigger) {
      collapseTrigger.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent default action (which adds the hash to the URL)
        const target = item.querySelector(".nav-content");
        const isExpanded = target.classList.contains("show");
        target.classList.toggle("show", !isExpanded);
      });
    }
  });
});

  </script>