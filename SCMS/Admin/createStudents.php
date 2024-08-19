<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
  $StudentName = $_POST['StudentName'];
  $StudentIc = $_POST['StudentIc'];
  $StudentParentName = $_POST['StudentParentName'];
  $StudentParentPhone = $_POST['StudentParentPhone'];
  $StudentAddress = $_POST['StudentAddress'];

  if(strlen($StudentIc) != 12){
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>IC Number must be exactly 12 characters long!</div>";
    } else {
    // Corrected variable name and query
    $query = mysqli_query($conn, "SELECT * FROM student WHERE StudentIc ='$StudentIc'");
    $ret = mysqli_fetch_array($query);

    if($ret > 0){ 
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This IC Number Already Exists!</div>";
    } else {
      $query = mysqli_query($conn, "INSERT INTO student (StudentName, StudentIc, StudentParentName, StudentParentPhone, StudentAddress) 
    VALUES ('$StudentName', '$StudentIc', '$StudentParentName', '$StudentParentPhone', '$StudentAddress')");

      if ($query) {
        $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
      } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
    }
  }
}

//---------------------------------------EDIT-------------------------------------------------------------

if (isset($_GET['StudentID']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $StudentID = $_GET['StudentID'];

  $query = mysqli_query($conn, "SELECT * FROM student WHERE StudentID ='$StudentID'");
  $row = mysqli_fetch_array($query);

  //------------UPDATE-----------------------------

    if (isset($_POST['update'])){
      $StudentName = $_POST['StudentName'];
      $StudentIc = $_POST['StudentIc'];
      $StudentParentName = $_POST['StudentParentName'];
      $StudentParentPhone = $_POST['StudentParentPhone'];
      $StudentAddress = $_POST['StudentAddress'];

      // Validate IC Number length
      if(strlen($StudentIc) != 12){
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>IC Number must be exactly 12 characters long!</div>";
      } else {    
      $query = mysqli_query($conn, "UPDATE student SET StudentName='$StudentName', StudentIc='$StudentIc',
        StudentParentName='$StudentParentName', StudentParentPhone='$StudentParentPhone', StudentAddress='$StudentAddress'
        WHERE StudentID='$StudentID'");
      if ($query) {
        echo "<script type='text/javascript'>
        window.location = ('createStudents.php')
        </script>"; 
      } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
    }
  }
}

//--------------------CANCEL EDIT------------------------------------------------

if (isset($_POST['cancel'])) {
    echo "<script type = \"text/javascript\">
    window.location = (\"createStudents.php\")
    </script>"; 
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['StudentID']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $StudentID = $_GET['StudentID'];

  $query = mysqli_query($conn, "DELETE FROM student WHERE StudentID='$StudentID'");

  if ($query == TRUE) {
    echo "<script type='text/javascript'>
    window.location = ('createStudents.php')
    </script>";
  } else {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
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
  <?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  
  <script>
    function classArmDropdown(str) {
      if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
      } else { 
        if (window.XMLHttpRequest) {
          xmlhttp = new XMLHttpRequest();
        } else {
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET", "ajaxClassArms2.php?cid=" + str, true);
        xmlhttp.send();
      }
    }
  </script>
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
            <h1 class="h3 mb-0 text-gray-800">Create Students</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Students</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Students</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Student Full Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="StudentName" value="<?php echo isset($row['StudentName']) ? $row['StudentName'] : ''; ?>" id="exampleInputStudentName" required pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Student Ic<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="StudentIc" value="<?php echo $row['StudentIc'];?>" id="exampleInputStudentIc" pattern="\d{12}" title="IC Number must be exactly 12 numeric" maxlength="12">
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Student Address<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="StudentAddress" value="<?php echo $row['StudentAddress'];?>" id="exampleInputStudentAddress" required >
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Student Parent Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="StudentParentName" value="<?php echo $row['StudentParentName'];?>" id="exampleInputStudentParentName" required pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed" >
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Student Parent Phone Number<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="StudentParentPhone" value="<?php echo $row['StudentParentPhone'];?>" id="exampleInputStudentParentPhone" pattern="\d{10,11}" title="Phone number must be up to 10 or 11 numeric" minlength="10"maxlength="11">
                      </div>
                    </div>
                
                    <?php
                    if (isset($StudentID)) {
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
                  <h6 class="m-0 font-weight-bold text-primary">All Student</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Student Ic</th>
                        <th>Student Parent Name</th>
                        <th>Student Parent Phone</th>
                        <th>Student Address</th>
                         <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                
                    <tbody>

                  <?php
                      $query = "SELECT student.StudentID, student.StudentName,
                      student.StudentIc,student.StudentParentName,student.StudentParentPhone,student.StudentAddress
                      FROM student
                      ORDER BY StudentName ASC";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      $status="";
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                             $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['StudentName']."</td>
                                <td>".$rows['StudentIc']."</td>
                                <td>".$rows['StudentParentName']."</td>
                                <td>".$rows['StudentParentPhone']."</td>
                                <td>".$rows['StudentAddress']."</td>
                                <td><a href='?action=edit&StudentID=".$rows['StudentID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                <td><a href='?action=delete&StudentID=".$rows['StudentID']."' style='color:red;' onclick='return confirm(\"Do you really want to delete?\");'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                              </tr>";
                          }
                      }
                      else
                      {
                           echo   
                           "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
                      }
                      
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            </div>
          </div>

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