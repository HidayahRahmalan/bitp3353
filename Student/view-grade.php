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
                    <h5 class="card-title">Bordered Table</h5>
                    <p>Add <code>.table-bordered</code> for borders on all sides of the table and cells.</p>
                    <!-- Bordered Table -->
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Course Code</th>
                            <th scope="col">Course Name</th>
                            <th scope="col">Credit Hour</th>
                            <th scope="col">Grade</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $stud_id = $_SESSION['stud_id'];

                        $query = "SELECT DISTINCT g.grade_id, d.course_code, d.title, d.credit_hour, g.grade
                                    FROM grade g
                                    JOIN course d ON g.course_id = d.course_id
                                    WHERE g.stud_id = '$stud_id'";

                        $sg = $conn->query($query);
                        $no = 0;

                        if ($sg && $sg->num_rows > 0) {
                            while ($row = $sg->fetch_assoc()) {
                                $no++;
                                echo "
                                <tr>
                                <td>".$no."</td>
                                <td>".$row['course_code']."</td>
                                <td>".$row['title']."</td>
                                <td>".$row['credit_hour']."</td>
                                <td>
                                    <select class='form-select' onchange='updateGrade(this.value, ".$row['grade_id'].")'>
                                        <option value='A' ".($row['grade'] == 'A' ? 'selected' : '').">A</option>
                                        <option value='A-' ".($row['grade'] == 'A-' ? 'selected' : '').">A-</option>
                                        <option value='B+' ".($row['grade'] == 'B+' ? 'selected' : '').">B+</option>
                                        <option value='B' ".($row['grade'] == 'B' ? 'selected' : '').">B</option>
                                        <option value='B-' ".($row['grade'] == 'B-' ? 'selected' : '').">B-</option>
                                        <option value='C+' ".($row['grade'] == 'C+' ? 'selected' : '').">C+</option>
                                        <option value='C' ".($row['grade'] == 'C' ? 'selected' : '').">C</option>
                                        <option value='C-' ".($row['grade'] == 'C-' ? 'selected' : '').">C-</option>
                                        <option value='D' ".($row['grade'] == 'D' ? 'selected' : '').">D</option>
                                    </select>
                                </td>
                                <td>
                                    <form method='post' action=''>
                                        <input type='hidden' name='grade_id' value='".$row['grade_id']."'>
                                        <button type='submit' class='btn btn-sm btn-outline-secondary'>Save</button>
                                    </form>
                                </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No Record Found!</td></tr>";
                        }
                        ?>

                        </tbody>
                    </table>
                    <!-- End Bordered Table -->
                </div>
            </div>
        </div>
    </section>
</main>

<?php include('footer.php'); ?>

<script>
    function updateGrade(grade, gradeId) {
        // Send AJAX request to update the grade
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "view-grade.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Response received, handle accordingly
                console.log(xhr.responseText); // For debugging
            }
        };
        var data = "grade=" + grade + "&grade_id=" + gradeId;
        xhr.send(data);
    }
</script>

</body>
</html>
