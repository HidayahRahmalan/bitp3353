<?php 
error_reporting();
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = ''; // Initialize status message

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
    $TeacherName = mysqli_real_escape_string($conn, $_POST['TeacherName']);
    $TeacherIc = mysqli_real_escape_string($conn, $_POST['TeacherIc']);
    $TeacherEmail = mysqli_real_escape_string($conn, $_POST['TeacherEmail']);
    $TeacherPhone = mysqli_real_escape_string($conn, $_POST['TeacherPhone']);

    // Validate IC Number length
    if(strlen($TeacherIc) != 12){
        $statusMsg = "<div class='alert alert-danger'>IC Number must be exactly 12 characters long!</div>";
    } else {
        // Check if IC Number already exists
        $query = mysqli_query($conn, "SELECT * FROM teacher WHERE TeacherIc = '$TeacherIc'");
        $ret = mysqli_fetch_array($query);

        if($ret > 0){
            $statusMsg = "<div class='alert alert-danger'>This IC Number already exists!</div>";
        } else {
            // Check if Email already exists
            $query = mysqli_query($conn, "SELECT * FROM teacher WHERE TeacherEmail = '$TeacherEmail'");
            $ret = mysqli_fetch_array($query);

            if($ret > 0){
                $statusMsg = "<div class='alert alert-danger'>This Email Address already exists!</div>";
            } else {
                // Insert new teacher record using stored procedure
                $insertQuery = "CALL insert_teacher ('$TeacherName', '$TeacherIc', '$TeacherPhone', '$TeacherEmail')";
                $query = mysqli_query($conn, $insertQuery);

                if($query){
                    $statusMsg = "<div class='alert alert-success'>Teacher added successfully!</div>";
                } else {
                    $statusMsg = "<div class='alert alert-danger'>An error occurred while adding the teacher!</div>";
                }
            }
        }
    }
}

//---------------------------------------EDIT-------------------------------------------------------------

if (isset($_GET['TeacherID']) && isset($_GET['action']) && $_GET['action'] == "edit")
{
  $TeacherID = $_GET['TeacherID'];

  $query = mysqli_query($conn, "SELECT * FROM teacher WHERE TeacherID = '$TeacherID'");
  $row = mysqli_fetch_array($query);

  //------------UPDATE-----------------------------

  if(isset($_POST['update'])){
  
    $TeacherName = $_POST['TeacherName'];
    $TeacherIc = $_POST['TeacherIc'];
    $TeacherEmail = $_POST['TeacherEmail'];
    $TeacherPhone = $_POST['TeacherPhone'];

    // Validate IC Number length
    if(strlen($TeacherIc) != 12){
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>IC Number must be exactly 12 characters long!</div>";
    } else {
      // Check if IC Number already exists (excluding current record)
      $query = mysqli_query($conn, "SELECT * FROM teacher WHERE TeacherIc = '$TeacherIc' AND TeacherID != '$TeacherID'");
      $ret = mysqli_fetch_array($query);

      if($ret > 0){
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This IC Number already exists!</div>";
      } else {
        // Update teacher record
        $query = mysqli_query($conn, "UPDATE teacher SET TeacherName='$TeacherName', TeacherIc='$TeacherIc', TeacherEmail='$TeacherEmail', TeacherPhone='$TeacherPhone' 
                                      WHERE TeacherID='$TeacherID'");
        if ($query) {
          echo "<script type='text/javascript'>
                  window.location = 'createClassTeacher.php';
                </script>"; 
        } else {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating the teacher!</div>";
        }
      }
    }
  }
}

//--------------------CANCEL EDIT------------------------------------------------

if (isset($_POST['cancel'])) {
    echo "<script type = \"text/javascript\">
    window.location = (\"createClassTeacher.php\")
    </script>"; 
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['TeacherID']) && isset($_GET['action']) && $_GET['action'] == "delete")
{
  $TeacherID = $_GET['TeacherID'];

  $query = mysqli_query($conn,"DELETE FROM teacher WHERE TeacherID='$TeacherID'");

  if ($query == TRUE) {
    $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Teacher deleted successfully!</div>"; echo "<script type='text/javascript'>
                  window.location = 'createClassTeacher.php';
                </script>"; 
  } else {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while deleting the teacher!</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <?php include 'Includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Teachers</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Teachers</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Teachers</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      
                      <div class="col-xl-6">
                        <label class="form-control-label">Teacher Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="TeacherName" value="<?php echo isset($row['TeacherName']) ? $row['TeacherName'] : ''; ?>" id="exampleInputName" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed">
                      </div>

                      <div class="col-xl-6">
                        <label class="form-control-label">Ic Number<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="TeacherIc"  value="<?php echo isset($row['TeacherIc']) ? $row['TeacherIc'] : ''; ?>" id="exampleInputIc" required pattern="\d{12}" title="Ic number must be 12 numeric" maxlength="12">
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                        <input type="email" class="form-control" required name="TeacherEmail" value="<?php echo isset($row['TeacherEmail']) ? $row['TeacherEmail'] : ''; ?>" id="exampleInputEmail">
                      </div>
                    <div class="col-xl-6">
                      <label class="form-control-label">Phone No<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="TeacherPhone" value="<?php echo isset($row['TeacherPhone']) ? $row['TeacherPhone'] : ''; ?>" id="exampleInputPhone" required pattern="\d{10,11}" title="Phone number must be up to 10 or 11 numeric" minlength="10"maxlength="11">
                    </div>
                    </div>
                    
                    <?php
                    if (isset($TeacherID))
                    {
                    ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button>
                    <?php
                    } else {           
                    ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php
                    }         
                    ?>
                  </form>
                </div>
              </div>
              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Class Teachers</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Teacher Name</th>
                            <th>Ic number</th>
                            <th>Email Address</th>
                            <th>Phone No</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = "SELECT TeacherID, TeacherName, TeacherIc, TeacherEmail, TeacherPhone FROM teacher";
                          $rs = $conn->query($query);
                          $num = $rs->num_rows;
                          $sn = 0;

                          if($num > 0) { 
                            while ($rows = $rs->fetch_assoc()) {
                              $sn++;
                              echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['TeacherName']."</td>
                                <td>".$rows['TeacherIc']."</td>
                                <td>".$rows['TeacherEmail']."</td>
                                <td>".$rows['TeacherPhone']."</td>
                                <td><a href='?action=edit&TeacherID=".$rows['TeacherID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                <td><a href='?action=delete&TeacherID=".$rows['TeacherID']."' style='color:red;' onclick='return confirm(\"Do you really want to delete?\");'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                              </tr>";
                            }
                          } else {
                            echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!--Row-->

            </div>
            <!---Container Fluid-->
          </div>
          <!-- Footer -->
          <?php include "Includes/footer.php";?>
          <!-- Footer -->
        </div>
      </div>

      <!-- Scroll to top -->
      <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
      </a>

      <script src="../vendor/jquery/jquery.min.js"></script>
      <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
      <script src="js/ruang-admin.min.js"></script>
      <!-- Page level plugins -->
      <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
      <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

      <!-- Page level custom scripts -->
      <script>
        $(document).ready(function () {
          $('#dataTable').DataTable(); // ID From dataTable 
          $('#dataTableHover').DataTable(); // ID From dataTable with Hover
        });
      </script>
    </body>
  </html>
