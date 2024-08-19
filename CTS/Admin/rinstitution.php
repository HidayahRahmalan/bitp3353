<?php
error_reporting(0);
include('header.php');
include('../include/connection.php');

$statusMsg = ""; // Initialize status message

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
    $int_name = $_POST['int_name'];
    $branch = $_POST['branch'];
 
    $query = mysqli_query($conn,"SELECT * FROM institution WHERE int_name ='$int_name'");
    $ret = mysqli_fetch_array($query);

    if($ret > 0){ 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This institution Already Exists!</div>";
    }
    else{
        $query = mysqli_query($conn,"CALL add_institution('$int_name','$branch')");
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

if (isset($_GET['int_id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $int_id = $_GET['int_id'];
    $query = mysqli_query($conn,"SELECT * FROM institution WHERE int_id ='$int_id'");
    $row = mysqli_fetch_array($query);

    //------------UPDATE-----------------------------

    if(isset($_POST['update'])){
  
        $int_name = $_POST['int_name'];
        $branch = $_POST['branch'];

        $query = mysqli_query($conn,"CALL update_institution('$int_id','$int_name','$branch')");
        if ($query) {
            echo "<script type = \"text/javascript\">window.location = ('rinstitution.php')</script>"; 
        }
        else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['int_id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $int_id = $_GET['int_id'];
    $query = mysqli_query($conn,"CALL delete_institution($int_id)");
    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">window.location = ('rinstitution.php')</script>";
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
  <h1>Institution Registration</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Registration Forms</li>
      <li class="breadcrumb-item active">Register institution</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- section start for registration form and datatable -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">

    <!-- institution registration form -->
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Institution Registration</h5>
              <p>Register for institution</p>
              <?php echo $statusMsg;?>
              <!-- Custom Styled Validation with Tooltips -->
              <form method="post" class="row g-3 needs-validation" novalidate>

                  <!-- Start of registration form -->
                  <div class="col-md-8 position-relative">
                    <!-- institution column for institution  name -->
                    <label for="validationTooltip01" class="form-label">Institution Name<span class="text-danger ml-2"> *</span></label>
                    <!-- Label for the institution name input field -->                    
                    <input type="text" class="form-control" name="int_name" id="validationTooltip01" maxlength="200" oninput="this.value = this.value.toUpperCase()" value="<?php echo isset($row['int_name']) ? $row['int_name'] : ''; ?>" required>
                    <!-- Input field for entering the institution name -->
                    <div class="valid-tooltip">
                      <!-- Tooltip for valid input -->
                      Looks good!
                    </div>
                    <div class="invalid-tooltip">
                        <!-- Tooltip for invalid input -->
                        Please choose a unique and valid institution name.
                      </div>
                  </div>

                   <!-- Start of registration form -->
                   <div class="col-md-4 position-relative">
                    <!-- institution column for institution name -->
                    <label for="validationTooltip01" class="form-label">Branch</label>
                    <!-- Label for the institution name input field -->                    
                    <input type="text" class="form-control" name="branch" id="validationTooltip01" maxlength="100" oninput="this.value = this.value.toUpperCase()" value="<?php echo isset($row['branch']) ? $row['branch'] : ''; ?>">
                    <!-- Input field for entering the institution name -->
                    <div class="valid-tooltip">
                      <!-- Tooltip for valid input -->
                      Looks good!
                    </div>
                    <div class="invalid-tooltip">
                        <!-- Tooltip for invalid input -->
                        Please choose a unique and valid institution name.
                      </div>
                  </div>
                  <div class="col-12">
                    <!-- Full-width column for submit button -->
                    <?php
                    if (isset($int_id))
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
                  <input class="btn btn-default" type="button" onclick="window.location.replace('rinstitution.php')" value="Cancel" />
                    </div>

            </form>
            <!-- End of registration form -->
            <!-- End Custom Styled Validation with Tooltips -->
            </div>
          </div>
          <!-- end of registration form -->

<!-- DataTable for list of institution -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Institution List</h5>
        <p>List of registered institutions</p>
          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Institution Name</th>
                <th>Branch</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>

            <tbody>
            <?php
                $query = "SELECT int_id, int_name, branch FROM institution";
                $rs = $conn->query($query);
                $sn = 0;
                if($rs && $rs->num_rows > 0) { 
                    while ($row = $rs->fetch_assoc()) {
                        $sn++;
                        echo "
                            <tr>
                                <td>".$sn."</td>
                                <td>".$row['int_name']."</td>
                                <td>".$row['branch']."</td>
                                <td><a href='?action=edit&int_id=".$row['int_id']."'><i class='ri-file-edit-fill'></i></a></td>
                                <td><a href='?action=delete&int_id=".$row['int_id']."' onclick='return confirm(\"Are you sure to delete (".$row['int_name'].")?\")'><i class='ri-delete-bin-6-fill'></i></a></td>
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
