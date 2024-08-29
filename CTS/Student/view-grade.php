<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('header.php');
include('../include/connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Add Diploma Course Grade</title>
</head>

<body>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>View Diploma Course Grade</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item">Diploma Grade</li>
                <li class="breadcrumb-item active">View Diploma Grade</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">View Diploma Course Grade</h5>
                    
                    <p>This section displays the grades for diploma courses that have been filled out by the student.</p>

                    <!-- Bordered Table -->
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Bachelor Course</th>
                            <th scope="col">Diploma Course</th>
                            <th scope="col">Grade</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $stud_id = $_SESSION['stud_id'];

                        $query = " SELECT DISTINCT g.grade_id, d.course_code, d.title, g.similar, g.grade, g.similar2, g.grade2
                                    FROM grade g
                                    JOIN course d ON g.course_id = d.course_id
                                    WHERE g.stud_id = '$stud_id'
                                    AND g.similar != 'N/A'
                                    AND g.grade != 'N/A'";
 
                        $sg = $conn->query($query);
                        $no = 0;

                        if ($sg && $sg->num_rows > 0) {
                            while ($row = $sg->fetch_assoc()) {
                                $no++;
                                $isSimilar2Visible = $row['similar2'] !== 'N/A';
                                $isGrade2Visible = $row['grade2'] !== 'N/A';
                    ?>
                                <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $row['course_code'] .' '. $row['title']; ?></td>
                                <td>
                                <?php 
                                    // Display similar and grade  
                                    echo $row['similar']; 
                                    echo '<br>';
                                    // Display similar2 and grade2 only if similar2 is not 'N/A'
                                    if ($isSimilar2Visible) {
                                        echo ' ' . $row['similar2'];
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    // Display similar and grade
                                    echo $row['grade']; 
                                    echo '<br>';
                                    // Display similar2 and grade2 only if similar2 is not 'N/A'
                                    if ($isSimilar2Visible) {
                                        echo ' ' . $row['grade2'];
                                    }
                                    ?>

                                </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No Record Found!</td></tr>";
                        }
                        ?>

                        </tbody>
                    </table>
                    <!-- End Bordered Table -->
                    <a href="transfer.php" class="btn btn-primary mt-3">Proceed to Credit Transfer</a>

                </div>
            </div>
        </div>
    </section>
</main>

<?php include('footer.php'); ?>

</body>
</html>
