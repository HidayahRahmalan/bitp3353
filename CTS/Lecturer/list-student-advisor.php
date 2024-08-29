<?php
error_reporting(E_ALL);
include('header.php');
include('../include/connection.php');

// Handle the POST request to send a notification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['stud_id'])) {
        $stud_id = $_POST['stud_id'];

        // Your notification logic here. For example, you could update a notification table in your database.
        $query = "INSERT INTO notifications (stud_id, message, status) 
                  VALUES ('$stud_id', 'You have been poked by your advisor. Please do credit transfer now!', 'unread')";

        if ($conn->query($query) === TRUE) {
            echo "Notification sent successfully";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
        exit; // Stop further processing after handling the AJAX request
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>

<main id="main" class="main">

<div class="pagetitle">
  <h1>List of student advisor</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Student</li>
      <li class="breadcrumb-item active">List of student advisor</li>
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
        <h5 class="card-title">Finish Credit Transfer Status</h5>
        <p>Detailed list providing information on academic advisors and the students under their guidance</p>
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Matric No</th>
                    <th>Total Credit</th>
                    <th>Transfer Date</th>
                    <th>Progress</th>
                    <th>Poke</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetching data from the database
            $lect_id = $_SESSION['lect_id'];
            $query = "SELECT 
                s.stud_id,
                s.name,
                s.username,
                MAX(t.transfer_id) AS transfer_id,
                MAX(t.aa_status) AS aa_status, 
                MAX(t.tda_status) AS tda_status, 
                MAX(t.dean_status) AS dean_status,
                MAX(t.transfer_date) AS transfer_date,
                COALESCE(SUM(CASE WHEN t.aa_status = 'Accepted' OR t.tda_status = 'Accepted' OR t.dean_status = 'Accepted' THEN c.credit_hour ELSE 0 END), 0) AS total_accepted_credit,
                COUNT(DISTINCT c.course_id) AS total_courses,
                SUM(CASE WHEN g.grade IS NOT NULL THEN 1 ELSE 0 END) AS completed_courses
            FROM 
                student s
            LEFT JOIN 
                grade g ON s.stud_id = g.stud_id
            LEFT JOIN 
                transfer t ON g.grade_id = t.grade_id
            LEFT JOIN 
                course c ON g.course_id = c.course_id
            WHERE 
                s.lect_id = '$lect_id'
            GROUP BY 
                s.stud_id, s.name, s.username
            ORDER BY 
                s.name ASC;";

            $rs = $conn->query($query);
            $sn = 0;

            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    $totalCourses = $row['total_courses'];
                    $completedCourses = $row['completed_courses'];
                    $totalAcceptedCredit = $row['total_accepted_credit'];
                    $progressPercentage = $totalCourses > 0 ? ($completedCourses / $totalCourses) * 100 : 0;
                    ?>
                    <tr>
                        <td><?php echo $sn ?></td>
                        <td><?php echo $row['name'] ?></td>
                        <td><?php echo $row['username'] ?></td>
                        <td><?php echo isset($totalAcceptedCredit) ? $totalAcceptedCredit : 'N/A'; ?></td>
                        <td><?php echo !empty($row['transfer_date']) ? date('d/m/Y', strtotime($row['transfer_date'])) : 'N/A'; ?></td>                           
                        <td>
                            <div class="progress mt-3">
                                <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: <?php echo $progressPercentage; ?>%" aria-valuenow="<?php echo $progressPercentage; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($progressPercentage); ?>%</div>
                            </div>
                    </td>
                    <td>
                            <?php if ($progressPercentage == 0): ?>
                                <button class="btn btn-warning poke-btn" data-stud-id="<?php echo $row['stud_id']; ?>"><i class="bi bi-hand-index"></i></button>
                            <?php endif; ?>
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
<script>
    $(document).ready(function() {
        $('.poke-btn').click(function() {
            var studId = $(this).data('stud-id');
            $.ajax({
                url: '', // Current file or specify the URL to handle the request
                type: 'POST',
                data: { stud_id: studId },
                success: function(response) {
                    alert('Student has been poked successfully!');
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });
        });
    });
</script>

</html>

<?php
include('footer.php');
?>
