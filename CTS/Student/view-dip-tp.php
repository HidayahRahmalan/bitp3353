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
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Ensure Bootstrap CSS is included -->
</head>
<body>

<main id="main" class="main">

<div class="pagetitle">
  <h1>Teaching Plan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Teaching Plan</li>
      <li class="breadcrumb-item active">Diploma Course Teaching Plan</li>
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Teaching Plan List</h5>
            <a href="add-new-tp.php" class="btn btn-success">Request Add New TP</a>
        </div>
        <p>List of registered diploma teaching plan</p>
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
                    <th>Request Update</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Assuming you have already established a database connection

            // Fetching data from the database
            $query = "SELECT d.course_id, d.course_code, d.title, d.credit_hour, d.type, d.tpfile, i.int_name, i.branch
                      FROM course d
                      JOIN institution i ON d.int_id = i.int_id
                      WHERE d.type LIKE 'Diploma'
                      ORDER BY d.title";

            $rs = $conn->query($query);
            $sn = 0;

            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    $pdfLink = "../teachingplan/" . $row['tpfile']; // Change the path as needed
                    ?>
                        <tr>
                            <td><?php echo $sn ?></td>
                            <td><?php echo $row['course_code'] ?></td>
                            <td><?php echo $row['title'] ?></td>
                            <td><?php echo $row['credit_hour'] ?></td>
                            <td><a href="<?php echo $pdfLink ?>" target="_blank">View</a></td>
                            <td><?php echo $row['int_name'] . ' <b>' . $row['branch'] ?></b></td>
                            <td><a href="request-update.php?requestid=<?php echo $row['course_id'] ?>" class="btn btn-info text-white">Request</a></td>
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
<?php
include('footer.php');
?>
</body>
</html>
