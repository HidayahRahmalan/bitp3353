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
      <li class="breadcrumb-item active">View Request Status</li>
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
        <?php //if (isset($statusMsg)) echo $statusMsg; ?>
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>View Link</th>
                    <th>Request Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetching data from the database
            $query = "SELECT * FROM accepted_requests_view ORDER BY request_date DESC";
            $rs = $conn->query($query);
            $sn = 0;

            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    $pdfLink = $row['link'];
                    $formattedDate = date('d/m/Y', strtotime($row['request_date']));

                    echo "
                        <tr>
                            <td>".$sn."</td>
                            <td>" .$row['course_code']. '-'.$row['title']."</td>
                            <td>".$row['message']."</td>
                            <td>".$row['status']."</td>
                            <td><a href='".$pdfLink."' target='_blank'>View</a></td>
                          <td>".$formattedDate."</td> <!-- Display formatted date -->
                        </tr>";
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
