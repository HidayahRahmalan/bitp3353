<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('header.php');
include('../include/connection.php');

$statusMsg = ""; // Initialize status message

if(isset($_GET['delete'])){
    $transfer_id = $_GET['delete'];

    // Create the delete query
    $delete = "DELETE FROM transfer WHERE transfer_id = ?";
    
    // Prepare and execute the query
    if ($stmt = $conn->prepare($delete)) {
        $stmt->bind_param("i", $transfer_id);
        if ($stmt->execute()) {
            // Redirect after successful deletion
            echo "<script type='text/javascript'>window.location = 'reject.php';</script>";
            exit();
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An error occurred during deletion: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An error occurred while preparing the deletion: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Reject Transfer Course</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
</head>
<body>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Reject Transfer Course</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item">Transfer Course</li>
                    <li class="breadcrumb-item active">Reject Course</li>
                </ol>
            </nav>
        </div><!-- End Page Title --> 
        <section class="section">
            <div class="row">
                <div class="col-lg-12">   
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">PART A: REJECTED COURSES TRANSFER</h5>
                            <p>When a course transfer request is <b style="color: red; ">'Rejected'</b>, it typically signifies that the course does not meet institution-specific requirements, prompting students to <b style="color: red; ">remove</b> the rejected course and seek alternatives with guidance from their academic advisors.
                                <br><br>After remove the rejected courses, please make a new transfer <a href="transfer.php" style="color: blue; font-weight: bold ; text-decoration: underline;">Here</a></p>
                            <?php echo $statusMsg;?>
                            <!-- Bordered Table -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">NO</th>
                                        <th scope="col">CODE</th>
                                        <th scope="col">TITLE</th>
                                        <th scope="col">CREDIT</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">COMMENT</th>
                                        <th scope="col">REMOVE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stud_id = $_SESSION['stud_id'] ?? '';

                                    $query = "SELECT DISTINCT t.transfer_id, c.course_code, c.title, g.similar, c.credit_hour, g.grade, t.aa_status, t.aa_comment, t.tda_status, t.tda_comment, t.dean_status, t.dean_comment
                                              FROM transfer t
                                              JOIN grade g ON t.grade_id = g.grade_id
                                              JOIN course c ON g.course_id = c.course_id
                                              JOIN student s ON g.stud_id = s.stud_id
                                              WHERE (t.aa_status = 'Rejected'
                                              OR t.tda_status = 'Rejected'
                                              OR t.dean_status = 'Rejected')
                                              AND s.stud_id = ?";

                                    if ($stmt = $conn->prepare($query)) {
                                        $stmt->bind_param("s", $stud_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        $no = 0;

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $no++;
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $no; ?></th>
                                        <td><?php echo $row['course_code']; ?></td>
                                        <td><?php echo $row['title']; ?></td>
                                        <td><?php echo $row['credit_hour']; ?></td>
                                        <td style="color: <?php echo ($row['aa_status'] == 'Accepted' && $row['tda_status'] == 'Accepted' && $row['dean_status'] == 'Accepted') ? 'green' : 'red'; ?>">
                                            <?php 
                                            echo "AA: " . $row['aa_status'] . "<br>";
                                            echo "TDA: " . $row['tda_status'] . "<br>";
                                            echo "Dean: " . $row['dean_status']; 
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            echo "AA: " . $row['aa_comment'] . "<br>";
                                            echo "TDA: " . $row['tda_comment'] . "<br>";
                                            echo "Dean: " . $row['dean_comment']; 
                                            ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-danger" href='reject.php?delete=<?php echo $row['transfer_id']; ?>' onclick="return confirm('Are you sure to delete <?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>?');">
                                                <i class="bx bx-x-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='8' class='text-center'>No Record Found!</td></tr>";
                                        }
                                        $stmt->close();
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>An error occurred while preparing the selection: " . $conn->error . "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <!-- End Bordered Table -->
                        </div>
                    </div>
                </div>
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
            <h5 class="card-title">PART B: REJECTED COURSES BY ACADEMIC ADVISOR</h5>
            <p>List of courses rejected by the academic advisor for not meeting the required standards.</p>
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
    <?php include('footer.php'); ?>
</body>
</html>
