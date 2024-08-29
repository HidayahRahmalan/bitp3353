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
        <h5 class="card-title">Pending Status</h5>
        <p>Pending Status of Student Course Transfers</p>
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Matric No</th>
                    <th>Previous Institution</th>
                    <th>Total Credit</th>
                    <th>Status</th>
                    <th>Transfer Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetching data from the database
            $lect_id = $_SESSION['lect_id'];
            $query = "SELECT 
                            t.transfer_id,
                            s.stud_id,
                            s.name,
                            s.username,
                            t.aa_status, 
                            t.transfer_date,
                            i.int_id,
                            i.int_name,
                            SUM(c.credit_hour) AS total
                        FROM 
                            transfer t
                        JOIN 
                            grade g ON t.grade_id = g.grade_id
                        JOIN 
                            student s ON g.stud_id = s.stud_id
                        JOIN 
                            lecturer r ON s.lect_id = r.lect_id
                        JOIN 
                            course c ON g.course_id = c.course_id
                        JOIN 
                            institution i ON s.int_id = i.int_id
                        WHERE 
                            r.lect_id = '$lect_id'
                        AND 
                            t.aa_status = 'Pending'    
                        GROUP BY 
                            s.name, s.username, t.aa_status, t.transfer_date";

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
                            <td><?php echo $row['int_name'] ?></td>
                            <td><?php echo $row['total'];?></td>
                            <td><?php echo $row['aa_status'] ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['transfer_date'])); ?></td>
                            <td>
                                <button type="submit" class="btn btn-info"><a href="view-status-transfer.php? statusid=<?php echo $row['stud_id']?>" class="text-light">VIEW</a></button>
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
