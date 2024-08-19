<?php
error_reporting(E_ALL); // Enable all error reporting
ini_set('display_errors', 1); // Display errors

//session_start();
include('header.php');
include('../include/connection.php');

// Assuming the student ID is stored in the session
// session_start(); // Start the session if not already started
$statusMsg = ""; // Initialize status message

if (isset($_SESSION['stud_id'])) {
    $stud_id = $_SESSION['stud_id'];
    
    // Fetch student's previous institution from the database
    $previousInstitution = '';
    $student_query = "SELECT i.int_id, i.int_name FROM institution i
                      JOIN student s ON i.int_id = s.int_id
                      WHERE s.stud_id = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("i", $stud_id);
    $stmt->execute();
    $stmt->bind_result($int_id, $previousInstitution);
    $stmt->fetch();
    $stmt->close(); 
    
    //------------------------SAVE--------------------------------------------------
    
    if (isset($_POST['save'])) {
        // Get form data
        $code = $_POST['code'];
        $title = $_POST['title'];
        $credit_hour = $_POST['credit_hour'];
        $status = 'Pending';

        // Handle file upload
        $pdf = $_FILES['tplink']['name'];
        $targetDir = "../teachingplan/pending/"; // Directory to save the uploaded file
        $targetFilePath = $targetDir . basename($pdf);
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Check if the file is a PDF
        if($fileType != "pdf"){
            $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Please upload a valid PDF file.</div>";
        } else {
            // Upload file to server
            if(move_uploaded_file($_FILES["tplink"]["tmp_name"], $targetFilePath)){
                // Prepare and bind
                $query = "INSERT INTO new_tp (code, title, credit_hour, tplink, status, int_id, stud_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssissii", $code, $title, $credit_hour, $pdf, $status, $int_id, $stud_id); // Bind int_id

                if ($stmt->execute()) {
                    echo "<meta http-equiv=\"refresh\" content=\"1;URL=view-req-update.php\">"; // Redirect to view-req-update.php after successful insert
                    exit(); // Make sure to exit after redirecting
                } else {
                    $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An error occurred: " . $stmt->error . "</div>";
                }
            } else {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }
    
    // Fetch institution names from the database
    $institutions = [];
    $inst_query = "SELECT int_id, int_name FROM institution";
    $inst_result = $conn->query($inst_query);
    
    if ($inst_result && $inst_result->num_rows > 0) {
        while ($row = $inst_result->fetch_assoc()) {
            $institutions[] = $row;
        }
    }
} else {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Student ID not found in session.</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Request Update</title>
  <style>
    .fixed-text {
      font-weight: bold;
      display: inline-block;
      user-select: none;
    }
  </style>
</head>
<body>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Request Add Teaching Plan</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Teaching Plan</li>
          <li class="breadcrumb-item active">Request Add Teaching Plan</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Request Add Teaching Plan</h5>
              <?php echo $statusMsg;?>

              <!-- Request Update New Teaching Plan -->
              <form class="row g-3" method="post" enctype="multipart/form-data">

                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="code" id="floatingName" placeholder="Course Code" value="" required oninput="this.value = this.value.toUpperCase();" style="text-transform: uppercase;">
                    <label for="floatingName">Course Code<span class="text-danger ml-2"> *</span></label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="title" id="floatingTitle" placeholder="Course Title" value="" required oninput="this.value = this.value.toUpperCase();" style="text-transform: uppercase;">
                    <label for="floatingTitle">Course Title<span class="text-danger ml-2"> *</span></label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="number" class="form-control" name="credit_hour" id="floatingch" placeholder="Credit Hour" value="" required min="0">
                    <label for="floatingch">Credit Hour<span class="text-danger ml-2"> *</span></label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="previousInstitution" id="floatingInstitution" placeholder="Previous Institution" value="<?php echo $previousInstitution; ?>" readonly>
                    <label for="floatingInstitution">Previous Institution<span class="text-danger ml-2"> *</span></label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="file" class="form-control" name="tplink" id="floatingtplink"  required>
                    <label for="floatingtplink">Upload Teaching Plan (pdf only) <span class="text-danger ml-2"> *</span></label>
                  </div>
                </div>

                <div class="text-center">
                  <button type="submit" name="save" class="btn btn-primary">Submit</button>
                  <input type="button" class="btn btn-secondary" onclick="window.location.replace('view-dip-tp.php')" value="Cancel">
                </div>
              </form><!-- End Request Update New Teaching Plan -->

            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('floatingTextarea');
        const fixedText = document.querySelector('.fixed-text').textContent;

        textarea.addEventListener('input', function () {
            if (!textarea.value.includes(fixedText)) {
                textarea.value = fixedText + textarea.value;
            }
        });

        textarea.value = fixedText + " "; // Initialize with fixed text
    });
  </script>

  <?php
include('footer.php');
?>
</body>
</html>
