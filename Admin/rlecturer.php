<?php
error_reporting();
include('header.php');
include('../include/connection.php');

$statusMsg = ""; // Initialize status message

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])) {
  // Get form data
  $lect_name = $_POST['lect_name'];
  $phoneno = $_POST['phoneno'];
  $email = $_POST['email'];
  $username = $_POST['username'];
  $role = $_POST['role'];

  // Default password (sample)
  $sampPass = "pensyarahftmk";
  $password = md5($sampPass);

  try {
      // Attempt to insert the new lecturer record
      $query = mysqli_query($conn, "CALL add_lecturer('$lect_name', '$phoneno', '$email', '$username', '$password', '$role')");
      
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
              $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Staff ID already exists. Please choose a different username.</div>";
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

if (isset($_GET['lect_id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $lect_id = $_GET['lect_id'];
    $query = mysqli_query($conn,"SELECT * FROM lecturer WHERE lect_id ='$lect_id'");
    $row = mysqli_fetch_array($query);

    //------------UPDATE-----------------------------

    if(isset($_POST['update'])){
  
        $lect_name = $_POST['lect_name'];
        $phoneno = $_POST['phoneno'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $role = $_POST['role'];


        $query = mysqli_query($conn,"CALL update_lecturer ('$lect_id','$lect_name','$phoneno','$email' ,'$username','$role')");
        if ($query) {
            echo "<script type = \"text/javascript\">window.location = ('rlecturer.php')</script>"; 
        }
        else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['lect_id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $lect_id = $_GET['lect_id'];
    $query = mysqli_query($conn,"CALL delete_lecturer($lect_id)");
    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">window.location = ('rlecturer.php')</script>";
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
    <title>Admin - Lecturer</title>
</head>
<body>

<main id="main" class="main">

<div class="pagetitle">
  <h1>
    Lecturer Registration</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Manage Lecturer</li>
      <li class="breadcrumb-item active">Register lecturer</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- section start for registration form and datatable -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">

    <!-- lecturer registration form -->
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Lecturer Registration</h5>
              <p>Register for lecturer in <code>Fakulti teknologi Maklumat dan Komunikasi (FTMK)</code></p>
              <?php echo $statusMsg;?>
              <!-- Custom Styled Validation with Tooltips -->
              <!-- Multi Columns Form -->
              <form method="post" class="row g-3 needs-validation" novalidate>
                <div class="col-md-12 position-relative">
                  <label for="inputName5" class="form-label">Lecturer Name<span class="text-danger ml-2"> *</span></label>
                  <input type="text" class="form-control" id="validationTooltip01" name="lect_name" maxlength="100" oninput="this.value = this.value.toUpperCase()" value="<?php echo isset($row['lect_name']) ? $row['lect_name'] : ''; ?>" required>
                  <div class="valid-tooltip">
                      <!-- Tooltip for valid input -->
                      Looks good!
                    </div>
                    <div class="invalid-tooltip">
                        <!-- Tooltip for invalid input -->
                        Please choose valid lecturer name.
                      </div>
                </div>

                <div class="col-md-6 position-relative">
                  <label for="inputPhoneNo" class="form-label">Phone No.<span class="text-danger ml-2"> *</span></label>
                  <input type="text" class="form-control" id="validationTooltip01" name="phoneno" onkeypress="return onlyNumber(event)" maxlength="200" oninput="this.value = this.value.toUpperCase()" value="<?php echo isset($row['phoneno']) ? $row['phoneno'] : ''; ?>" required>
                  <div class="valid-tooltip">
                      <!-- Tooltip for valid input -->
                      Looks good!
                    </div>
                    <div class="invalid-tooltip">
                        <!-- Tooltip for invalid input -->
                        Please choose a unique and valid phone no.
                      </div>
                </div>

                <div class="col-md-6 position-relative">
                  <label for="inputEmail5" class="form-label">Email<span class="text-danger ml-2"> *</span></label>
                  <input type="email" class="form-control" id="validationTooltip01" name="email" maxlength="200" value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" required>
                  <div class="valid-tooltip">
                      <!-- Tooltip for valid input -->
                      Looks good!
                    </div>
                    <div class="invalid-tooltip">
                        <!-- Tooltip for invalid input -->
                        Please choose a unique and valid email.
                      </div>
                </div>

                <div class="col-md-6 position-relative">
                  <label for="inputStaffId" class="form-label">Staff ID<span class="text-danger ml-2"> *</span></label>
                  <input type="text" class="form-control" id="validationTooltip01" name="username" maxlength="200" oninput="this.value = this.value.toUpperCase()" value="<?php echo isset($row['username']) ? $row['username'] : ''; ?>" required>
                  <div class="valid-tooltip">
                      <!-- Tooltip for valid input -->
                      Looks good!
                    </div>
                    <div class="invalid-tooltip position-relative">
                        <!-- Tooltip for invalid input -->
                        Please choose a unique and valid staff ID.
                      </div>
                </div>

                <div class="col-md-6">
                  <label for="role" class="form-label">Staff Role <span class="text-danger ml-2"> *</span></label>
                  <select class="form-select" id="role" name="role" required>
                    <option selected="true" disabled="disabled">Select Staff Role</option>
                    <option value="Academic Advisor" <?php echo isset($row['role']) && $row['role'] == 'Academic Advisor' ? 'selected' : ''; ?>>Academic Advisor</option>
                    <option value="Acadmic Deputy Dean" <?php echo isset($row['role']) && $row['role'] == 'Academic Deputy Dean' ? 'selected' : ''; ?>>Deputy Acadmic Dean</option>
                    <option value="Dean" <?php echo isset($row['role']) && $row['role'] == 'Dean' ? 'selected' : ''; ?>>Dean</option>
                  </select>
                </div>
          
                <div class="col-12">
                    <!-- Full-width column for submit button -->
                    <?php
                    if (isset($lect_id))
                    { ?>
                    <button class="btn btn-warning" type="submit" name="update">Save Changes</button>
                    <?php 
                    }
                    else {
                   ?>
                    <button class="btn btn-primary" type="submit" name="save">Save</button>
                    <!-- Submit button for the form -->
                  <?php
                    }
                  ?>
                  <input class="btn btn-secondary" type="button" onclick="window.location.replace('rlecturer.php')" value="Cancel" />
                    </div>
              </form><!-- End Multi Columns Form -->
            <!-- End of registration form -->
            <!-- End Custom Styled Validation with Tooltips -->
            </div>
          </div>
          <!-- end of registration form -->

<!-- DataTable for list of lecturer -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">lecturer List</h5>
        <p>List of registered lecturers in FTMK</p>
          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Lecturer Name</th>
                <th>Phone No</th>
                <th>Email</th>
                <th>Staff ID</th>
                <th>Staff Role</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>

            <tbody>
            <?php
                $query = "SELECT lect_id, lect_name,phoneno, email, username,role FROM lecturer";
                $rs = $conn->query($query);
                $sn = 0;
                if($rs && $rs->num_rows > 0) { 
                    while ($row = $rs->fetch_assoc()) {
                        $sn++;
                        echo "
                            <tr>
                                <td>".$sn."</td>
                                <td>".$row['lect_name']."</td>
                                <td>".$row['phoneno']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['username']."</td>
                                <td>".$row['role']."</td>
                                <td><a href='?action=edit&lect_id=".$row['lect_id']."'><i class='ri-file-edit-fill'></i></a></td>
                                <td><a href='?action=delete&lect_id=".$row['lect_id']."' onclick='return confirm(\"Are you sure to delete (".$row['lect_name'].")?\")'><i class='ri-delete-bin-6-fill'></i></a></td>
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
</section>
</main><!-- End #main -->

<script>
  function onlyNumber(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)){
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
