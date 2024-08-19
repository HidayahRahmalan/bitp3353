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
  <h1>Course Transfer</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Course Transfer</li>
      <li class="breadcrumb-item active">Pending Status</li>
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
        <p>Pending Status of Student Course Transfers</p>
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Matric No</th>
                    <th>Total Credit</th>
                    <th>AA Status</th>
                    <th>TDA Status</th>
                    <th>Dean Status</th>
                    <th>Transfer Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetching data from the database
            $query = "SELECT * FROM student_pending_dean_view";


            $rs = $conn->query($query);
            $sn = 0;

            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    ?>
                  
                        <tr>
                            <td><?php echo $sn ?></td>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $row['username'] ?></td>
                            <td><?php echo $row['total'];?></td>
                            <td><?php echo $row['aa_status'] ?></td>
                            <td><?php echo $row['tda_status'] ?></td>
                            <td><?php echo $row['dean_status'] ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['transfer_date'])); ?></td>
                            <td>
                                <button type="submit" class="btn btn-info"><a href="dean-view-status.php? statusid=<?php echo $row['stud_id']?>" class="text-light">VIEW</a></button>
                            </td>
                        </tr>

                        <?php
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No Record Found!</td></tr>";
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
