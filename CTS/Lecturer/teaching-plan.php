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
      <li class="breadcrumb-item active">Bachelor Course Teaching Plan</li>
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
        <h5 class="card-title">Teaching Plan List</h5>
        <p>List of registered teaching plan for bachelor degree course in UTeM</p>
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Code</th>
                    <th>Title</th>
                    <th>Credit Hour</th>
                    <th>View</th>
                    <th>Institution</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Assuming you have already established a database connection

            // Fetching data from the database
            $query = "SELECT * FROM bachelor_courses_view";

            $rs = $conn->query($query);
            $sn = 0;

            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    $pdfLink = "../teachingplan/" . $row['tpfile']; // Change the path as needed
                    echo "
                        <tr>
                            <td>".$sn."</td>
                            <td>".$row['course_code']."</td>
                            <td>".$row['title']."</td>
                            <td>".$row['credit_hour']."</td>
                            <td><a href='".$pdfLink."' target='_blank'>View</a></td>
                            <td>".$row['int_name']." <b>".$row['branch']."</b></td>

                        </tr>";
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
