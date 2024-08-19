
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    $StudentID = $_POST['StudentID'];
    $ClubID = $_POST['ClubID'];
    $CurrentYear = date("Y");
    $StudentClass = $_POST['StudentClass'];
    $StudentFormLevel = $_POST['StudentFormLevel'];
    $ClubPosition = $_POST['ClubPosition'];
    // $DateCreated = date("Y-m-d");

    // Check if the combination of StudentID and ClubID already exists
    $checkQuery = "SELECT * FROM registerstudent WHERE StudentID = '$StudentID' AND ClubID = '$ClubID' AND CurrentYear = '$CurrentYear'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if(mysqli_num_rows($checkResult) > 0){ 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Student Already Registered!</div>";
    } else {
        // Insert the data into the database
        $insertQuery = "INSERT INTO registerstudent (StudentID, ClubID, CurrentYear, StudentClass, StudentFormLevel, ClubPosition) 
                        VALUES ('$StudentID', '$ClubID', '$CurrentYear', '$StudentClass', '$StudentFormLevel', '$ClubPosition')";
        if(mysqli_query($conn, $insertQuery)){
            $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//--------------------EDIT------------------------------------------------------------

// Check if editing a record
if (isset($_GET['RegisterStudentID']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $RegisterStudentID = $_GET['RegisterStudentID'];

    // Fetch existing data based on RegisterStudentID
    $query = mysqli_query($conn, "SELECT * FROM registerstudent WHERE RegisterStudentID = '$RegisterStudentID'");
    $row = mysqli_fetch_assoc($query);

    // Populate variables for pre-filling form fields
    $StudentID = $row['StudentID'];
    $ClubID = $row['ClubID'];
    $CurrentYear = date("Y");
    $StudentClass = $row['StudentClass'];
    $StudentFormLevel = $row['StudentFormLevel'];
    $ClubPosition = $row['ClubPosition'];

    // Handle form submission for update
    if(isset($_POST['update'])){
        $StudentID = $_POST['StudentID'];
        $ClubID = $_POST['ClubID'];
        $CurrentYear = $_POST['CurrentYear'];
        $StudentClass = $_POST['StudentClass'];
        $StudentFormLevel = $_POST['StudentFormLevel'];
        $ClubPosition = $_POST['ClubPosition'];

        $updateQuery = "UPDATE registerstudent SET StudentID='$StudentID', ClubID='$ClubID', StudentClass='$StudentClass', StudentFormLevel='$StudentFormLevel', ClubPosition='$ClubPosition' WHERE RegisterStudentID='$RegisterStudentID'";

        if (mysqli_query($conn, $updateQuery)) {
            echo "<script type='text/javascript'>
            window.location = ('StudentSession.php')
            </script>"; 
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//--------------------CANCEL EDIT------------------------------------------------

if (isset($_POST['cancel'])) {
    echo "<script type = \"text/javascript\">
    window.location = (\"StudentSession.php\")
    </script>"; 
}

//--------------------------------DELETE------------------------------------------------------------------

  if (isset($_GET['RegisterStudentID']) && isset($_GET['action']) && $_GET['action'] == "delete")
  {
        $RegisterStudentID= $_GET['RegisterStudentID'];

        $query = mysqli_query($conn,"DELETE FROM registerstudent WHERE RegisterStudentID='$RegisterStudentID'");

        if ($query == TRUE) {

                echo "<script type = \"text/javascript\">
                window.location = (\"StudentSession.php\")
                </script>";  
        }
        else{

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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
            <h1 class="h3 mb-0 text-gray-800">Create Student Session</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Student Session</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Student Session</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">

                      <div class="col-xl-6">
                        <label class="form-control-label">Select Student<span class="text-danger ml-2">*</span></label>
                        <select required name="StudentID" id="select-student" class="form-control mb-3">
                          <option value="">--Select Student--</option>
                          <?php
                          $studentQuery = "SELECT * FROM student ORDER BY StudentName ASC";
                          $studentResult = mysqli_query($conn, $studentQuery);
                          while ($studentRow = mysqli_fetch_assoc($studentResult)) {
                              $selected = ($studentRow['StudentID'] == $StudentID) ? "selected" : "";
                              echo '<option value="' . $studentRow['StudentID'] . '" ' . $selected . '>' . $studentRow['StudentName'] . '</option>';
                          }
                          ?>
                        </select>
                      </div>

                      <div class="col-xl-6">
                                  <label class="form-control-label">Select Form Level<span class="text-danger ml-2">*</span></label>
                                  <select name="StudentFormLevel" class="form-control mb-3">
                                    <option value="">--Select Form Level--</option>
                                    <option value="1" <?php if($StudentFormLevel == '1') { echo "selected"; } ?>>1</option>
                                    <option value="2" <?php if($StudentFormLevel == '2') { echo "selected"; } ?>>2</option>
                                    <option value="3" <?php if($StudentFormLevel == '3') { echo "selected"; } ?>>3</option>
                                    <option value="4" <?php if($StudentFormLevel == '4') { echo "selected"; } ?>>4</option>
                                    <option value="5" <?php if($StudentFormLevel == '5') { echo "selected"; } ?>>5</option>
                                  </select>
                                  </div>

                                  <div class="col-xl-6">
                                  <label class="form-control-label">Student Class<span class="text-danger ml-2">*</span></label>
                                  <select name="StudentClass" class="form-control mb-3">
                                    <option value="">--Select Class--</option>
                                    <option value="1 Khadijah" <?php if($StudentClass == '1 Khadijah') { echo "selected"; } ?>>1 Khadijah</option>
                                    <option value="1 Saodah" <?php if($StudentClass == '1 Saodah') { echo "selected"; } ?>>1 Saodah</option>
                                    <option value="1 Aisyah" <?php if($StudentClass == '1 Aisyah') { echo "selected"; } ?>>1 Aisyah</option>
                                    <option value="2 Khadijah" <?php if($StudentClass == '2 Khadijah') { echo "selected"; } ?>>2 Khadijah</option>
                                    <option value="2 Saodah" <?php if($StudentClass == '2 Saodah') { echo "selected"; } ?>>2 Saodah</option>
                                    <option value="2 Aisyah" <?php if($StudentClass == '2 Aisyah') { echo "selected"; } ?>>2 Aisyah</option>
                                    <option value="3 Khadijah" <?php if($StudentClass == '3 Khadijah') { echo "selected"; } ?>>3 Khadijah</option>
                                    <option value="3 Saodah" <?php if($StudentClass == '3 Saodah') { echo "selected"; } ?>>3 Saodah</option>
                                    <option value="3 Aisyah" <?php if($StudentClass == '3 Aisyah') { echo "selected"; } ?>>3 Aisyah</option>
                                    <option value="4 Ibnu Sina" <?php if($StudentClass == '4 Ibnu Sina') { echo "selected"; } ?>>4 Ibnu Sina</option>
                                    <option value="4 Al-Khawarizmi" <?php if($StudentClass == '4 Al-Khawarizmi') { echo "selected"; } ?>>4 Al-Khawarizmi</option>
                                    <option value="4 As-Syafie" <?php if($StudentClass == '4 As-Syafie') { echo "selected"; } ?>>4 As-Syafie</option>
                                    <option value="5 Ibnu Sina" <?php if($StudentClass == '5 Ibnu Sina') { echo "selected"; } ?>>5 Ibnu Sina</option>
                                    <option value="5 Al-Khawarizmi" <?php if($StudentClass == '5 Al-Khawarizmi') { echo "selected"; } ?>>5 Al-Khawarizmi</option>
                                    <option value="5 As-Syafie" <?php if($StudentClass == '5 As-Syafie') { echo "selected"; } ?>>5 As-Syafie</option>
                                  </select>
                                  </div>


                                  <div class="col-xl-6">
                                    <label class="form-control-label">Select Club<span class="text-danger ml-2">*</span></label>
                                    <select required name="ClubID" class="form-control mb-3">
                                        <option value="">--Select Club--</option>
                                        <?php
                                        $clubQuery = "SELECT * FROM club ORDER BY ClubName ASC";
                                        $clubResult = mysqli_query($conn, $clubQuery);
                                        while ($clubRow = mysqli_fetch_assoc($clubResult)) {
                                            $selected = ($clubRow['ClubID'] == $ClubID) ? "selected" : "";
                                            echo '<option value="' . $clubRow['ClubID'] . '" ' . $selected . '>' . $clubRow['ClubName'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                  </div>

                                  <div class="col-xl-6">
                                  <label class="form-control-label">Select Club Position<span class="text-danger ml-2">*</span></label>
                                  <select name="ClubPosition" class="form-control mb-3">
                                    <option value="">--Select Form Level--</option>
                                    <option value="Pengerusi" <?php if($ClubPosition == 'Pengerusi') { echo "selected"; } ?>>Pengerusi</option>
                                    <option value="Naib Pengerusi" <?php if($ClubPosition == 'Naib Pengerusi') { echo "selected"; } ?>>Naib Pengerusi</option>
                                    <option value="Setiausaha" <?php if($ClubPosition == 'Setiausaha') { echo "selected"; } ?>>Setiausaha</option>
                                    <option value="Penolong Setiausaha" <?php if($ClubPosition == 'Penolong Setiausaha') { echo "selected"; } ?>>Penolong Setiausaha</option>
                                    <option value="Bendahari" <?php if($ClubPosition == 'Bendahari') { echo "selected"; } ?>>Bendahari</option>
                                    <option value="Penolong Bendahari" <?php if($ClubPosition == 'Penolong Bendahari') { echo "selected"; } ?>>Penolong Bendahari</option>
                                    <option value="AJK Tingkatan 5" <?php if($ClubPosition == 'AJK Tingkatan 5') { echo "selected"; } ?>>AJK Tingkatan 5</option>
                                    <option value="AJK Tingkatan 4" <?php if($ClubPosition == 'AJK Tingkatan 4') { echo "selected"; } ?>>AJK Tingkatan 4</option>
                                    <option value="AJK Tingkatan 3" <?php if($ClubPosition == 'AJK Tingkatan 3') { echo "selected"; } ?>>AJK Tingkatan 3</option>
                                    <option value="AJK Tingkatan 2" <?php if($ClubPosition == 'AJK Tingkatan 2') { echo "selected"; } ?>>AJK Tingkatan 2</option>
                                    <option value="AJK Tingkatan 1" <?php if($ClubPosition == 'AJK Tingkatan 1') { echo "selected"; } ?>>AJK Tingkatan 1</option>
                                    <option value="Ahli Aktif" <?php if($ClubPosition == 'Ahli Aktif') { echo "selected"; } ?>>Ahli Aktif</option>
                                    <option value="Ahli Biasa" <?php if($ClubPosition == 'Ahli Biasa') { echo "selected"; } ?>>Ahli Biasa</option>
                                  </select>
                                  </div>

                      <!-- Other form fields remain the same -->

                    </div>
                    <?php if (isset($RegisterStudentID)) { ?>
                      <button type="submit" name="update" class="btn btn-warning">Update</button>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button>
                    <?php } else { ?>
                      <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php } ?>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
                 <div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">All Student Registered</h6>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Club Name</th>
                            <th>Club Type</th>
                            <th>Current Year</th>
                            <th>Class</th>
                            <th>Form Level</th>
                            <th>Club Position</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT registerstudent.RegisterStudentID, club.ClubName, student.StudentName, registerstudent.CurrentYear, registerstudent.StudentClass, registerstudent.StudentFormLevel, registerstudent.ClubPosition, clubtype.ClubTypeName
                            FROM registerstudent
                            INNER JOIN club ON club.ClubID = registerstudent.ClubID
                            INNER JOIN student ON student.StudentID = registerstudent.StudentID
                            INNER JOIN clubtype ON clubtype.ClubTypeID = club.ClubTypeID
                            ORDER BY CurrentYear DESC, StudentName ASC";
                        $rs = $conn->query($query);
                        $num = $rs->num_rows;
                        $sn = 0;
                        if ($num > 0) {
                            while ($rows = $rs->fetch_assoc()) {
                                $sn++;
                                echo "<tr>
                                        <td>".$sn."</td>
                                        <td>".$rows['StudentName']."</td>
                                        <td>".$rows['ClubName']."</td>
                                        <td>".$rows['ClubTypeName']."</td>
                                        <td>".$rows['CurrentYear']."</td>
                                        <td>".$rows['StudentClass']."</td>
                                        <td>".$rows['StudentFormLevel']."</td>
                                        <td>".$rows['ClubPosition']."</td>
                                        <td><a href='?action=edit&RegisterStudentID=".$rows['RegisterStudentID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                        <td><a href='?action=delete&RegisterStudentID=".$rows['RegisterStudentID']."' style='color:red;' onclick='return confirm(\"Do you really want to delete?\");'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr>
                                    <td colspan='5'>No Record Found!</td>
                                  </tr>";
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
      $(document).ready(function () {
        $('#dataTable').DataTable(); // ID From dataTable 
        $('#dataTableHover').DataTable(); // ID From dataTable with Hover

        // Initialize Select2 for the student dropdown
        $('#select-student').select2({
          placeholder: "--Select Student--",
          allowClear: true
        });
      });
    </script>
</body>

</html>