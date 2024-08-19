<?php
error_reporting(E_ALL); // Enable all error reporting
ini_set('display_errors', 1); // Display errors

include('header.php');
include('../include/connection.php');

//session_start(); // Start the session if not already started
$statusMsg = ""; // Initialize status message

$course_id = $_REQUEST['requestid'];

// Check if course_id exists in the course table
$sqld = "SELECT * FROM course WHERE course_id = ?";
$stmt = $conn->prepare($sqld);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$resultd = $stmt->get_result();

if ($resultd->num_rows === 0) {
    die("Error: Course ID does not exist.");
}

$rowd = $resultd->fetch_assoc();

$course_id = $rowd['course_id'];
$course_code = $rowd['course_code'];
$title = $rowd['title'];

//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {
    // Get form data
    $course = $_POST['course'];
    $message = $_POST['message'];
    $link = $_POST['link'];
    $status = 'Pending';
    
    // Use the student ID from the session
    $stud_id = $_SESSION['stud_id'];

    // Prepare and bind
    $query = "INSERT INTO request (course, message, link, status, stud_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $course_id, $message, $link, $status, $stud_id);

    if ($stmt->execute()) {
      echo "<meta http-equiv=\"refresh\" content=\"1;URL=view-req-update.php\">"; // Redirect to view-req-update.php after successful insert
        exit(); // Make sure to exit after redirecting
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An error occurred: " . $stmt->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Request Update</title>
</head>

<body>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Form Layouts</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Teaching Plan</li>
          <li class="breadcrumb-item active">Request Update</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Request Update New Teaching Plan</h5>
              <?php echo $statusMsg;?>

              <!-- Request Update New Teaching Plan -->
              <form class="row g-3" method="post">

                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="course" id="floatingName" placeholder="course" value="<?php echo $course_code .' - '. htmlspecialchars($title) ?>" readonly>
                    <label for="floatingName">Subject<span class="text-danger ml-2"> *</span></label>
                  </div>
                </div>

                <div class="col-12 mt-2">
                    <label>Choose the reason: <span class="text-danger ml-2"> *</span></label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Update TP to the latest version" id="option1">
                        <label class="form-check-label" for="option1">Update TP to the latest version</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Update course code" id="option1">
                        <label class="form-check-label" for="option1">Update course code</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Update course name" id="option1">
                        <label class="form-check-label" for="option1">Update course name</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Wrong Teaching Plan" id="option2">
                        <label class="form-check-label" for="option2">Wrong Teaching Plan</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Other:" id="option3">
                        <label class="form-check-label" for="option3">Other Reason</label>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating">
                        <textarea class="form-control" name="message" placeholder="You can add other message" id="floatingTextarea" style="height: 100px;" required></textarea>
                        <label for="floatingTextarea">Add other message <span class="text-danger ml-2"> *</span></label>
                    </div>
                </div>

                <div class="col-md-4">
                    <p>Please Upload the Teaching Plan here <span class="text-danger ml-2"> *</span></p>
                    <a href="https://drive.google.com/drive/folders/1oFxNDwNUN7dW05DdazvgJdpu6B-50txp?usp=drive_link" target="_blank">Link to Google Drive Folder</a>
                </div>

                <div class="col-md-8">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="link" id="floatingLink" placeholder="Google drive link" required>
                    <label for="floatingLink">Submit TP Google Drive link here <span class="text-danger ml-2"> *</span></label>
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
    const checkboxes = document.querySelectorAll('.form-check-input');
    const textarea = document.getElementById('floatingTextarea');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const selectedOptions = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            textarea.value = selectedOptions.join(', ');
        });
    });
});
  </script>

<?php
include('footer.php');
?>

</body>
</html>
