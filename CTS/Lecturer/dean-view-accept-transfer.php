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


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>

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
      <div class="text-center mb-3">
                    <!-- <button class="btn btn-secondary" onclick="printPage()">Print</button> -->
                    <button class="btn" onclick="saveToPDF()">Save as PDF <i class="ri-file-download-line"></i></button>
                </div>

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
          <div class="card">
            <div class="card-body">
              <h1 class="card-title text-center">CREDIT EXEMPTION DETAILS</h1>
                        
                <a href="dean-student-list-accept.php"><i class="ri-arrow-left-line"></i>Back</a>
                        
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

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $stud_id = $_SESSION['stud_id'] ?? ''; // Use null coalescing operator to handle undefined index

                    $query = "SELECT DISTINCT c.course_code, c.title, g.similar, c.credit_hour,g.grade,g.similar2, g.grade2, t.aa_status, t.aa_comment, t.tda_status, t.tda_comment, t.dean_status, t.dean_comment
                              FROM transfer t
                              JOIN grade g ON t.grade_id = g.grade_id
                              JOIN course c ON g.course_id = c.course_id
                              JOIN student s ON g.stud_id = s.stud_id
                              WHERE s.stud_id = '$stud_id'"; // Add WHERE clause to filter by student ID

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

  </main><!-- End #main -->

  <script>
    function saveToPDF() {
    html2canvas(document.querySelector("#main")).then(canvas => {
        var imgData = canvas.toDataURL('image/png');
        var pdf = new jsPDF('p', 'mm', 'a4');
        var imgWidth = 210; // A4 width in mm
        var imgHeight = canvas.height * imgWidth / canvas.width;
        var heightLeft = imgHeight;

        var position = 0;

        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= 295;

        while (heightLeft >= 0) {
            position -= 295;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= 295;
        }

        pdf.save('student_details.pdf');
    });
}

  </script>

  <?php
  include('footer.php');
  ?>
</body>
</html>

