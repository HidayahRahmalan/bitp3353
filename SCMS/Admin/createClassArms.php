<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
    $ClubTypeID = $_POST['ClubTypeID'];
    $ClubName = $_POST['ClubName'];
   
    $query = mysqli_query($conn, "SELECT * FROM club WHERE ClubTypeID = '$ClubTypeID' AND ClubName ='$ClubName'");
    $ret = mysqli_fetch_array($query);

    if($ret > 0){ 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Club Already Exists!</div>";
    }
    else{
        $query = mysqli_query($conn, "INSERT INTO club (ClubTypeID, ClubName) VALUES ('$ClubTypeID', '$ClubName')");
        if ($query) {
            $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
        }
        else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//--------------------EDIT------------------------------------------------------------

if (isset($_GET['ClubID']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $ClubID = $_GET['ClubID'];

    $query = mysqli_query($conn, "SELECT * FROM club WHERE ClubID = '$ClubID'");
    $row = mysqli_fetch_array($query);
    $ClubTypeID = $row['ClubTypeID'];
    $ClubName = $row['ClubName'];

    //------------UPDATE-----------------------------
    if(isset($_POST['update'])){
        $ClubTypeID = $_POST['ClubTypeID'];
        $ClubName = $_POST['ClubName'];

        $query = mysqli_query($conn, "UPDATE club SET ClubTypeID = '$ClubTypeID', ClubName = '$ClubName' WHERE ClubID = '$ClubID'");
        if ($query) {
            echo "<script type='text/javascript'>
                window.location.href = 'createClassArms.php';
                </script>"; 
        }
        else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//--------------------CANCEL EDIT------------------------------------------------

if (isset($_POST['cancel'])) {
    echo "<script type = \"text/javascript\">
    window.location = (\"createClassArms.php\")
    </script>"; 
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['ClubID']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $ClubID = $_GET['ClubID'];

    $query = mysqli_query($conn, "DELETE FROM club WHERE ClubID = '$ClubID'");
    if ($query == TRUE) {
        echo "<script type='text/javascript'>
                window.location = 'createClassArms.php';
                </script>";  
    }
    else {
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
            <h1 class="h3 mb-0 text-gray-800">Create Club</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Club</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Club</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Select Club Type<span class="text-danger ml-2">*</span></label>
                        <?php
                        $qry = "SELECT * FROM ClubType ORDER BY ClubTypeName ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;   
                        if ($num > 0){
                          echo '<select required name="ClubTypeID" class="form-control mb-3">';
                          echo '<option value="">--Select Club Type--</option>';
                          while ($rows = $result->fetch_assoc()){
                            $selected = ($rows['ClubTypeID'] == $ClubTypeID) ? "selected" : "";
                            echo '<option value="'.$rows['ClubTypeID'].'" '.$selected.'>'.$rows['ClubTypeName'].'</option>';
                          }
                          echo '</select>';
                        }
                        ?>  
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Club Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="ClubName" value="<?php echo isset($ClubName) ? $ClubName : ''; ?>" id="exampleInputClub" placeholder="Club Name" required>
                      </div>
                    </div>
                    <?php
                    if (isset($ClubID)) {
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
                      <h6 class="m-0 font-weight-bold text-primary">All Club</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Club Type Name</th>
                            <th>Club Name</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = "SELECT club.ClubID, clubtype.ClubTypeName, club.ClubName 
                                    FROM club
                                    INNER JOIN clubtype ON clubtype.ClubTypeID = club.ClubTypeID
                                    ORDER BY clubtype.ClubTypeName ASC";
                          $rs = $conn->query($query);
                          $sn = 0;
                          if ($rs && $rs->num_rows > 0) {
                            while ($rows = $rs->fetch_assoc()) {
                              $sn++;
                              echo "<tr>
                                      <td>".$sn."</td>
                                      <td>".$rows['ClubTypeName']."</td>
                                      <td>".$rows['ClubName']."</td>
                                      <td><a href='?action=edit&ClubID=".$rows['ClubID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                      <td><a href='?action=delete&ClubID=".$rows['ClubID']."' style='color:red;' onclick='return confirm(\"Do you really want to delete?\");'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
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
          <!--Row-->
        </div>
        <!---Container Fluid-->
      </div>
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