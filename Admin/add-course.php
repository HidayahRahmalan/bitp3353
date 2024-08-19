<?php
error_reporting();
include('header.php');
include('../include/connection.php');

$statusMsg = ""; // Initialize status message

// ---------------------SAVE WITH PDF-------------------------------------------
if (isset($_POST['save'])) {
  $course_code = $_POST['course_code'];
  $title = $_POST['title'];
  $credit_hour = '3';
  $type = $_POST['type'];
  $int_id = $_POST['int_id'];
  $pdf = $_FILES["tpfile"]["name"];
  $extension = substr($pdf, strlen($pdf) - 4, strlen($pdf));

  // Allowed extensions
  $allowed_extensions = array(".pdf");

  // Validation for allowed extensions
  if (!in_array($extension, $allowed_extensions)) {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Invalid format, only PDF format file is allowed!</div>";
  } else {
      $propdf = md5($pdf) . $extension;
      move_uploaded_file($_FILES["tpfile"]["tmp_name"], "../teachingplan/" . $propdf);

      try {
          // Using prepared statements to avoid SQL injection
          $stmt = $conn->prepare("CALL add_course(?,?,?,?,?,?)");
          $stmt->bind_param("sssssi", $course_code, $title, $credit_hour, $type, $propdf, $int_id);

          if ($stmt->execute()) {
              $statusMsg = "<div class='alert alert-success' style='margin-right:9px;'>Add Successfully!</div>";
          } else {
              $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Something went wrong. Please try again!</div>";
          }
      } catch (mysqli_sql_exception $e) {
          // Handle specific SQL exceptions
          $errorCode = $e->getCode();
          $errorMessage = $e->getMessage();

          if ($errorCode == 1644) { // MySQL error code for user-defined exception
              if (strpos($errorMessage, 'Course code already exists') !== false) {
                  $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Course code already exists. Please use another course code.</div>";
              } else {
                  $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An error occurred: $errorMessage</div>";
              }
          } else {
              $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An unexpected error occurred: $errorMessage</div>";
          }
      }

      $stmt->close();
  }
}


//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['course_id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $course_id = $_GET['course_id'];
  $query = mysqli_query($conn,"CALL delete_course($course_id)");
  if ($query == TRUE) {
      echo "<script type = \"text/javascript\">window.location = ('add-course.php')</script>";
  }
  else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
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
  <h1>Course</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Course</li>
      <li class="breadcrumb-item active">Add Course</li>
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
              <h5 class="card-title">Course Registration Form</h5>
              <p>Add Diploma and Bachelor Course</p>
              <?php echo $statusMsg;?>
              <!-- Custom Styled Validation with Tooltips -->
              <form method="post" enctype="multipart/form-data">

              <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Course Code<span class="text-danger ml-2"> *</span></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="course_code"  required>
                  </div>
                </div>  

                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Course Title<span class="text-danger ml-2"> *</span></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="title"  required>
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Course Type<span class="text-danger ml-2"> *</span></label>
                  <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example" name="type" required>
                      <option selected="" disabled="disabled">Select Cours</option>
                      <option value="Diploma" <?php echo isset($row['type']) && $row['type'] == 'Diploma' ? 'selected' : ''; ?>>Diploma</option>
                      <option value="Bachelor" <?php echo isset($row['type']) && $row['type'] == 'Bachelor' ? 'selected' : ''; ?>>Bachelor</option>
                    </select>
                  </div>
                </div>
                

                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">File Upload<span class="text-danger ml-2"> *</span></label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile"  name="tpfile" >
                  </div>
                </div>

              <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Institution<span class="text-danger ml-2"> *</span></label>
              <div class="col-sm-10">
              <select id="institution" class="form-select" name="int_id" required>
                  <option disabled="true" disabled="disabled">Select Institution</option>
                  <?php
                  // Fetch course data from the database
                  $sql_int = "SELECT * FROM institution";
                  $result_int = $conn->query($sql_int);
                  
                  // Initialize selectedInstitution variable
                  $selectedInstitution = false;

                  if ($result_int->num_rows > 0) {
                      while ($row_int = $result_int->fetch_assoc()) {
                          // Check if the current advisor's course_id matches the course_id of the student being edited
                          if (!empty($row['int_id']) && $row['int_id'] == $row_int['int_id']) {
                              $selected = 'selected';
                              $selectedInstitution = true; // Set selectedInstitution to true if an advisor is selected
                          } else {
                              $selected = '';
                          }
                          echo "<option value='" . $row_int['int_id'] . "' $selected>" . $row_int['int_name'] .''.$row_int['branch']. "</option>";
                      }
                  }

                  // If no advisor is selected, set the default option as selected
                  if (!$selectedInstitution) {
                      echo "<script>document.getElementById('institution').selectedIndex = 0;</script>";
                  }
                  ?>
              </select>
          </div>
              </div>


                <div class="text-center">
               
                    <button type="submit" name="save" class="btn btn-primary">Submit</button>
                <input class="btn btn-secondary" type="button" onclick="window.location.replace('add-course.php')" value="Cancel" />
            </div>

              </form><!-- End General Form Elements -->
            <!-- End Custom Styled Validation with Tooltips -->
            </div>
          </div>
          <!-- end of registration form -->

          

<!-- DataTable for list of institutions -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Institution List</h5>
        <p>List of registered institutions</p>
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Code</th>
                    <th>Title</th>
                    <th>Credit Hour</th>
                    <th>Course Type</th>
                    <th>Teaching Plan</th>
                    <th>Institution</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Assuming you have already established a database connection

            // Fetching data from the database
            $query = "SELECT * FROM course_details_view";
            $rs = $conn->query($query);
            $sn = 0;

            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    $pdfLink = "../teachingplan/" . $row['tpfile']; // Change the path as needed
                    echo "
                        <tr>
                            <td>".$sn."</td>
                            <td>".$row['course_code']."</td>
                            <td>".$row['title']."</td>
                            <td>".$row['credit_hour']."</td>
                            <td>".$row['type']."</td>
                            <td><a href='".$pdfLink."' target='_blank'>View TP</a></td>
                            <td>".$row['int_name']."</td>
                            <td><a href='manage-course.php?editid=".$row['course_id']."'><i class='ri-file-edit-fill'></i></a></td>
                            <td><a href='?action=delete&course_id=".$row['course_id']."' onclick='return confirm(\"Are you sure to delete (".$row['course_code']." ".$row['title'].")?\")'><i class='ri-delete-bin-6-fill'></i></a></td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='10' class='text-center'>No Record Found!</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <!-- End Table with striped rows -->
    </div>
</div>



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
