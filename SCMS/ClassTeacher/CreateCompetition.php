<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------
if(isset($_POST['save'])){
    $ClubID = $_POST['ClubID'];
    $LevelID = $_POST['LevelID'];
    $CompetitionName = $_POST['CompetitionName'];
    $CompetitionDate = $_POST['CompetitionDate']; // Date input from form
    $CompetitionMark = $_POST['CompetitionMark'];
    $CompetitionAchievement = $_POST['CompetitionAchievement'];


    // Convert CompetitionDate from d-m-Y to Y-m-d for database storage
    $CompetitionDate = date('d-m-Y', strtotime(str_replace('/', '-', $CompetitionDate)));

    $checkQuery = "SELECT * FROM competition WHERE ClubID = '$ClubID' AND LevelID = '$LevelID' AND CompetitionName = '$CompetitionName'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if(mysqli_num_rows($checkResult) > 0){ 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Competition Already Exists!</div>";
    } else {
        // Insert the data into the database
        $insertQuery = "INSERT INTO competition (ClubID, LevelID, CompetitionName, CompetitionDate, CompetitionMark, CompetitionAchievement) 
                        VALUES ('$ClubID', '$LevelID', '$CompetitionName', '$CompetitionDate', '$CompetitionMark', '$CompetitionAchievement')";
        if(mysqli_query($conn, $insertQuery)){
            $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}

$teacherID = $_SESSION['userId'];
$clubQuery = "SELECT ClubID FROM registerteacher WHERE TeacherID = '$teacherID'";
$clubResult = mysqli_query($conn, $clubQuery);
$clubRow = mysqli_fetch_assoc($clubResult);
$teacherClubID = $clubRow['ClubID'];

//---------------------------------------EDIT-------------------------------------------------------------

$editMode = false;
$CompetitionID = null;
$competition = null;
if (isset($_GET['CompetitionID']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $CompetitionID= $_GET['CompetitionID'];
    $editMode = true;

    $query=mysqli_query($conn,"SELECT * FROM competition WHERE CompetitionID ='$CompetitionID'");
    $competition=mysqli_fetch_array($query);
}

//--------------------UPDATE------------------------------------------------------------

if (isset($_POST['update'])) {
    $CompetitionID = $_POST['CompetitionID'];
    $ClubID = $_POST['ClubID'];
    $LevelID = $_POST['LevelID'];
    $CompetitionName = $_POST['CompetitionName'];
    $CompetitionDate = $_POST['CompetitionDate']; // Date input from form
    $CompetitionMark = $_POST['CompetitionMark'];
    $CompetitionAchievement = $_POST['CompetitionAchievement'];

    // Convert CompetitionDate from d-m-Y to Y-m-d for database storage
    $CompetitionDate = date('d-m-Y', strtotime(str_replace('/', '-', $CompetitionDate)));

    $query = mysqli_query($conn, "UPDATE competition SET ClubID = '$ClubID', LevelID = '$LevelID', CompetitionName = '$CompetitionName', CompetitionDate = '$CompetitionDate', CompetitionMark = '$CompetitionMark', CompetitionAchievement = '$CompetitionAchievement' WHERE CompetitionID='$CompetitionID'");

    if ($query) {
        echo "<script type=\"text/javascript\">
        window.location = (\"CreateCompetition.php\")
        </script>"; 
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
}

//--------------------CANCEL EDIT------------------------------------------------

if (isset($_POST['cancel'])) {
    echo "<script type = \"text/javascript\">
    window.location = (\"CreateCompetition.php\")
    </script>"; 
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['CompetitionID']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $CompetitionID= $_GET['CompetitionID'];

    $query = mysqli_query($conn,"DELETE FROM competition WHERE CompetitionID='$CompetitionID'");

    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">
        window.location = (\"CreateCompetition.php\")
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
            <h1 class="h3 mb-0 text-gray-800">Create Competition</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Competition</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Competition</h6>
                    <?php echo $statusMsg; ?>
                </div>
               <div class="card-body">
    <form method="post">
        <div class="form-group row mb-3">
            <div class="col-xl-6">
                <label class="form-control-label">Select Competition Level<span class="text-danger ml-2">*</span></label>
                <select required name="LevelID" class="form-control mb-3">
                    <option value="">--Select Level--</option>
                    <?php
                    $qry = "SELECT * FROM level ORDER BY LevelType ASC";
                    $result = mysqli_query($conn, $qry);
                    while ($row = mysqli_fetch_assoc($result)){
                        $selected = ($editMode && $competition['LevelID'] == $row['LevelID']) ? 'selected' : '';
                        echo '<option value="'.$row['LevelID'].'" '.$selected.'>'.$row['LevelType'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-xl-6">
                <label class="form-control-label">Select Club<span class="text-danger ml-2">*</span></label>
                <select required name="ClubID" class="form-control mb-3">
                    <?php
                    // Retrieve club names from the Club table based on the teacher's club ID
                    $clubQuery = "SELECT * FROM Club WHERE ClubID = '$teacherClubID' ORDER BY ClubName ASC";
                    $clubResult = mysqli_query($conn, $clubQuery);
                    while ($clubRow = mysqli_fetch_assoc($clubResult)){
                        $selected = ($editMode && $competition['ClubID'] == $clubRow['ClubID']) ? 'selected' : '';
                        echo '<option value="'.$clubRow['ClubID'].'" '.$selected.'>'.$clubRow['ClubName'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-xl-6">
                <label class="form-control-label">Competition Name<span class="text-danger ml-2">*</span></label>
                <input type="text" class="form-control" name="CompetitionName" value="<?php echo $editMode ? $competition['CompetitionName'] : ''; ?>" id="exampleInputCompetitionName" placeholder="Competition Name">
            </div>
            <div class="col-xl-6">
                <label class="form-control-label">Competition Mark<span class="text-danger ml-2">*</span></label>
                <input type="text" class="form-control" name="CompetitionMark" value="<?php echo $editMode ? $competition['CompetitionMark'] : ''; ?>" id="exampleInputCompetitionMark" placeholder="Competition Mark" pattern="\d{1,100}" title="Mark must be a numeric value between 1 and 100" minlength="1" maxlength="3">
            </div>
            <div class="col-xl-6">
                <label class="form-control-label"> Competition Achievement<span class="text-danger ml-2"></span></label>
                <input type="text" class="form-control" name="CompetitionAchievement" value="<?php echo $editMode ? $competition['CompetitionAchievement'] : ''; ?>" id="exampleInputCompetitionAchievement" placeholder="Competition Achievement">
            </div>
            <div class="col-xl-6">
                    <label class="form-control-label">Competition Date<span class="text-danger ml-2">*</span></label>
                    <input type="date" class="form-control" name="CompetitionDate" id="exampleInputCompetitionDate" required value="<?php echo $CompetitionDate; ?>">
                </div>
        </div>
        <?php if ($editMode) { ?>
            <input type="hidden" name="CompetitionID" value="<?php echo $CompetitionID; ?>">
            <button type="submit" name="update" class="btn btn-warning">Update</button>
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
                <h6 class="m-0 font-weight-bold text-primary">All Competition</h6>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Competition Name</th>
                            <th>Date</th>
                            <th>Mark</th>
                            <th>Achievement</th>
                            <th>Level</th>
                            <th>Club</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch the teacher's ClubID
                    $teacherID = $_SESSION['userId'];
                    $query = "SELECT club.ClubID 
                              FROM registerteacher
                              INNER JOIN club ON club.ClubID = registerteacher.ClubID
                              WHERE registerteacher.TeacherID = '$teacherID'";
                    $rs = mysqli_query($conn, $query);
                    $rrw = mysqli_fetch_assoc($rs);
                    $teacherClubID = $rrw['ClubID'];

                        $query = "SELECT competition.CompetitionID, club.ClubName, level.LevelType, competition.CompetitionName, competition.CompetitionDate, competition.CompetitionMark, competition.CompetitionAchievement 
                            FROM competition
                            INNER JOIN club ON club.ClubID = competition.ClubID
                            INNER JOIN level ON level.LevelID = competition.LevelID
                            WHERE competition.ClubID = '$teacherClubID'";
                        $rs = $conn->query($query);
                        $num = $rs->num_rows;
                        $sn = 0;
                        if ($num > 0) {
                            while ($rows = $rs->fetch_assoc()) {
                                $sn++;
                                echo "<tr>
                                        <td>".$sn."</td>
                                        <td>".$rows['CompetitionName']."</td>
                                        <td>".$rows['CompetitionDate']."</td>
                                        <td>".$rows['CompetitionMark']."</td>
                                        <td>".$rows['CompetitionAchievement']."</td>
                                        <td>".$rows['LevelType']."</td>
                                        <td>".$rows['ClubName']."</td>
                                        <td><a href='?action=edit&CompetitionID=".$rows['CompetitionID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                        <td><a href='?action=delete&CompetitionID=".$rows['CompetitionID']."' style='color:red;' onclick='return confirm(\"Do you really want to delete?\");'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr>
                                    <td colspan='9'>No Record Found!</td>
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
