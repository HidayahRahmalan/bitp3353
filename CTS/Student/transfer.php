<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('header.php');
include('../include/connection.php');
$success = false;
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['grade_ids']) && is_array($_POST['grade_ids'])) {
        $grade_ids = $_POST['grade_ids'];
        $transfer_date = date('Y-m-d H:i:s'); // Current date and time formatted as day-month-year hours:minutes:seconds
        $aa_status = 'Pending';
        $aa_comment = '';
        $tda_status = 'Pending';
        $tda_comment = '';
        $dean_status = 'Pending';
        $dean_comment = '';

        // Check if total credit hours exceed the limit
        $totalCreditHours = 0;
        foreach ($grade_ids as $grade_id) {
            $query = "SELECT 
                          s.stud_id,
                          SUM(c.credit_hour) AS totalCredit 
                      FROM 
                          transfer t 
                      JOIN 
                          grade g ON t.grade_id = g.grade_id
                      JOIN 
                          course c ON g.course_id = c.course_id
                      JOIN 
                          student s ON g.stud_id = s.stud_id
                      WHERE 
                          s.stud_id = ?
                      GROUP BY s.stud_id";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $grade_id);
            $stmt->execute();
            $stmt->bind_result($stud_id, $totalCreditHours);
            $stmt->fetch();

        }
        $stmt->close();

        if ($totalCreditHours > 36) {
            $errors[] = 'You cannot select subjects exceeding a total of 36 credit hours.';
        } else {
            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO transfer (grade_id, transfer_date, aa_status, aa_comment, tda_status, tda_comment, dean_status, dean_comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }

            // Bind the parameters and execute the statement for each selected grade
            foreach ($grade_ids as $grade_id) {
              // Check if the grade ID already exists
              $check_query = "SELECT COUNT(*), c.title FROM transfer t
                              JOIN grade g ON t.grade_id = g.grade_id 
                              JOIN course c ON g.course_id = c.course_id 
                              WHERE t.grade_id = ?";
              $stmt_check = $conn->prepare($check_query);
              $stmt_check->bind_param('i', $grade_id);
              $stmt_check->execute();
              $stmt_check->bind_result($count, $title); // Added $course_title variable
              $stmt_check->fetch();
              $stmt_check->close();

              if ($count > 0) {
                  $errors[] = 'Course ' . htmlspecialchars($title) . ' is already added for transfer.';
              } else {
                  $stmt->bind_param('isssssss', $grade_id, $transfer_date, $aa_status, $aa_comment, $tda_status, $tda_comment, $dean_status, $dean_comment);
                  if (!$stmt->execute()) {
                      $errors[] = 'Failed to insert course ' . htmlspecialchars($title) . ': ' . htmlspecialchars($stmt->error);
                  }
              }
            }


            // Check if there were any errors
            if (empty($errors)) {
                $success = true;
                // Disable checkboxes after successful submission
                echo '<script>';
                echo 'document.addEventListener("DOMContentLoaded", function() {';
                echo 'const checkboxes = document.querySelectorAll("input[type=\'checkbox\']");';
                echo 'checkboxes.forEach(function(checkbox) {';
                echo 'checkbox.disabled = true;';
                echo '});';
                echo '});';
                echo '</script>';
            } else {
                $success = false;
            }

            $stmt->close();
        }
    } else {
        $errors[] = 'No grades selected for transfer.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let totalCreditHours = 0;
        const limit = 36;
        const checkboxes = document.querySelectorAll('input[type="checkbox"].select-checkbox');
        const creditHours = Array.from(document.querySelectorAll('td.credit-hour')).map(td => parseInt(td.textContent));
        const submitButton = document.querySelector('button[type="submit"]');
        const totalCreditDisplay = document.getElementById('total-credit');

        // Function to update the total credit hours display
        function updateTotalCreditHours() {
            totalCreditDisplay.textContent = totalCreditHours;
        }

        // Select all checkboxes up to the limit
        document.getElementById('select-all').addEventListener('change', function() {
            if (this.checked) {
                totalCreditHours = 0; // Reset total credit hours
                checkboxes.forEach((checkbox, index) => {
                    const creditHour = creditHours[index];
                    if (totalCreditHours + creditHour <= limit) {
                        checkbox.checked = true;
                        totalCreditHours += creditHour;
                    } else {
                        checkbox.checked = false;
                    }
                });
            } else {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                totalCreditHours = 0;
            }
            updateTotalCreditHours();
        });

        checkboxes.forEach((checkbox, index) => {
            checkbox.addEventListener('change', function() {
                const creditHour = creditHours[index];
                if (this.checked) {
                    if (totalCreditHours + creditHour > limit) {
                        alert('You can select subjects up to a maximum of 36 credit hours.');
                        this.checked = false;
                    } else {
                        totalCreditHours += creditHour;
                    }
                } else {
                    totalCreditHours -= creditHour;
                }
                updateTotalCreditHours();
            });
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            if (!confirm('Are you sure you want to submit? You cannot make changes after submitting.')) {
                event.preventDefault(); // Cancel the form submission
            } else {
                submitButton.disabled = true;
                submitButton.style.visibility = 'hidden';
            }
        });
    });
</script>

</head>

<body>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Transfer Credit</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item">Transfer Credit</li>
                    <li class="breadcrumb-item active">Course Credit</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Course to Transfer</h5>
                            <p>Subject to transfer and Subject to apply at UTeM.</p>
                            <p>
                            <b>NOTE:</b> The courses listed here are exclusively for diploma courses with grades of A, A-, B+, B, B-, C+, and C. The maximum number of transfer credits allowed is limited to 36 credit hours.
                            </p>
                            <p style="color: darkblue;">Total Credit Hours Selected: <b><span id="total-credit" >0</span></b></p>
                            <?php
                            if ($success) {
                                echo '<div class="alert alert-success">Transfer course added successfully!</div>';
                            }

                            if (!empty($errors)) {
                                echo '<div class="alert alert-danger">';
                                foreach ($errors as $error) {
                                    echo '<p>' . htmlspecialchars($error) . '</p>';
                                }
                                echo '</div>';
                            }
                            ?>
                            <!-- Bordered Table -->
                            <form method="POST" action="">
                                <table class="table table-bordered border-success">
                                    <input type="hidden" class="form-control" name="stud_id"
                                        value="<?php echo $_SESSION['stud_id']; ?>">

                                    <thead>
                                        <tr>
                                            <th scope="col">No.</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">Course Title</th>
                                            <th scope="col">Credit Hour</th>
                                            <th scope="col">Check All<input type="checkbox" id="select-all"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stud_id = $_SESSION['stud_id'];

                                        $query = "SELECT DISTINCT g.grade_id, d.course_code, d.title, d.credit_hour, g.grade, g.grade2
                                                 FROM grade g
                                                JOIN course d ON g.course_id = d.course_id
                                                WHERE g.stud_id = '$stud_id'
                                                AND (
                                                    (g.grade IN ('N/A','A', 'A-', 'B+', 'B', 'B-', 'C') AND g.grade2 IN ('A', 'A-', 'B+', 'B', 'B-', 'C'))
                                                    OR
                                                    (g.grade2 IN ('N/A','A', 'A-', 'B+', 'B', 'B-', 'C') AND g.grade IN ('A', 'A-', 'B+', 'B', 'B-', 'C')))
                                                  ";


                                        $sg = $conn->query($query);
                                        $no = 0;

                                        if ($sg && $sg->num_rows > 0) {
                                            while ($row = $sg->fetch_assoc()) {
                                                $no++;
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $no ?></th>
                                            <td><?php echo $row['course_code'] ?></td>
                                            <td><?php echo $row['title'] ?></td>
                                            <td class="credit-hour"><?php echo $row['credit_hour'] ?></td>
                                            <td><input type="checkbox" class="select-checkbox" name="grade_ids[]"
                                                    value="<?php echo $row['grade_id']; ?>"></td>
                                        </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>No Record Found!</td></tr>";
                                        } ?>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary">Submit Transfer Credit</button>
                            </form>
                            <!-- End Bordered Table -->
                            <!-- End Primary Color Bordered Table -->

                        </div>
                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Cannot Transfer Course</h5>
                            <p>A course cannot be transferred if the grade received is C-, D, or if the course has not yet been taken during the diploma program.</p>
                            <!-- Primary Color Bordered Table -->
                            <table class="table table-bordered border-danger">
                                <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Course Title</th>
                                        <th scope="col">Credit Hour</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stud_id = $_SESSION['stud_id'];

                                    $query = "SELECT DISTINCT g.grade_id, d.course_code, d.title, d.credit_hour, g.grade, g.grade2
                                              FROM grade g
                                              JOIN course d ON g.course_id = d.course_id
                                              WHERE g.stud_id = '$stud_id'
                                              AND (
                                                    -- Condition for subjects where both grades are 'N/A'
                                                    (g.grade = 'N/A' AND g.grade2 = 'N/A')
                                                    OR
                                                    -- Condition for subjects where either grade is 'C-' or 'D'
                                                    ((g.grade IN ('C-', 'D') OR g.grade2 IN ('C-', 'D')))
                                                )
                                            ";

                                    $sg = $conn->query($query);
                                    $no = 0;

                                    if ($sg && $sg->num_rows > 0) {
                                        while ($row = $sg->fetch_assoc()) {
                                            $no++;
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $no ?></th>
                                        <td><?php echo $row['course_code'] ?></td>
                                        <td><?php echo $row['title'] ?></td>
                                        <td><?php echo $row['credit_hour'] ?></td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No Record Found!</td></tr>";
                                    } ?>

                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->
    
    <?php include('footer.php'); ?>

</body>

</html>
