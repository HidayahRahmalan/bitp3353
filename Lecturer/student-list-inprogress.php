<?php
error_reporting(E_ALL); // Enable error reporting
include('header.php');
include('../include/connection.php');

// session_start(); // Start the session if not already started
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Accept Status</title>
</head>
<body>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Course Transfer</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item">Course Transfer</li>
                <li class="breadcrumb-item active">View inprogress student Status</li>
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
                        <h5 class="card-title">In-Progress Course Transfer</h5>
                        <p>List of Students with Ongoing Course Transfer</p>
                        <!-- Table with striped rows -->
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Matric No</th>
                                <th>Programme</th>
                                <th>Academic Advisor</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Fetching data from the database
                            $query = "SELECT * FROM students_in_progress_view";
                            
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $rs = $stmt->get_result();
                            
                            $sn = 0;
                            
                            if ($rs && $rs->num_rows > 0) {
                                while ($row = $rs->fetch_assoc()) {
                                    $sn++;
                                    ?>
                                    <tr>
                                        <td><?php echo $sn ?></td>
                                        <td><?php echo $row['name'] ?></td>
                                        <td><?php echo $row['username'] ?></td>
                                        <td><?php echo $row['prog_code'] ?></td>
                                        <td><?php echo $row['lect_name'] ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No students found who have not made any transfers.</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                        <!-- End Table with striped rows -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End section -->

</main><!-- End #main -->

<?php
include('footer.php');
?>
</body>
</html>
