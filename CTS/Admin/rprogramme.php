<?php
error_reporting(0);
include('header.php');
include('../include/connection.php');

$statusMsg = ""; // Initialize status message

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
    $prog_name = $_POST['prog_name'];
    $prog_code = $_POST['prog_code'];
 
    $query = mysqli_query($conn,"SELECT * FROM programme WHERE prog_code ='$prog_code'");
    $ret = mysqli_fetch_array($query);

    if($ret > 0){ 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Programme Code Already Exists!</div>";
    }
    else{
        $query = mysqli_query($conn,"CALL add_programme('$prog_name','$prog_code')");
        if ($query) {
            $statusMsg = "<div class='alert alert-success'  style='margin-right:700px;'>Created Successfully!</div>";
        }
        else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//---------------------------------------EDIT-------------------------------------------------------------

//--------------------EDIT------------------------------------------------------------

if (isset($_GET['prog_id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $prog_id = $_GET['prog_id'];
    $query = mysqli_query($conn,"SELECT * FROM programme WHERE prog_id ='$prog_id'");
    $row = mysqli_fetch_array($query);

    //------------UPDATE-----------------------------

    if(isset($_POST['update'])){
  
        $prog_name = $_POST['prog_name'];
        $prog_code = $_POST['prog_code'];

        $query = mysqli_query($conn,"CALL update_programme('$prog_id','$prog_name','$prog_code')");
        if ($query) {
            echo "<script type = \"text/javascript\">window.location = ('rprogramme.php')</script>"; 
        }
        else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['prog_id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $prog_id = $_GET['prog_id'];
    $query = mysqli_query($conn,"CALL delete_programme($prog_id)");
    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">window.location = ('rprogramme.php')</script>";
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
    <title>Register Programme</title>
</head>
<body>

<main id="main" class="main">

<div class="pagetitle">
  <h1>Programme Registration</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Registration Forms</li>
      <li class="breadcrumb-item active">Register Programme</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- section start for registration form and datatable -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">

    <!-- programme registration form -->
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Programme Registration</h5>
              <p>Register for programme in <code>Fakulti teknologi Maklumat dan Komunikasi (FTMK)</code></p>
              <?php echo $statusMsg;?>
              <!-- Custom Styled Validation with Tooltips -->
              <form method="post" class="row g-3 needs-validation" novalidate>

                  <!-- Start of registration form -->
                  <div class="col-md-8 position-relative">
                    <!-- Programme column for programme  name -->
                    <label for="validationTooltip01" class="form-label">Programme Name<span class="text-danger ml-2"> *</span></label>
                    <!-- Label for the Programme name input field -->                    
                    <input type="text" class="form-control" name="prog_name" id="validationTooltip01" maxlength="200" oninput="this.value = this.value.toUpperCase()" value="<?php echo isset($row['prog_name']) ? $row['prog_name'] : ''; ?>" required>
                    <!-- Input field for entering the Programme name -->
                    <div class="valid-tooltip">
                      <!-- Tooltip for valid input -->
                      Looks good!
                    </div>
                    <div class="invalid-tooltip">
                        <!-- Tooltip for invalid input -->
                        Please choose a unique and valid programme name.
                      </div>
                  </div>

                   <!-- Start of registration form -->
                   <div class="col-md-4 position-relative">
                    <!-- Programme column for Programme name -->
                    <label for="validationTooltip01" class="form-label">Programme Code<span class="text-danger ml-2"> *</span></label>
                    <!-- Label for the Programme name input field -->                    
                    <input type="text" class="form-control" name="prog_code" id="validationTooltip01" maxlength="10" oninput="this.value = this.value.toUpperCase()" style="text-transform: uppercase;" value="<?php echo isset($row['prog_code']) ? $row['prog_code'] : ''; ?>" required>
                    <!-- Input field for entering the Programme name -->
                    <div class="valid-tooltip">
                      <!-- Tooltip for valid input -->
                      Looks good!
                    </div>
                    <div class="invalid-tooltip">
                        <!-- Tooltip for invalid input -->
                        Please choose a unique and valid programme name.
                      </div>
                  </div>
                  <div class="col-12">
                    <!-- Full-width column for submit button -->
                    <?php
                    if (isset($prog_id))
                    { ?>
                    <button class="btn btn-warning" type="submit" name="update">Edit</button>
                    <?php 
                    }
                    else {
                   ?>
                    <button class="btn btn-primary" type="submit" name="save">Save</button>
                    <!-- Submit button for the form -->
                  <?php
                    }
                  ?>
                  <input class="btn btn-default" type="button" onclick="window.location.replace('rprogramme.php')" value="Cancel" />
                    </div>

            </form>
            <!-- End of registration form -->
            <!-- End Custom Styled Validation with Tooltips -->
            </div>
          </div>
          <!-- end of registration form -->

<!-- DataTable for list of programme -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Programme List</h5>
        <p>List of registered programmes in FTMK</p>
          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Programme Name</th>
                <th>Programme Code</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>

            <tbody>
            <?php
                $query = "SELECT prog_id, prog_name, prog_code FROM programme";
                $rs = $conn->query($query);
                $sn = 0;
                if($rs && $rs->num_rows > 0) { 
                    while ($row = $rs->fetch_assoc()) {
                        $sn++;
                        echo "
                            <tr>
                                <td>".$sn."</td>
                                <td>".$row['prog_name']."</td>
                                <td>".$row['prog_code']."</td>
                                <td><a href='?action=edit&prog_id=".$row['prog_id']."'><i class='ri-file-edit-fill'></i></a></td>
                                <td><a href='?action=delete&prog_id=".$row['prog_id']."' onclick='return confirm(\"Are you sure to delete (".$row['prog_name'].")?\")'><i class='ri-delete-bin-6-fill'></i></a></td>
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
</body>
</html>

<?php
include('footer.php');
?>
