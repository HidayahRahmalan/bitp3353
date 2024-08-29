<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('header.php');

include('../include/connection.php');

$success = false;
$errors = [];
$submitted = isset($_POST['submitted']) ? $_POST['submitted'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $submitted != 'submitted') {
    $stud_id = $_POST['stud_id'];
    $similarGrades = isset($_POST['similar']) ? $_POST['similar'] : [];
    $similarGrades2 = isset($_POST['similar2']) ? $_POST['similar2'] : [];
    $dipids = isset($_POST['course_id']) ? $_POST['course_id'] : [];
    $grades = isset($_POST['grade']) ? $_POST['grade'] : [];
    $grades2 = isset($_POST['grade2']) ? $_POST['grade2'] : [];

    // Ensure that $dipids, $similarGrades, $similarGrades2, $grades, and $grades2 are arrays
    if (!is_array($dipids) || !is_array($similarGrades) || !is_array($grades) || !is_array($similarGrades2) || !is_array($grades2)) {
        $errors[] = "Invalid data format for course_id, similar, similar2, grade, or grade2.";
    } elseif (count($dipids) == count($similarGrades) && count($dipids) == count($grades) && count($dipids) == count($similarGrades2) && count($dipids) == count($grades2)) {
        $conn->begin_transaction();
        try {
            // Check if stud_id already exists in the grade table
            $check_query = "SELECT * FROM grade WHERE stud_id = '$stud_id'";
            $check_result = $conn->query($check_query);
            if ($check_result && $check_result->num_rows > 0) {
                $errors[] = "Grades for this student already exist. You cannot submit again.";
            } else {
                // Proceed with inserting grades
                for ($i = 0; $i < count($dipids); $i++) {
                    $dipid = $conn->real_escape_string($dipids[$i]);
                    $similarGrade = $conn->real_escape_string($similarGrades[$i]);
                    $grade = $conn->real_escape_string($grades[$i]);
                    $similarGrade2 = $conn->real_escape_string($similarGrades2[$i]);
                    $grade2 = $conn->real_escape_string($grades2[$i]);

                    $query = "INSERT INTO grade (stud_id, course_id, similar, grade, similar2, grade2) VALUES ('$stud_id', '$dipid', '$similarGrade', '$grade', '$similarGrade2', '$grade2')";
                    if (!$conn->query($query)) {
                        throw new Exception("Insert failed: " . $conn->error);
                    }
                }
                $conn->commit();
                $success = true;
                $submitted = 'submitted'; // Set submitted flag after successful submission
            }
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = $e->getMessage();
        }
    } else {
        $errors[] = "Mismatch in the number of courses, similar, similar2, grades, and grades2 provided.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Add Diploma Course Grade</title>
    <style>
        /* Include the CSS styles here */
        .search-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 10px auto;
        }

        #searchInput {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            outline: none;
            transition: all 0.3s ease-in-out;
        }

        #searchInput:focus {
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
        }

        .search-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            font-size: 18px;
            color: #aaa;
            pointer-events: none;
        }

        #searchInput:focus + .search-icon {
            color: #007bff;
        }
    </style>
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Grade</h5>

                        <p>Add Diploma Course grade that has been taken by students during the diploma.
                        <code>Please Take Note:</code>
                        ALL SUBJECT <code>MUST BE SELECTED</code> if the Diploma course in N/A during Your diploma Please select <code>N/A</code>
                        </p>
                        <p>
                            <b>NOTE:</b> Diploma Course 2 should only be applied to courses that need to be combined to achieve a total of 3 credit hours.
                        </p>
                        <p>
                            <b style="color: red;">IMPORTANT:</b> Please ensure that all courses and grades match the degree course, as students will <b style="color: red;">NOT</b> be able to make edits after submission.
                            <a href="view-grade.php" style="color: Blue; text-decoration: none;">
                                <b>View Diploma Grades</b>
                            </a>
                        </p>
                       

                        <?php
                        if ($success) {
                            echo '<div class="alert alert-success">Grades added successfully!</div>';
                        }

                        if (!empty($errors)) {
                            echo '<div class="alert alert-danger">';
                            foreach ($errors as $error) {
                                echo '<p>' . htmlspecialchars($error) . '</p>';
                            }
                            echo '</div>';
                        }
                        ?>

                        <!-- Search bar -->
                        <div class="search-container">
                            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search by course code or title..">
                            <i class="fas fa-search search-icon"></i>
                        </div>

                        <form id="gradeForm" method="POST" action="">
                            <table class="table table-bordered" id="gradesTable">
                                <input type="hidden" class="form-control" name="stud_id" value="<?php echo htmlspecialchars($_SESSION['stud_id']); ?>">
                                <input type="hidden" name="submitted" value="<?php echo htmlspecialchars($submitted); ?>">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Course Code</th>
                                        <th scope="col">Course Name</th>
                                        <th scope="col">Credit Hour</th>
                                        <th scope="col">Diploma Course 1</th>
                                        <th scope="col">Grade</th>
                                        <th scope="col">
                                            Diploma Course 2
                                            <br>
                                            <small style="color: red;">(Only for 2 combination subjects)</small>
                                        </th>
                                        <th scope="col">Grade</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                $stud_id = $_SESSION['stud_id'];

                                $query = "SELECT c.course_code, c.title, c.credit_hour, c.course_id, i.int_name, g.grade
                                            FROM course c
                                            LEFT JOIN grade g ON c.course_id = g.course_id AND g.stud_id = '$stud_id'
                                            LEFT JOIN institution i ON c.int_id = i.int_id
                                            WHERE c.type = 'Bachelor' 
                                            ORDER BY c.title ASC
                                            LIMIT 0, 25";

                                $sg = $conn->query($query);
                                $no = 0;

                                if ($sg && $sg->num_rows > 0) {
                                    while ($row = $sg->fetch_assoc()) {
                                        $no++;
                                        $grade = isset($row['grade']) ? $row['grade'] : ''; // Get existing grade if available
                                        ?>
                                        <tr>
                                            <th><?php echo $no; ?></th>
                                            <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td><?php echo htmlspecialchars($row['credit_hour']); ?></td>
                                            <td>
                                                <select class="form-select" name="similar[]" <?php if ($submitted == 'submitted') echo 'disabled'; ?> onchange="updateSelectedSubject(this)">
                                                    <option value="N/A">N/A</option>
                                                    <?php
                                                    $idx = $conn->query("SELECT 
                                                                            c.course_code, 
                                                                            c.title, 
                                                                            i.int_name
                                                                        FROM 
                                                                            course c
                                                                        JOIN 
                                                                            institution i ON c.int_id = i.int_id
                                                                        JOIN 
                                                                            student s ON s.int_id = i.int_id
                                                                        WHERE 
                                                                            c.type = 'Diploma' 
                                                                            AND s.stud_id = '$stud_id'
                                                                        ORDER BY 
                                                                            c.title ASC;");
                                                    while ($p = $idx->fetch_assoc()) {
                                                        $courseInfo = htmlspecialchars($p['course_code'] . ' ' . $p['title']);
                                                        ?>
                                                        <option value="<?php echo $courseInfo; ?>" <?php if ($row['course_code'] . ' ' . $row['title'] == $courseInfo) echo 'selected'; ?>><?php echo $courseInfo; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" name="grade[]" <?php if ($submitted == 'submitted') echo 'disabled'; ?>>
                                                    <option value="N/A" <?php if ($grade == '') echo 'selected'; ?>>N/A</option>
                                                    <option value="A" <?php if ($grade == 'A') echo 'selected'; ?>>A</option>
                                                    <option value="A-" <?php if ($grade == 'A-') echo 'selected'; ?>>A-</option>
                                                    <option value="B+" <?php if ($grade == 'B+') echo 'selected'; ?>>B+</option>
                                                    <option value="B" <?php if ($grade == 'B') echo 'selected'; ?>>B</option>
                                                    <option value="B-" <?php if ($grade == 'B-') echo 'selected'; ?>>B-</option>
                                                    <option value="C+" <?php if ($grade == 'C+') echo 'selected'; ?>>C+</option>
                                                    <option value="C" <?php if ($grade == 'C') echo 'selected'; ?>>C</option>
                                                    <option value="C-" <?php if ($grade == 'C-') echo 'selected'; ?>>C-</option>
                                                    <option value="D" <?php if ($grade == 'D') echo 'selected'; ?>>D</option>
                                                </select>
                                                <input type="hidden" name="course_id[]" value="<?php echo htmlspecialchars($row['course_id']); ?>">
                                            </td>
                                            <td>
                                                <select class="form-select" name="similar2[]" <?php if ($submitted == 'submitted') echo 'disabled'; ?> onchange="updateSelectedSubject(this)">
                                                    <option value="N/A">N/A</option>
                                                    <?php
                                                    // Assuming similar logic for the second diploma course options
                                                    $idx2 = $conn->query("SELECT 
                                                                            c.course_code, 
                                                                            c.title, 
                                                                            i.int_name
                                                                        FROM 
                                                                            course c
                                                                        JOIN 
                                                                            institution i ON c.int_id = i.int_id
                                                                        JOIN 
                                                                            student s ON s.int_id = i.int_id
                                                                        WHERE 
                                                                            c.type = 'Diploma' 
                                                                            AND s.stud_id = '$stud_id'
                                                                        ORDER BY 
                                                                            c.title ASC;");
                                                    while ($p2 = $idx2->fetch_assoc()) {
                                                        $courseInfo2 = htmlspecialchars($p2['course_code'] . ' ' . $p2['title']);
                                                        ?>
                                                        <option value="<?php echo $courseInfo2; ?>" <?php if ($row['course_code'] . ' ' . $row['title'] == $courseInfo2) echo 'selected'; ?>><?php echo $courseInfo2; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" name="grade2[]" <?php if ($submitted == 'submitted') echo 'disabled'; ?>>
                                                    <option value="N/A" <?php if ($grade == '') echo 'selected'; ?>>N/A</option>
                                                    <option value="A" <?php if ($grade == 'A') echo 'selected'; ?>>A</option>
                                                    <option value="A-" <?php if ($grade == 'A-') echo 'selected'; ?>>A-</option>
                                                    <option value="B+" <?php if ($grade == 'B+') echo 'selected'; ?>>B+</option>
                                                    <option value="B" <?php if ($grade == 'B') echo 'selected'; ?>>B</option>
                                                    <option value="B-" <?php if ($grade == 'B-') echo 'selected'; ?>>B-</option>
                                                    <option value="C+" <?php if ($grade == 'C+') echo 'selected'; ?>>C+</option>
                                                    <option value="C" <?php if ($grade == 'C') echo 'selected'; ?>>C</option>
                                                    <option value="C-" <?php if ($grade == 'C-') echo 'selected'; ?>>C-</option>
                                                    <option value="D" <?php if ($grade == 'D') echo 'selected'; ?>>D</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>No Record Found!</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                            <?php if (!$success) { ?>
                                <button type="button" id="submitBtn" class="btn btn-primary">Submit Grades</button>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include('footer.php'); ?>

<script>
                            document.getElementById('submitBtn').addEventListener('click', function() {
                                var confirmation = confirm('Are you sure you want to submit the grades? You will not be able to make any changes after submission.');
                                if (confirmation) {
                                    document.getElementById('gradeForm').submit();
                                }
                            });

                            function updateSelectedSubject(select) {
                                var selectedSubject = select.options[select.selectedIndex].text;
                                document.getElementById('selectedSubject').value = selectedSubject;
                            }

                            function searchTable() {
                            var input, filter, table, tr, tdCourseCode, tdCourseTitle, i, txtValueCourseCode, txtValueCourseTitle;
                            input = document.getElementById("searchInput");
                            filter = input.value.toUpperCase();
                            table = document.getElementById("gradesTable");
                            tr = table.getElementsByTagName("tr");

                            for (i = 1; i < tr.length; i++) {
                                // Get the course code and course title cells
                                tdCourseCode = tr[i].getElementsByTagName("td")[0];
                                tdCourseTitle = tr[i].getElementsByTagName("td")[1];

                                if (tdCourseCode || tdCourseTitle) {
                                    txtValueCourseCode = tdCourseCode.textContent || tdCourseCode.innerText;
                                    txtValueCourseTitle = tdCourseTitle.textContent || tdCourseTitle.innerText;

                                    // Check if either the course code or course title matches the filter
                                    if (txtValueCourseCode.toUpperCase().indexOf(filter) > -1 || txtValueCourseTitle.toUpperCase().indexOf(filter) > -1) {
                                        tr[i].style.display = ""; // Show the row if a match is found
                                    } else {
                                        tr[i].style.display = "none"; // Hide the row if no match is found
                                    }
                                }
                            }
                        }
</script>
</body>
</html>
