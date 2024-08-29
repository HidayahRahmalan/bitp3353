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
      <li class="breadcrumb-item active">View Finish Transfer</li>
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
        <h5 class="card-title">Finish Transfer</h5>
        <p>List of Approved Course Transfers per Student</p>
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Student Details</th>
                    <th>Total Credit</th>
                    <th>Status</th>
                    <th>Transfer Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetching data from the database
            $query = "SELECT * FROM student_finish_dean_view";


            $rs = $conn->query($query);
            $sn = 0;

            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    ?>
                  
                        <tr>
                            <td><?php echo $sn ?></td>
                            <td>
                                <b>Name:</b> <?php echo $row['name']; ?><br>
                                <b>Matric No:</b> <?php echo $row['username']; ?><br>
                                <b>Programme:</b> <?php echo $row['prog_code']; ?><br>
                                <b>Academic Advisor:</b> <?php echo $row['lect_name']; ?><br>
                                <b>Prev Institution:</b> <?php echo $row['int_name']; ?>
                            </td>
                            <td><?php echo $row['total'];?></td>
                            <td style="color: <?php echo ($row['aa_status'] == 'Accepted' && $row['tda_status'] == 'Accepted' && $row['dean_status'] == 'Accepted') ? 'green' : 'blue'; ?>">
                                            <?php 
                                            echo "AA: " . $row['aa_status'] . "<br>";
                                            echo "TDA: " . $row['tda_status'] . "<br>";
                                            echo "Dean: " . $row['dean_status']; 
                                            ?>
                           </td>                              
                            <td><?php echo date('d/m/Y', strtotime($row['transfer_date'])); ?></td>
                            <td>
                                <button type="submit" class="btn btn-info"><a href="dean-view-accept-transfer.php? statusid=<?php echo $row['stud_id']?>" class="text-light">VIEW</a></button>
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
