<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('header.php');
//session_start();
include('../include/connection.php');

$success = false;
$errors = [];
$submitted = isset($_POST['submitted']) ? $_POST['submitted'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $submitted != 'submitted') {
    $stud_id = $_POST['stud_id'];
    $similarGrades = isset($_POST['similar']) ? $_POST['similar'] : [];
    $dipids = isset($_POST['course_id']) ? $_POST['course_id'] : [];
    $grades = isset($_POST['grade']) ? $_POST['grade'] : [];

    // Ensure that $dipids, $similarGrades, and $grades are arrays
    if (!is_array($dipids) || !is_array($similarGrades) || !is_array($grades)) {
        $errors[] = "Invalid data format for course_id, similar, or grade.";
    } elseif (count($dipids) == count($similarGrades) && count($dipids) == count($grades)) {
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

                    $query = "INSERT INTO grade (stud_id, course_id, similar, grade) VALUES ('$stud_id', '$dipid', '$similarGrade', '$grade')";
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
        $errors[] = "Mismatch in the number of courses, similar, and grades provided.";
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
        .form-select-wrapper {
            display: flex;
            align-items: center;
        }
        .remove-btn {
            margin-left: 10px;
            cursor: pointer;
            color: red;
        }
        .add-more-btn {
            margin-top: 10px;
            cursor: pointer;
            color: green;
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

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add Grade</h5>

                    <p>Add Diploma Course grade that has been taken by students during the diploma.
                    <code>Please Take Note:</code>
                    ALL SUBJECT <code>MUST BE SELECTED</code> if the Diploma course in N/A during Your diploma Please select <code>N/A</code>
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
                    <form id="gradeForm" method="POST" action="">
                        <table class="table table-bordered">
                            <input type="hidden" class="form-control" name="stud_id" value="<?php echo htmlspecialchars($_SESSION['stud_id']); ?>">
                            <input type="hidden" name="submitted" value="<?php echo htmlspecialchars($submitted); ?>">
                            <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Course Code</th>
                                <th scope="col">Course Name</th>
                                <th scope="col">Credit Hour</th>
                                <th scope="col">Diploma Course</th>
                                <th scope="col">Grade</th>
                            </tr>
                            </thead>
                            <tbody id="courseTableBody">

                            <?php
                            $stud_id = $_SESSION['stud_id'];

                            $query = "SELECT c.course_code, c.title, c.credit_hour, c.course_id, i.int_name, g.grade
                                        FROM course c
                                        LEFT JOIN grade g ON c.course_id = g.course_id AND g.stud_id = '$stud_id'
                                        LEFT JOIN institution i ON c.int_id = i.int_id
                                        WHERE c.type = 'Bachelor' 
                                        ORDER BY c.course_id ASC
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
                                            <div class="form-select-wrapper">
                                                <select class="form-select" name="similar[]" onchange="updateSelectedSubject(this)">
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
                                                        <option value="<?php echo $courseInfo; ?>" <?php if ($row['course_code'] . ' ' . $row['title'] == $courseInfo ) echo 'selected'; ?>><?php echo $courseInfo ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <input type="hidden" name="selected_subject[]" id="selectedSubject" value="<?php echo isset($_POST['similar']) ? htmlspecialchars($_POST['similar'][0]) : ''; ?>">
                                                <button type="button" class="btn btn-default remove-btn" onclick="removeSelect(this)"><i class="ri-close-line"></i></button>
                                            </div>
                                            <button type="button" class="btn btn-default add-more" onclick="addMoreSelect(this)"><i class="ri-add-fill"></i></button>
                                        </td>
                                        <td>
                                            <div class="form-select-wrapper">
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
                                                <button type="button" class="btn btn-default remove-btn" onclick="removeGradeSelect(this)"><i class="ri-close-line"></i></button>
                                            </div>
                                            <button type="button" class="btn btn-default add-more" onclick="addMoreGradeSelect(this)"><i class="ri-add-fill"></i></button>
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
                        <div id="additionalSelects"></div>
                        <?php if (!$success) { ?>
                            <button type="button" id="submitBtn" class="btn btn-primary">Submit Grades</button>
                        <?php } ?>
                    </form>
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
        var selectedValue = select.value;
        var selects = document.querySelectorAll('select[name="similar[]"]');

        selects.forEach(function(sel) {
            if (sel !== select) {
                var options = sel.querySelectorAll('option');
                options.forEach(function(option) {
                    if (option.value === selectedValue) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            }
        });
    }

    function removeSelect(element) {
        var select = element.parentNode.querySelector('select[name="similar[]"]');
        var selectedValue = select.value;
        var selects = document.querySelectorAll('select[name="similar[]"]');

        selects.forEach(function(sel) {
            if (sel !== select) {
                var options = sel.querySelectorAll('option');
                options.forEach(function(option) {
                    if (option.value === selectedValue) {
                        option.disabled = false;
                    }
                });
            }
        });

        element.parentNode.parentNode.removeChild(element.parentNode);
    }

    function removeGradeSelect(element) {
        element.parentNode.parentNode.removeChild(element.parentNode);
    }

    function addMoreSelect(button) {
        var newSelectWrapper = document.createElement('div');
        newSelectWrapper.classList.add('form-select-wrapper');

        var newSelect = document.createElement('select');
        newSelect.classList.add('form-select');
        newSelect.name = 'similar[]';
        newSelect.innerHTML = `
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
                echo '<option value="' . $courseInfo . '">' . $courseInfo . '</option>';
            }
            ?>
        `;

        var removeBtn = document.createElement('span');
        removeBtn.classList.add('remove-btn');
        removeBtn.innerHTML = '&#10005;';
        removeBtn.setAttribute('onclick', 'removeSelect(this)');

        newSelectWrapper.appendChild(newSelect);
        newSelectWrapper.appendChild(removeBtn);

        button.parentNode.insertBefore(newSelectWrapper, button);
    }

    function addMoreGradeSelect(button) {
        var newSelectWrapper = document.createElement('div');
        newSelectWrapper.classList.add('form-select-wrapper');

        var newSelect = document.createElement('select');
        newSelect.classList.add('form-select');
        newSelect.name = 'grade[]';
        newSelect.innerHTML = `
            <option value="N/A">N/A</option>
            <option value="A">A</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B">B</option>
            <option value="B-">B-</option>
            <option value="C+">C+</option>
            <option value="C">C</option>
            <option value="C-">C-</option>
            <option value="D">D</option>
        `;

        var removeBtn = document.createElement('span');
        removeBtn.classList.add('remove-btn');
        removeBtn.innerHTML = '&#10005;';
        removeBtn.setAttribute('onclick', 'removeGradeSelect(this)');

        newSelectWrapper.appendChild(newSelect);
        newSelectWrapper.appendChild(removeBtn);

        button.parentNode.insertBefore(newSelectWrapper, button);
    }
</script>
</body>
</html>
