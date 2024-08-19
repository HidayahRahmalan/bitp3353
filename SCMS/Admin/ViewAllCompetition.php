
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------
if(isset($_POST['save'])){
    $ClubID = $_POST['ClubID'];
    $LevelID = $_POST['LevelID'];
    $CompetitionName = $_POST['CompetitionName'];
    $CompetitionDate = date("Y-m-d");
    $CompetitionMark = $_POST['CompetitionMark'];
    $CompetitionAchievement = $_POST['CompetitionAchievement'];

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

//--------------------EDIT------------------------------------------------------------

 if (isset($_GET['CompetitionID']) && isset($_GET['action']) && $_GET['action'] == "edit")
  {
        $CompetitionID= $_GET['CompetitionID'];

        $query=mysqli_query($conn,"select * from competition where CompetitionID ='$CompetitionID'");
        $row=mysqli_fetch_array($query);

        //------------UPDATE-----------------------------

        if(isset($_POST['update'])){
   
          $ClubID = $_POST['ClubID'];
          $LevelID = $_POST['LevelID'];
          $CompetitionName = $_POST['CompetitionName'];
          $CompetitionDate = date("Y-m-d");
          $CompetitionMark   = $_POST['CompetitionMark  '];
          $CompetitionAchievement = $_POST['CompetitionAchievement'];

            $query=mysqli_query($conn,"update competition set ClubID = '$ClubID', LevelID = '$LevelID', CompetitionName = '$CompetitionName', CompetitionMark = '$CompetitionMark', CompetitionAchievement = '$CompetitionAchievement' where CompetitionID='$CompetitionID'");

            if ($query) {
                
                echo "<script type = \"text/javascript\">
                window.location = (\"ViewAllCompetition.php\")
                </script>"; 
            }
            else
            {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
            }
        }
    }


//--------------------------------DELETE------------------------------------------------------------------

  if (isset($_GET['CompetitionID']) && isset($_GET['action']) && $_GET['action'] == "delete")
  {
        $CompetitionID= $_GET['CompetitionID'];

        $query = mysqli_query($conn,"DELETE FROM competition WHERE CompetitionID='$CompetitionID'");

        if ($query == TRUE) {

                echo "<script type = \"text/javascript\">
                window.location = (\"ViewAllCompetition.php\")
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
            <h1 class="h3 mb-0 text-gray-800">View Competition</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Competition</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">

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
                            <th>Student List</th>
<!--                             <th>Edit</th>
                            <th>Delete</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT competition.CompetitionID, club.ClubName, level.LevelType, competition.CompetitionName, competition.CompetitionDate, competition.CompetitionMark, competition.CompetitionAchievement 
                                  FROM competition
                                  INNER JOIN club ON club.ClubID = competition.ClubID
                                  INNER JOIN level ON level.LevelID = competition.LevelID
                                  ORDER BY STR_TO_DATE(competition.CompetitionDate, '%d-%m-%Y') DESC";
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
                                        <td><a href='ViewCompetitionStudent.php?CompetitionID=".$rows['CompetitionID']."' id='CompetitionStudent'>View Students</a></td>
                                      </tr>";
                                      // <td><a href='?action=edit&CompetitionID=".$rows['CompetitionID']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                      // <td><a href='?action=delete&CompetitionID=".$rows['CompetitionID']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                            }
                        } else {
                            echo "<tr>
                                    <td colspan='10'>No Record Found!</td>
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
          <!--Row-->

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

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