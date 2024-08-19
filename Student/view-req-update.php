<?php
error_reporting();
include('header.php');
include('../include/connection.php');
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
  <h1>Teaching Plan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Teaching Plan</li>
      <li class="breadcrumb-item active">View Request Status </li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- section start for registration form and datatable -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">

<!-- DataTable for list of institutions -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Request Update Status</h5>
        <p>List of teaching plan update status</p>
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Course</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>View Link</th>
                    <th>Request Dte</th>

                </tr>
            </thead>
            <tbody>
            <?php
            // Assuming you have already established a database connection
            $stud_id = $_SESSION['stud_id'];

            // Fetching data from the database
            $query = "SELECT c.course_code, c.title, r.message, r.status, r.link, r.request_date
                      FROM request r
                      JOIN course c ON r.course = c.course_id
                      WHERE  stud_id = '$stud_id'
                      ORDER BY request_date";

            $rs = $conn->query($query);
            $sn = 0;

            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    $pdfLink = $row['link']; // Change the path as needed
                    ?>
                        <tr>
                            <td><?php echo $sn ?></td>
                            <td><?php echo $row['course_code']. ' - '.$row['title'] ?></td>
                            <td><?php echo $row['message'] ?></td>
                            <td><?php echo $row['status'] ?></td>
                            <td><a href='".$pdfLink."' target='_blank'>View</a></td>
                            <td><?php echo date('d/m/Y', strtotime($row['request_date'])); ?></td>



                        </tr>
                        <?php 
                }
            } else {
                echo "<tr><td colspan='10' class='text-center'>No Record Found!</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <!-- End Table with striped rows -->
    </div>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

<!-- DataTable for list of institutions -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Request Status for Add New TP</h5>
        <p>Current updates on new teaching plan statuses</p>
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Course</th>
                    <th>Credit Hour</th>
                    <th>Status</th>
                    <th>View Link</th>
                    <th>Request Dte</th>

                </tr>
            </thead>
            <tbody>
            <?php
            // Assuming you have already established a database connection
            $stud_id = $_SESSION['stud_id'];

            // Fetching data from the database
            $query = "SELECT code, title, credit_hour, status, tplink, date
                      FROM new_tp
                      WHERE  stud_id = '$stud_id'
                      ORDER BY date";

            $rs = $conn->query($query);
            $sn = 0;

            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    $pdfLink = "../teachingplan/pending/" .$row['tplink']; // Change the path as needed
                    ?>
                        <tr>
                            <td><?php echo $sn ?></td>
                            <td><?php echo $row['code']. ' - '.$row['title'] ?></td>
                            <td><?php echo $row['credit_hour']?></td>
                            <td><?php echo $row['status']?></td>
                            <td><a href="<?php echo $pdfLink; ?>" target='_blank'>View</a></td>
                            <td><?php echo date('d/m/Y', strtotime($row['date'])); ?></td>
                        </tr>
                        <?php 
                }
            } else {
                echo "<tr><td colspan='10' class='text-center'>No Record Found!</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <!-- End Table with striped rows -->
    </div>
</div>

        </div>
      </div>
    </div>
  </div>

</section>

</main><!-- End #main -->
</body>
</html>

<?php
include('footer.php');
?>
