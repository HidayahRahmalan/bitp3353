<?php
include('header.php');
//session_start();
include('../include/connection.php');

$transfer_id = $_REQUEST['statusid'];

// Retrieve data SQL
$query = "SELECT s.stud_id, s.name, s.icno, s.faculty, p.prog_name, p.prog_code, s.session, s.email, s.phone, s.username
          FROM student s
          JOIN programme p ON s.prog_id = p.prog_id
          WHERE s.stud_id = '$transfer_id'";
$rs = $conn->query($query);

if ($rs && $rs->num_rows > 0) {
    $rows = $rs->fetch_assoc();
    $stud_id = $rows['stud_id'];
    $name = $rows['name'];
    $icno = $rows['icno'];
    $faculty = $rows['faculty'];
    $prog_name = $rows['prog_name'];
    $prog_code = $rows['prog_code'];
    $session = $rows['session'];
    $email = $rows['email'];
    $phone = $rows['phone'];
    $matric_number = $rows['username'];

    // Store stud_id in session
    $_SESSION['stud_id'] = $stud_id;
} else {
    // Handle the case where no rows are returned
    $stud_id = '';
    $name = '';
    $icno = '';
    $faculty = '';
    $prog_name = '';
    $prog_code = '';
    $session = '';
    $email = '';
    $phone = '';
    $matric_number = '';
    echo "<p>No student record found.</p>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Handle form submission to update course status
  $course_code = $_POST['course_code'];
  $comment = $_POST['comment']; // Changed from aa_comment to comment
  $action = $_POST['action']; // 'accept' or 'reject'

  if ($action == 'accept') {
      $aa_status = 'Accepted';
      $update_query = "UPDATE transfer t
                       JOIN grade g ON t.grade_id = g.grade_id
                       JOIN course c ON g.course_id = c.course_id
                       SET t.aa_status = '$aa_status', t.aa_comment = '$comment'
                       WHERE c.course_code = '$course_code' AND g.stud_id = '$stud_id'";
      $conn->query($update_query);
  } elseif ($action == 'reject') {
      // Fetch course details before deletion
      $fetch_course_query = "SELECT c.course_code, c.title, c.credit_hour, g.grade, g.similar, g.similar2, g.grade2 
                             FROM course c
                             JOIN grade g ON c.course_id = g.course_id
                             WHERE c.course_code = '$course_code' AND g.stud_id = '$stud_id'";
      $course_details = $conn->query($fetch_course_query)->fetch_assoc();

      // Insert into reject table
      $insert_reject_query = "INSERT INTO reject_transfer (stud_id, course_code, title, credit_hour, grade, similar, similar2, grade2, aa_comment)
                              VALUES ('$stud_id', '{$course_details['course_code']}', '{$course_details['title']}', '{$course_details['credit_hour']}',
                                      '{$course_details['grade']}', '{$course_details['similar']}', '{$course_details['similar2']}',
                                      '{$course_details['grade2']}', '$comment')";
      $conn->query($insert_reject_query);

      // Delete or update the rejected course in the original table
      $delete_query = "DELETE FROM transfer 
                       WHERE grade_id IN (
                           SELECT g.grade_id 
                           FROM grade g 
                           JOIN course c ON g.course_id = c.course_id 
                           WHERE c.course_code = '$course_code' AND g.stud_id = '$stud_id'
                       )";
      $conn->query($delete_query);
  }
}

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
      <h1>Student's Course Credit Transfer</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">View list transfer</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
          <div class="card">
            <div class="card-body">
              <h1 class="card-title text-center">CREDIT EXEMPTION DETAILS</h1>
              <h5 class="card-title">PART A: STUDENT'S INFO</h5>
                 <!-- Multi Columns Form -->
              <form class="row g-3">
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Name</label>
                  <input type="text" class="form-control" id="inputName5" value="<?php echo $name?>" readonly>
                </div>

                <div class="col-md-4">
                  <label for="inputEmail5" class="form-label">IC No</label>
                  <input type="text" class="form-control" id="inputEmail5" value="<?php echo $icno?>" readonly>
                </div>

                <div class="col-md-4">
                  <label for="inputPassword5" class="form-label">Year / Programme</label>
                  <?php
                  //calculate total credit
                  $stud_id = $_SESSION['stud_id'] ?? ''; // Use null coalescing operator to handle undefined index
                  $total = "SELECT 
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
                                aa_status = 'Accepted'
                            AND    
                                s.stud_id = '$stud_id'
                            GROUP BY s.stud_id";
                  $rs2 = $conn->query($total);

                  if ($rs2 && $rs2->num_rows > 0) {
                      $rows2 = $rs2->fetch_assoc();
                      $totalcredit = $rows2['totalCredit'];
                      if ($totalcredit <= 14) {
                          $year = 1;
                          $semester = 1;
                      } elseif ($totalcredit >= 15 && $totalcredit <= 27) {
                          $year = 1;
                          $semester = 2;
                      } elseif ($totalcredit >= 28 && $totalcredit <= 36) {
                          $year = 2;
                          $semester = 1;
                      } else {
                          $year = 2;
                          $semester = 2;
                      }
                  } else {
                      $totalcredit = 0;
                      $year = 1; // Default to year 1 if no records found
                      $semester = 1; // Default to semester 1 if no records found
                  }
                  ?>
                  <input type="text" class="form-control" id="inputPassword5" value="<?php echo "$year - $prog_code" ?>" readonly>
                </div>

                <div class="col-md_4">
                  <label for="inputPassword5" class="form-label">Faculty</label>
                  <input type="text" class="form-control" id="inputPassword5" value="<?php  echo $faculty ?>" readonly>
                </div>

                <div class="col-md-6">
                  <label for="inputEmail5" class="form-label">Matriculation No</label>
                  <input type="text" class="form-control" id="inputEmail5" value="<?php echo $matric_number?>" readonly>
                </div>

                <div class ="col-md-6">
                  <label for="inputPassword5" class="form-label">Session / Semester</label>
                  <input type="text" class="form-control" id="inputPassword5" value="<?php echo $session .' - '. $semester ?>" readonly>
                </div>

                <div class="col-md-6">
                  <label for="inputPassword5" class="form-label">Total Credit Exemption obtained before</label>
                  <input type="text" class="form-control" id="inputPassword5" value="0" readonly>
                </div>

                <div class="col-md-6">
                  <label for="inputPassword5" class="form-label">Total Credit Exemption applied this semester</label>
                  <input type="text" class="form-control" id="inputPassword5" value="<?php echo $totalcredit ?>" readonly>
                  </div>
              </form><!-- End Multi Columns Form -->

                <h5 class="card-title">PART B: COURSES' INFO</h5>
                <p style="text-align: center">COURSE GIVEN CREDIT EXEMPTION IN THIS SEMESTER</p>
                <!-- Bordered Table -->
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">NO</th>
                      <th scope="col">CODE</th>
                      <th scope="col">TITLE</th>
                      <th scope="col">SIMILAR COURSE</th>
                      <th scope="col">CREDIT</th>
                      <th scope="col">GRADE</th>
                      <th scope="col">STATUS</th>
                      <th scope="col">COMMENT</th>
                      <th scope="col">ACTION</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $stud_id = $_SESSION['stud_id'] ?? ''; // Use null coalescing operator to handle undefined index

                    $query = "SELECT DISTINCT c.course_code, c.title, g.similar,g.grade, c.credit_hour, g.similar2,g.grade2 ,t.aa_status, t.aa_comment
                              FROM transfer t
                              JOIN grade g ON t.grade_id = g.grade_id
                              JOIN course c ON g.course_id = c.course_id
                              JOIN student s ON g.stud_id = s.stud_id
                              WHERE s.stud_id = '$stud_id'
                              "; // Add WHERE clause to filter by student ID

                    $sg = $conn->query($query);
                    $no = 0;

                    if ($sg && $sg->num_rows > 0) {
                        while ($row = $sg->fetch_assoc()) {
                            $no++;
                            $isSimilar2Visible = $row['similar2'] !== 'N/A';
                            $isGrade2Visible = $row['grade2'] !== 'N/A';

                    ?>
                    <tr>
                      <th scope="row"><?php echo $no?></th>
                      <td><?php echo $row['course_code'] ?></td>
                      <td><?php echo $row['title'] ?></td>
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
                      <td><?php echo $row['credit_hour'] ?></td>
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
                      <td><?php echo $row['aa_status'] ?></td>
                      <td><?php echo $row['aa_comment'] ?></td>
                      <td>
                        <form method="post" onsubmit="return confirmAction(this);">
                          <input type="hidden" name="course_code" value="<?php echo $row['course_code'] ?>">
                          <textarea name="comment" rows="2" cols="20" placeholder="Add comment"></textarea> <br>
                          <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                          <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                        </form>
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
                <!-- End Bordered Table -->                 
              </div>
            </div><!-- End Default Card -->
          </div>
        </div><!-- End Left side columns -->

      </div>
    </section>

    

    <!-- Part C: Rejected Subjects -->
    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
          <div class="card">
            <div class="card-body">
            <h5 class="card-title">PART C: REJECTED COURSES</h5>
            <p style="text-align: center">SUBJECTS THAT WERE REJECTED THIS SEMESTER</p>
            <!-- Bordered Table -->
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">NO</th>
                      <th scope="col">CODE</th>
                      <th scope="col">TITLE</th>
                      <th scope="col">CREDIT</th>
                      <th scope="col">GRADE</th>
                      <th scope="col">SIMILAR COURSES</th>
                      <th scope="col">COMMENT</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $stud_id = $_SESSION['stud_id'] ?? ''; // Ensure session ID is set

                    $query = "SELECT course_code, title, credit_hour, grade, similar, similar2, grade2, aa_comment 
                              FROM reject_transfer 
                              WHERE stud_id = '$stud_id'";

                    $sg = $conn->query($query);
                    $no = 0;

                    if ($sg && $sg->num_rows > 0) {
                        while ($row = $sg->fetch_assoc()) {
                            $no++;
                            $isSimilar2Visible = $row['similar2'] !== 'N/A';
                    ?>
                    <tr>
                      <th scope="row"><?php echo $no ?></th>
                      <td><?php echo $row['course_code'] ?></td>
                      <td><?php echo $row['title'] ?></td>
                      <td><?php echo $row['credit_hour'] ?></td>
                      <td><?php echo $row['grade'] ?></td>
                      <td>
                        <?php
                        echo $row['similar'];
                        echo '<br>';
                        if ($isSimilar2Visible) {
                            echo $row['similar2'];
                        }
                        ?>
                      </td>
                      <td><?php echo $row['aa_comment'] ?></td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No Rejected Subjects Found!</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
                <!-- End Bordered Table -->                
              </div>
            </div><!-- End Default Card -->
          </div>
        </div><!-- End Left side columns -->

      </div>
    </section> 
  
  </main><!-- End #main -->

  <script>
function confirmAction(form) {
    const action = form.querySelector('button[name="action"][type="submit"]:focus').value;
    const message = action === 'accept' ? 'Are you sure you want to accept this request?' : 'Are you sure you want to reject this request?';
    return confirm(message);
}
</script>

  <?php
  include('footer.php');
  ?>
</body>
</html>

