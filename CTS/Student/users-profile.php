<?php
error_reporting();
include('header.php');
include('../include/connection.php');


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
        $query = "SELECT password FROM student WHERE stud_id = " . $_SESSION['stud_id'];
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];

            // Verify the current password
            if (md5($currentPassword) === $storedPassword) {
                // Hash the new password
                $newHashedPassword = md5($newPassword);

                // Update the password in the database
                $updateQuery = "UPDATE student SET password = ? WHERE stud_id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("si", $newHashedPassword, $_SESSION['stud_id']);
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

// Retrieve data SQL
$query = "SELECT s.stud_id, s.name, s.icno, s.faculty, p.prog_name, p.prog_code, s.session, s.email, s.phone, s.latest_int, i.int_name, s.username
          FROM student s
          JOIN programme p ON s.prog_id = p.prog_id
          JOIN institution i ON s.int_id = i.int_id
          WHERE stud_id = " . $_SESSION['stud_id'];
          $rs = $conn->query($query);
          $num = $rs->num_rows;
          $rows = $rs->fetch_assoc();
          $stud_id = $rows['stud_id'];
          $name = $rows['name'];
          $icno = $rows['icno'];
          $faculty = $rows['faculty'];
          $prog_name = $rows['prog_name'];
          $prog_code = $rows['prog_code'];
          $session = $rows['session'];
          $email = $rows['email'];
          $phone = $rows['phone'];
          $latest_int = $rows['latest_int'];
          $username = $rows['username'];
          $int_name = $rows['int_name'];



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
              <img src="../assets/img/graduate.png" alt="Profile" class="rounded-circle">
              <span><b><?php echo $name; ?></b></span>
              <h3>Student</h3>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Display success or error message -->
              <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
              <?php endif; ?>
              <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
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
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo $name; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">ICNO</div>
                    <div class="col-lg-9 col-md-8"><?php echo $icno; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Matric No.</div>
                    <div class="col-lg-9 col-md-8"><?php echo $username; ?></div>
                  </div>


                  <div class="row">
                    <div class="col-lg-3 col-md=4 label">Faculty</div>
                    <div class="col-lg-9 col-md-8"><?php echo $faculty; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Programme</div>
                    <div class="col-lg-9 col-md-8"><?php echo $prog_name . ' (' . $prog_code . ')'; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Session</div>
                    <div class="col-lg-9 col-md-8"><?php echo $session; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?php echo $email; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Phone</div>
                    <div class="col-lg-9 col-md-8"><?php echo $phone; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Institution</div>
                    <div class="col-lg-9 col-md-8"><?php echo $latest_int; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Previous Institution</div>
                    <div class="col-lg-9 col-md-8"><?php echo $int_name; ?></div>
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
