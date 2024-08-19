<?php
error_reporting();
include('header.php');
include('../include/connection.php');

$statusMsg = ""; // Initialize status message

if (isset($_POST['submit'])) {
    $course_code = $_POST['course_code'];
    $title = $_POST['title'];
    $credit_hour = $_POST['credit_hour'];
    $type = $_POST['type'];
    $int_id = $_POST['int_id'];
    $eid = $_GET['editid'];
    $propdf = "";

    // Check if a new file is uploaded
    if (!empty($_FILES['tpfile']['name'])) {
        $pdf = $_FILES['tpfile']['name'];
        $extension = substr($pdf, strlen($pdf) - 4, strlen($pdf));

        // Allowed extensions
        $allowed_extensions = array(".pdf");

        // Validation for allowed extensions
        if (!in_array($extension, $allowed_extensions)) {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Invalid format, only PDF format file is allowed!</div>";
        } else {
            $propdf = md5($pdf) . $extension;
            move_uploaded_file($_FILES['tpfile']['tmp_name'], "../teachingplan/" . $propdf);
        }
    } else {
        // Use the existing file if no new file is uploaded
        $propdf = $_POST['current_tpfile'];
    }

    // Update the database with the new values
    $query = mysqli_query($conn, "UPDATE course SET course_code='$course_code', title='$title', credit_hour='$credit_hour', type='$type', tpfile='$propdf', int_id='$int_id' WHERE course_id='$eid'");

    if ($query) {
      echo "<script>alert('Course details have been updated.'); window.location.href='add-course.php';</script>";

    } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Something went wrong. Please try again!</div>";
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
  <h1>Diploma Course</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Diploma Course</li>
      <li class="breadcrumb-item active">Register institution</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- section start for registration form and datatable -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">

    <!-- Diploma Course form -->
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Diploma Course</h5>
              <p>Register for institution</p>
              <?php echo $statusMsg;?>
              <!-- Custom Styled Validation with Tooltips -->
              <form method="post" enctype="multipart/form-data">

              <?php
              $cid = $_GET['editid'];
              $ret = mysqli_query($conn, "SELECT * FROM course WHERE course_id='$cid'");
              while ($row = mysqli_fetch_array($ret)) {
              ?>

              <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Course Code</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="course_code" value="<?php echo  $row['course_code']; ?>" required>
                  </div>
                </div>  

                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Course Title</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" value="<?php echo $row['title']; ?>" required>
                  </div>
                </div>

                <!-- <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Category</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example" name="category" required>
                        <option selected="" disabled="disabled">Select Course Category</option>
                        <option value="P" <?php // echo $row['category'] == 'P' ? 'selected' : ''; ?>>Programme Core</option>
                        <option value="K" <?php //echo $row['category'] == 'K' ? 'selected' : ''; ?>>Course Core</option>
                        <option value="E" <?php //echo $row['category'] == 'E' ? 'selected' : ''; ?>>Elective</option>
                        <option value="W" <?php //echo $row['category'] == 'W' ? 'selected' : ''; ?>>University Compulsory</option>
                    </select>
                </div>
            </div> -->

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Course Type</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example" name="type" required>
                        <option selected="" disabled="disabled">Select Course Category</option>
                        <option value="Diploma" <?php echo $row['type'] == 'Diploma' ? 'selected' : ''; ?>>Diploma</option>
                        <option value="Bachelor" <?php echo $row['type'] == 'Bachelor' ? 'selected' : ''; ?>>Bachelor</option>
                    </select>
                </div>
            </div>

                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Credit Hour</label>
                  <div class="col-sm-5">
                    <input type="number" class="form-control" name="credit_hour" min="0" max="3" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" value="<?php echo $row['credit_hour']; ?>" required>
                  </div>
                </div>

                <div class="row mb-3">
                <label for="inputNumber" class="col-sm-2 col-form-label">File Upload</label>
                <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile" name="tpfile">
                    <?php if (!empty($row['tpfile'])): ?>
                        <p>Current File: <a href="../teachingplan/<?php echo $row['tpfile']; ?>" target="_blank"><?php echo $row['tpfile']; ?></a></p>
                        <input type="hidden" name="current_tpfile" value="<?php echo $row['tpfile']; ?>">
                    <?php endif; ?>
                </div>
            </div>

                <!-- <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Programme</label>
                  <div class="col-sm-10">
                  <select id="programme" class="form-select" name="prog_id" required>
                      <option disabled="true" disabled="disabled">Select Programme</option>
                      <?php
                      // Fetch programme data from the database
                      // $sql_prog = "SELECT * FROM programme ORDER BY prog_name ASC";
                      // $result_prog = $conn->query($sql_prog);
                      
                      // Initialize selectedProgramme variable
                      // $selectedProgramme = false;

                      // if ($result_prog->num_rows > 0) {
                      //     while ($row_prog = $result_prog->fetch_assoc()) {
                      //         if (!empty($row['prog_id']) && $row['prog_id'] == $row_prog['prog_id']) {
                      //             $selected = 'selected';
                      //             $selectedProgramme = true;
                      //         } else {
                      //             $selected = '';
                      //         }
                      //         echo "<option value='" . $row_prog['prog_id'] . "' $selected>" . $row_prog['prog_name'] . "</option>";
                      //     }
                      //   }

                        // If no programme is selected, set the default option as selected
                        // if (!$selectedProgramme) {
                        //     echo "<script>document.getElementById('programme').selectedIndex = 0;</script>";
                        // }
                        // ?>
                    </select>
                </div>
              </div> -->

              <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Institution</label>
              <div class="col-sm-10">
              <select id="institution" class="form-select" name="int_id" required>
                  <option disabled="true" disabled="disabled">Select Institution</option>
                  <?php
                  // Fetch institution data from the database
                  $sql_int = "SELECT * FROM institution";
                  $result_int = $conn->query($sql_int);
                  
                  // Initialize selectedInstitution variable
                  $selectedInstitution = false;

                  if ($result_int->num_rows > 0) {
                      while ($row_int = $result_int->fetch_assoc()) {
                          if (!empty($row['int_id']) && $row['int_id'] == $row_int['int_id']) {
                              $selected = 'selected';
                              $selectedInstitution = true;
                          } else {
                              $selected = '';
                          }
                          echo "<option value='" . $row_int['int_id'] . "' $selected>" . $row_int['int_name'] . ' ' . $row_int['branch'] . "</option>";
                      }
                  }

                  // If no institution is selected, set the default option as selected
                  if (!$selectedInstitution) {
                      echo "<script>document.getElementById('institution').selectedIndex = 0;</script>";
                  }
                  ?>
              </select>
          </div>
              </div>

                <?php }?>
                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-warning">Save Changes</button>
                <input class="btn btn-secondary" type="button" onclick="window.location.replace('add-course.php')" value="Cancel" />
            </div>

              </form><!-- End General Form Elements -->
            <!-- End Custom Styled Validation with Tooltips -->
            </div>
          </div>
          <!-- end of registration form -->
        </div>
      </div>
    </div>
  </div>

</section>
</main><!-- End #main -->
</body>
</html>

<?php
include('footer.php');
?>
