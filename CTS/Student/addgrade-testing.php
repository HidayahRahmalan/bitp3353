<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('header.php');
//session_start();
include('../include/connection.php');

$success = false;
$errors = [];
$submitted = isset($_POST['submitted']) ? $_POST['submitted'] : '';

// Retrieve POST values
$selectedSimilar = isset($_POST['similar']) ? $_POST['similar'] : [];
$selectedGrade = isset($_POST['grade']) ? $_POST['grade'] : [];
$selectedSimilar2 = isset($_POST['similar2']) ? $_POST['similar2'] : [];
$selectedGrade2 = isset($_POST['grade2']) ? $_POST['grade2'] : [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && $submitted != 'submitted') {
    $stud_id = $_POST['stud_id'];
    $similarGrades = isset($_POST['similar']) ? $_POST['similar'] : [];
    $similarGrades2 = isset($_POST['similar2']) ? $_POST['similar2'] : [];
    $dipids = isset($_POST['course_id']) ? $_POST['course_id'] : [];
    $grades = isset($_POST['grade']) ? $_POST['grade'] : [];
    $grades2 = isset($_POST['grade2']) ? $_POST['grade2'] : [];

    if (!is_array($dipids) || !is_array($similarGrades) || !is_array($grades) || !is_array($similarGrades2) || !is_array($grades2)) {
        $errors[] = "Invalid data format for course_id, similar, similar2, grade, or grade2.";
    } elseif (count($dipids) == count($similarGrades) && count($dipids) == count($grades) && count($dipids) == count($similarGrades2) && count($dipids) == count($grades2)) {
        $conn->begin_transaction();
        try {
            $check_query = "SELECT * FROM grade WHERE stud_id = '$stud_id'";
            $check_result = $conn->query($check_query);
            if ($check_result && $check_result->num_rows > 0) {
                $errors[] = "Grades for this student already exist. You cannot submit again.";
            } else {
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
                $submitted = 'submitted';
            }
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = $e->getMessage();
        }
    } else {
        $errors[] = "Mismatch in the number of courses, similar, similar2, grades, and grades2 provided.";
    }
}

// Pagination
$limit = 10;  // Number of entries to show in a page.
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query to get courses and grades
$stud_id = $_SESSION['stud_id'];
$query = "SELECT c.course_code, c.title, c.credit_hour, c.course_id, i.int_name, g.grade
          FROM course c
          LEFT JOIN grade g ON c.course_id = g.course_id AND g.stud_id = '$stud_id'
          LEFT JOIN institution i ON c.int_id = i.int_id
          WHERE c.type = 'Bachelor' AND (c.course_code LIKE '%$search%' OR c.title LIKE '%$search%')
          ORDER BY c.course_id ASC
          LIMIT $start, $limit";

$sg = $conn->query($query);
$no = $start;

$total_query = "SELECT COUNT(*) AS total
                FROM course c
                LEFT JOIN grade g ON c.course_id = g.course_id AND g.stud_id = '$stud_id'
                LEFT JOIN institution i ON c.int_id = i.int_id
                WHERE c.type = 'Bachelor' AND (c.course_code LIKE '%$search%' OR c.title LIKE '%$search%')";

$total_result = $conn->query($total_query);
$total_records = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);
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
            <div class="col-lg-12">
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
                            <input type="hidden" class="form-control" name="stud_id" value="<?php echo htmlspecialchars($_SESSION['stud_id']); ?>">
                            <input type="hidden" name="submitted" value="<?php echo htmlspecialchars($submitted); ?>">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search" id="search" value="<?php echo htmlspecialchars($search); ?>">
                                <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Course Code</th>
                                        <th scope="col">Course Name</th>
                                        <th scope="col">Credit Hour</th>
                                        <th scope="col">Diploma Course 1</th>
                                        <th scope="col">Grade</th>
                                        <th scope="col">Diploma Course 2</th>
                                        <th scope="col">Grade </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($sg && $sg->num_rows > 0) {
                                    while ($row = $sg->fetch_assoc()) {
                                        $no++;
                                        $grade = isset($row['grade']) ? $row['grade'] : '';
                                        $course_id = $row['course_id'];
                                        ?>
                                        <tr>
                                            <th><?php echo $no; ?></th>
                                            <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td><?php echo htmlspecialchars($row['credit_hour']); ?></td>
                                            <td>
                                                <select class="form-select" name="similar[]">
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
                                                                            i.int_name ASC;");
                                                    while ($p = $idx->fetch_assoc()) {
                                                        $courseInfo = htmlspecialchars($p['course_code'] . ' ' . $p['title']);
                                                        $selected = in_array($courseInfo, $selectedSimilar) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?php echo $courseInfo; ?>" <?php echo $selected; ?>><?php echo $courseInfo; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" name="grade[]">
                                                    <option value="N/A" <?php echo $grade == 'N/A' ? 'selected' : ''; ?>>N/A</option>
                                                    <option value="A" <?php echo $grade == 'A' ? 'selected' : ''; ?>>A</option>
                                                    <option value="A-" <?php echo $grade == 'A-' ? 'selected' : ''; ?>>A-</option>
                                                    <option value="B+" <?php echo $grade == 'B+' ? 'selected' : ''; ?>>B+</option>
                                                    <option value="B" <?php echo $grade == 'B' ? 'selected' : ''; ?>>B</option>
                                                    <option value="B-" <?php echo $grade == 'B-' ? 'selected' : ''; ?>>B-</option>
                                                    <option value="C+" <?php echo $grade == 'C+' ? 'selected' : ''; ?>>C+</option>
                                                    <option value="C" <?php echo $grade == 'C' ? 'selected' : ''; ?>>C</option>
                                                    <option value="C-" <?php echo $grade == 'C-' ? 'selected' : ''; ?>>C-</option>
                                                    <option value="D" <?php echo $grade == 'D' ? 'selected' : ''; ?>>D</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" name="similar2[]">
                                                    <option value="N/A">N/A</option>
                                                    <?php
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
                                                                            i.int_name ASC;");
                                                    while ($p2 = $idx2->fetch_assoc()) {
                                                        $courseInfo2 = htmlspecialchars($p2['course_code'] . ' ' . $p2['title']);
                                                        $selected2 = in_array($courseInfo2, $selectedSimilar2) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?php echo $courseInfo2; ?>" <?php echo $selected2; ?>><?php echo $courseInfo2; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" name="grade2[]">
                                                    <option value="N/A" <?php echo $grade == 'N/A' ? 'selected' : ''; ?>>N/A</option>
                                                    <option value="A" <?php echo $grade == 'A' ? 'selected' : ''; ?>>A</option>
                                                    <option value="A-" <?php echo $grade == 'A-' ? 'selected' : ''; ?>>A-</option>
                                                    <option value="B+" <?php echo $grade == 'B+' ? 'selected' : ''; ?>>B+</option>
                                                    <option value="B" <?php echo $grade == 'B' ? 'selected' : ''; ?>>B</option>
                                                    <option value="B-" <?php echo $grade == 'B-' ? 'selected' : ''; ?>>B-</option>
                                                    <option value="C+" <?php echo $grade == 'C+' ? 'selected' : ''; ?>>C+</option>
                                                    <option value="C" <?php echo $grade == 'C' ? 'selected' : ''; ?>>C</option>
                                                    <option value="C-" <?php echo $grade == 'C-' ? 'selected' : ''; ?>>C-</option>
                                                    <option value="D" <?php echo $grade == 'D' ? 'selected' : ''; ?>>D</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="8" class="text-center">No results found.</td></tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary">Submit Grades</button>
                        </form>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($search); ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($search); ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    document.getElementById('searchButton').addEventListener('click', function() {
        var search = document.getElementById('search').value;
        window.location.href = '?search=' + encodeURIComponent(search) + '&page=1';
    });
</script>

</body>
</html>
