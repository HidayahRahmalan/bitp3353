<?php
error_reporting();
include('header.php');
include('../include/connection.php');
//session_start();

$statusMsg = ""; // Initialize status message



// Database connection (assuming you have already set up the $conn variable)

// Fetch institution id
$sql = "SELECT * FROM institution WHERE int_name = 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $latest_int = $row['int_name'];
} else {
    echo "No institution found with other name";
    // Handle this case as needed
}

// Fetch admin id
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $query = "SELECT * FROM admin WHERE admin_id = $admin_id";
    $rs = mysqli_query($conn, $query);

    if ($rs && mysqli_num_rows($rs) > 0) {
        $rows = mysqli_fetch_assoc($rs);
        $admin_id = $rows['admin_id'];
    } else {
        echo "No admin found with admin_id = $admin_id.";
        // Handle this case as needed
    }
} else {
    echo "Admin ID is not set in the session.";
    // Handle this case as needed
}




//------------------------SAVE--------------------------------------------------


if(isset($_POST['save'])) {
    // Get form data
    $name = $_POST['name'];
    $icno = $_POST['icno'];
    $faculty = 'FTMK';
    $prog_id = $_POST['prog_id'];
    $currentYear = date('Y');
    $previousYear = $currentYear - 1;
    $session = substr($previousYear, -2) . '/' . substr($currentYear, -2);    
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $int_id = $_POST['int_id'];
    $latest_int = $_POST['latest_int'];
    $username = $_POST['username'];
    $lect_id = $_POST['lect_id'];
    $admin_id = $_POST['admin_id'];

    $sampPass = "pelajarftmk";
    $password = md5($sampPass);

    try {
        // Attempt to insert the new student record
        $query = mysqli_query($conn, "CALL add_student('$name', '$icno', '$faculty', '$prog_id', '$session', '$email', '$phone', '$int_id', '$latest_int', '$username', '$password', '$lect_id', '$admin_id')");
        
        if ($query) {
            $statusMsg = "<div class='alert alert-success' style='margin-right:9px;'>Created Successfully!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An error occurred while creating the record.</div>";
        }
    } catch (mysqli_sql_exception $e) {
        // Handle specific SQL exceptions
        $errorCode = $e->getCode();
        $errorMessage = $e->getMessage();

        if ($errorCode == 1062) { // MySQL error code for duplicate entry
            if (strpos($errorMessage, 'for key \'username\'') !== false) {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Matric No already exists. Please choose a different username.</div>";
            } elseif (strpos($errorMessage, 'for key \'icno\'') !== false) {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>IC Number already exists. Please use a different IC number.</div>";
            } else {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An error occurred: $errorMessage</div>";
            }
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An unexpected error occurred: $errorMessage</div>";
        }
    }
}




//---------------------------------------EDIT-------------------------------------------------------------

//--------------------EDIT------------------------------------------------------------

// Fetch student data if 'edit' action is triggered
if (isset($_GET['stud_id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $stud_id = $_GET['stud_id']; // Fixed variable name
  $query = mysqli_query($conn, "SELECT * FROM student WHERE stud_id ='$stud_id'");
  $row = mysqli_fetch_array($query);
}

// Update student data
if (isset($_POST['update'])) {
  $name = $_POST['name'];
  $icno = $_POST['icno'];
  $prog_id = $_POST['prog_id'];
  $email = $_POST['email'];
  $phoneno = $_POST['phone'];
  $int_id = $_POST['int_id'];
  $username = $_POST['username'];
  $lect_id = $_POST['lect_id'];

  $query = mysqli_query($conn, "CALL update_student('$stud_id','$name','$icno','$prog_id','$email','$phoneno','$int_id','$username','$lect_id')");

  if ($query) {
      echo "<script type='text/javascript'>window.location = ('rstudent.php')</script>";
  } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An error occurred!</div>";
  }
}


//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['stud_id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $stud_id = $_GET['stud_id'];
    $query = mysqli_query($conn,"CALL delete_student('$stud_id')");
    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">window.location = ('rstudent.php')</script>";
    }
    else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An error Occurred!</div>"; 
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Student</title>
</head>
<body>

<main id="main" class="main">

<div class="pagetitle">
  <h1>Direct-Entry Student Registration</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Manage Student</li>
      <li class="breadcrumb-item active">Register DE Student</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Direct-entry Students Registration</h5>
          <p>Register for direct-entry students in <code>Fakulti teknologi Maklumat dan Komunikasi (FTMK)</code></p>
          <?php echo $statusMsg;?>

          <!-- Direct-entry Students Registration -->
          <form method="post" class="row g-3">

            <div class="col-md-12">
              <label for="inputName5" class="form-label">Name<span class="text-danger ml-2"> *</span></label>
              <input type="text" class="form-control" name="name" id="inputName5" maxlength="100" required value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">
            </div>

            <div class="col-md-6">
                <label for="username" class="form-label">Matric No<span class="text-danger ml-2"> *</span></label>
                <input type="text" name="username" class="form-control" id="username" maxlength="20" required value="<?php echo isset($row['username']) ? $row['username'] : ''; ?>" oninput="updateEmail()" onkeypress="return validateUsername(event)">
            </div>

            <div class="col-md-6">
              <label for="icno" class="form-label">IC Number<span class="text-danger ml-2"> *</span></label>
              <input type="text" name="icno" class="form-control" id="icno" maxlength="20" required value="<?php echo isset($row['icno']) ? $row['icno'] : ''; ?>" onkeypress="return onlyNumber(event)">
            </div>

            <div class="col-md-12">
              <label for="programme" class="form-label">Programme<span class="text-danger ml-2"> *</span></label>
              <select id="programme" class="form-select" name="prog_id" required>
                  <option disabled="true" disabled="disabled">Select Programme</option>
                  <?php
                  // Fetch lecturer data from the database
                  $sql_programme = "SELECT * FROM programme WHERE prog_name LIKE '%BACHELOR%' ORDER BY prog_name ASC";
                  $result_programme = $conn->query($sql_programme);
                  
                  // Initialize selectedAdvisor variable
                  $selectedProgramme = false;

                  if ($result_programme->num_rows > 0) {
                      while ($row_programme = $result_programme->fetch_assoc()) {
                          // Check if the current advisor's lect_id matches the lect_id of the student being edited
                          if (!empty($row['prog_id']) && $row['prog_id'] == $row_programme['prog_id']) {
                              $selected = 'selected';
                              $selectedProgramme = true; // Set selectedAdvisor to true if an advisor is selected
                          } else {
                              $selected = '';
                          }
                          echo "<option value='" . $row_programme['prog_id'] . "' $selected>" . $row_programme['prog_name'] . "</option>";
                      }
                  }

                  // If no advisor is selected, set the default option as selected
                  if (!$selectedProgramme) {
                      echo "<script>document.getElementById('programme').selectedIndex = 0;</script>";
                  }
                  ?>
              </select>
          </div>


            <div class="col-md-6">
              <label for="email" class="form-label">Email<span class="text-danger ml-2"> *</span></label>
              <input type="email" name="email" class="form-control" id="email" maxlength="50" value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" readonly>
            </div>

            <div class="col-md-6">
              <label for="phone" class="form-label">Phone No<span class="text-danger ml-2"> *</span></label>
              <input type="text" class="form-control" id="phone" name="phone" maxlength="20" onkeypress="return onlyNumber(event)" required value="<?php echo isset($row['phone']) ? $row['phone'] : ''; ?>">
            </div>


            <div class="col-md-6">
              <label for="academic_advisor" class="form-label">Academic Advisor<span class="text-danger ml-2"> *</span></label>
              <select id="academic_advisor" class="form-select" name="lect_id" required>
                  <option disabled="true" disabled="disabled">Select Academic Advisor</option>
                  <?php
                  // Fetch lecturer data from the database
                  $sql_advisors = "SELECT * FROM lecturer WHERE role = 'Academic Advisor' ORDER BY lect_name ASC";
                  $result_advisors = $conn->query($sql_advisors);
                  
                  // Initialize selectedAdvisor variable
                  $selectedAdvisor = false;

                  if ($result_advisors->num_rows > 0) {
                      while ($row_advisor = $result_advisors->fetch_assoc()) {
                          // Check if the current advisor's lect_id matches the lect_id of the student being edited
                          if (!empty($row['lect_id']) && $row['lect_id'] == $row_advisor['lect_id']) {
                              $selected = 'selected';
                              $selectedAdvisor = true; // Set selectedAdvisor to true if an advisor is selected
                          } else {
                              $selected = '';
                          }
                          echo "<option value='" . $row_advisor['lect_id'] . "' $selected>" . $row_advisor['lect_name'] . "</option>";
                      }
                  }

                  // If no advisor is selected, set the default option as selected
                  if (!$selectedAdvisor) {
                      echo "<script>document.getElementById('academic_advisor').selectedIndex = 0;</script>";
                  }
                  ?>
              </select>
          </div>

          <div class="col-md-6">
                <label for="prev_int" class="form-label">Previous Institution<span class="text-danger ml-2"> *</span></label>
                <select id="prev_int" class="form-select" name="int_id" required>
                    <option value="" disabled="disabled" selected>Select Previous Institution</option>
                    <?php
                    // Fetch lecturer data from the database
                    $sql_int = "SELECT * FROM institution ORDER BY int_name ASC";
                    $result_int = $conn->query($sql_int);
                    
                    // Initialize selectedAdvisor variable
                    $selectedAdvisor = false;

                    if ($result_int->num_rows > 0) {
                        while ($row_int = $result_int->fetch_assoc()) {
                            // Check if the current advisor's int_id matches the int_id of the student being edited
                            if (!empty($row['int_id']) && $row['int_id'] == $row_int['int_id']) {
                                $selected = 'selected';
                                $selectedAdvisor = true; // Set selectedAdvisor to true if an advisor is selected
                            } else {
                                $selected = '';
                            }
                            echo "<option value='" . $row_int['int_id'] . "' $selected>" . $row_int['int_name'] . "</option>";
                        }
                    }

                    // If no advisor is selected, keep the default option as selected
                    if (!$selectedAdvisor) {
                        echo "<option value='' disabled='disabled' selected>Select Previous Institution</option>";
                    }
                    ?>
                </select>
            </div>


            <input type="hidden" id="institution" name="latest_int" value="<?php echo $latest_int;?>" required><br>


            <input type="hidden" id="institution" name="admin_id" value="<?php echo $admin_id;?>" required><br>


            <br><br><br><br>

            <div class="text-center">
                <?php if(isset($stud_id)) { ?>
                    <button type="submit" class="btn btn-warning" name="update">Save Changes</button>
                <?php } else { ?>
                    <button type="submit" name="save" class="btn btn-primary">Submit</button>
                <?php } ?>
                <input class="btn btn-secondary" type="button" onclick="window.location.replace('rstudent.php')" value="Cancel" />
            </div>
          </form><!-- End Direct-entry Students Registration -->

        </div>
      </div>

      <!-- table for student -->
      <!-- DataTable for list of student -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">student List</h5>
          <p>List of registered direct-entry students in FTMK</p>
          <!-- Table with stripped rows -->
          <div class="table-responsive">
          <table class="table datatable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Matric No</th>
                <th>ICNO</th>
                <th>Faculty</th>
                <th>Programme</th>
                <th>Session</th>
                <th>Email</th>
                <th>Phone No</th>
                <th>Academic Advisor</th>
                <th>Previous Institution</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>

            <tbody>
            <?php
            $query = "SELECT * FROM student_details_view";
            $rs = $conn->query($query);
            $sn = 0;
            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    echo "
                        <tr>
                            <td>" . $sn . "</td>
                            <td>" . $row['name'] . "</td>
                            <td>" . $row['username'] . "</td>
                            <td>" . $row['icno'] . "</td>
                            <td>" . $row['faculty'] . "</td>
                            <td>" . $row['prog_code'] . "</td>
                            <td>" . $row['session'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td>" . $row['phone'] . "</td>
                            <td>" . $row['lect_name'] . "</td>
                            <td>" . $row['int_name'] . "</td>
                            <td><a href='?action=edit&stud_id=" . $row['stud_id'] . "'><i class='ri-file-edit-fill'></i></a></td>
                            <td><a href='?action=delete&stud_id=" . $row['stud_id'] . "' onclick='return confirm(\"Are you sure to delete (" . $row['name'] . ")?\")'><i class='ri-delete-bin-6-fill'></i></a></td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No Record Found!</td></tr>";
            }
            ?>
            </tbody>
          </table>
          <!-- End Table with stripped rows -->
        </div>
        </div>
      </div>
    </div>
  </div>
</section>
</main><!-- End #main -->

<script>
  // email update by matric number
  function updateEmail() {
    var matricNo = document.getElementById("username").value;
    var emailInput = document.getElementById("email");
    var emailSuffix = "@student.utem.edu.my";
    emailInput.value = matricNo + emailSuffix;
  }

  // accept only number for the IC number and phone fields
  function onlyNumber(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;
  }

  // validate username to ensure it starts with B032 and only allows numbers after
  function validateUsername(evt) {
    var input = document.getElementById("username");
    var value = input.value;

    // Ensure the value starts with B032
    if (!value.startsWith("B032")) {
      input.value = "B032";
    }

    // Allow only numbers after the prefix
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode < 48 || charCode > 57) {
      return false;
    }

    return true;
  }
</script>

</body>
</html>

<?php
include('footer.php');
?>
