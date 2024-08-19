<?php
error_reporting(E_ALL);
include('header.php');
include('../include/connection.php');

// Check if the user is logged in and the session variable is set
if (!isset($_SESSION['lect_id'])) {
    // Redirect to login page or show an error
    header('Location: login.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form input
    $currentPassword = $_POST['password'];
    $newPassword = $_POST['newpassword'];
    $renewPassword = $_POST['renewpassword'];

    // Validate form input
    if (empty($currentPassword) || empty($newPassword) || empty($renewPassword)) {
        $error = "All fields are required.";
    } elseif ($newPassword !== $renewPassword) {
        $error = "New passwords do not match.";
    } else {
        // Fetch the current password from the database
        $query = "SELECT password FROM lecturer WHERE lect_id = " . $_SESSION['lect_id'];
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];

            // Verify the current password
            if (md5($currentPassword) === $storedPassword) {
                // Hash the new password
                $newHashedPassword = md5($newPassword);

                // Update the password in the database
                $updateQuery = "UPDATE lecturer SET password = ? WHERE lect_id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("si", $newHashedPassword, $_SESSION['lect_id']);
                if ($stmt->execute()) {
                    $success = "Password changed successfully.";
                } else {
                    $error = "Error updating password.";
                }
                $stmt->close();
            } else {
                $error = "Current password is incorrect.";
            }
        } else {
            $error = "User not found.";
        }
    }
}

// Retrieve lecturer data
$lect_id = $_SESSION['lect_id'];
$query = "SELECT lect_id, lect_name, phoneno, email, username, role FROM lecturer WHERE lect_id = '$lect_id'";
$rs = $conn->query($query);
if ($rs && $rs->num_rows > 0) {
    $rows = $rs->fetch_assoc();
    $lect_id = $rows['lect_id'];
    $name = $rows['lect_name'];
    $email = $rows['email'];
    $phone = $rows['phoneno'];
    $username = $rows['username'];
    $role = $rows['role'];

} else {
    // Handle case where lecturer data is not found
    $error = "Lecturer data not found.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Users / Profile - Credit Transfer System</title>
</head>

<body>

  <main id="main" class="main">
    
    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              <img src="../assets/img/school.png" alt="Profile" class="rounded-circle">
              <span><b><?php echo htmlspecialchars($name); ?></b></span>
              <h3>lecturer</h3>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Display success or error message -->
              <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
              <?php endif; ?>
              <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
              <?php endif; ?>
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">Profile Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($name); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Staff No.</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($username); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($email); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Phone</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($phone); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Role</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($role); ?></div>
                  </div>

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="post">

                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="password" type="password" class="form-control" id="currentPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php
    include('footer.php');
  ?>
</body>

</html>
