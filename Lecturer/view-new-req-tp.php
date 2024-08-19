<?php
error_reporting(E_ALL);
include('header.php');
include('../include/connection.php');

// Handle status update and course insertion
if (isset($_POST['tp_id']) && isset($_POST['action'])) {
    $new_tpId = $_POST['tp_id'];
    $action = $_POST['action'];

    if ($action == 'accept') {
        // Start transaction
        $conn->begin_transaction();

        // Fetch data from new_tp to insert into course
        $fetchQuery = "SELECT code, title, credit_hour, tplink, int_id FROM new_tp WHERE tp_id = ?";
        $stmtFetch = $conn->prepare($fetchQuery);
        $stmtFetch->bind_param("i", $new_tpId);
        $stmtFetch->execute();
        $stmtFetch->bind_result($code, $title, $credit_hour, $tplink, $int_id);
        $stmtFetch->fetch();
        $stmtFetch->close();

        // Insert into course table
        $insertQuery = "INSERT INTO course (course_code, title, credit_hour, type, tpfile, int_id) VALUES (?, ?, ?, 'Diploma', ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param("ssssi", $code, $title, $credit_hour, $tplink, $int_id);
        
        if ($stmtInsert->execute()) {
            // Move the file from pending to teachingplan folder
            $sourcePath = "../teachingplan/pending/" . $tplink;
            $destinationPath = "../teachingplan/" . $tplink;

            if (rename($sourcePath, $destinationPath)) {
                // Update the status in new_tp table to 'Accepted'
                $updateQuery = "UPDATE new_tp 
                                SET status = 'Accepted', 
                                    review_date =  CURDATE()
                                    WHERE tp_id = ?";
                $stmtUpdate = $conn->prepare($updateQuery);
                $stmtUpdate->bind_param("i", $new_tpId);
                
                if ($stmtUpdate->execute()) {
                    // Commit transaction
                    $conn->commit();
                    $statusMsg = "<div class='alert alert-success' style='margin-right:9px;'>New Teaching Plan Accepted Successfully, Added to Courses, and File Moved!</div>";
                } else {
                    // Rollback transaction
                    $conn->rollback();
                    $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Failed to update status in new_tp!</div>";
                }
                $stmtUpdate->close();
            } else {
                // Rollback transaction if file move fails
                $conn->rollback();
                $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Failed to move the file!</div>";
            }
        } else {
            // Rollback transaction
            $conn->rollback();
            $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Failed to insert into course table!</div>";
        }
        $stmtInsert->close();
    } elseif ($action == 'reject') {
        // Update the status in new_tp table to 'Rejected'
        $updateQuery = "UPDATE new_tp 
                        SET status = 'Rejected', 
                        review_date =  CURDATE()
                        WHERE tp_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $new_tpId);
        if ($stmt->execute()) {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>New Teaching Plan Rejected Successfully!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>An error occurred!</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<main id="main" class="main">

<div class="pagetitle">
  <h1>Teaching Plan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Teaching Plan</li>
      <li class="breadcrumb-item active">View Teaching Plan Addition Status</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- pending -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">

<!-- Pending and Rejected Teaching Plan -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">New Teaching Plan Pending Status</h5>
        <p>List of Pending Teaching Plan Addition Status</p>
        <?php if (isset($statusMsg)) echo $statusMsg; ?>
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Course</th>
                    <th>Credit Hour</th>
                    <th>Institution</th>
                    <th>Status</th>
                    <th>View Link</th>
                    <th>Date Request</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetching data from the database
            $query = "SELECT r.tp_id, r.code, r.title, r.credit_hour, r.status, r.tplink, r.date, i.int_name
                      FROM new_tp r
                      JOIN institution i ON r.int_id = i.int_id
                      WHERE status = 'Pending'
                      ORDER BY r.date DESC";
            $rs = $conn->query($query);
            $sn = 0;

            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    $pdfLink = "../teachingplan/pending/" .$row['tplink'];
                    $formattedDate = date('d/m/Y', strtotime($row['date']));
                    echo "
                        <tr>
                            <td>".$sn."</td>
                            <td>".$row['code'].'-'.$row['title']."</td>
                            <td>".$row['credit_hour']."</td>
                            <td>".$row['int_name']."</td>
                            <td>".$row['status']."</td>
                            <td><a href=\"".$pdfLink."\" target='_blank'>View</a></td> 
                            <td>".$formattedDate."</td>
                            <td>
                                <form method='post'>
                                    <input type='hidden' name='tp_id' value='".$row['tp_id']."'>
                                    <button type='submit' name='action' value='accept' class='btn btn-success' ".($row['status'] == 'Accepted' ? 'disabled' : '').">Accept and Upload</button>
                                    <button type='submit' name='action' value='reject' class='btn btn-danger' ".($row['status'] == 'Rejected' ? 'disabled' : '').">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reject &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                </form>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No Record Found!</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <!-- End Table with striped rows -->
    </div>
</div>

        </div>
      </div>
    </div>
  </div>

</section>

<!-- accept and reject -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">

<!-- Accepted and rejected -->
<!-- Filter Form -->
<?php
// Initialize $filterStatus with a default value
$filterStatus = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

// Fetch data based on the filter status
$query = "SELECT r.tp_id, r.code, r.title, r.credit_hour, r.status, r.tplink, r.date, r.review_date, i.int_name
          FROM new_tp r
          JOIN institution i ON r.int_id = i.int_id
          WHERE r.status = '$filterStatus' OR '$filterStatus' = ''
          ORDER BY r.date";
$rs = $conn->query($query);
$sn = 0;
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Filter by Status</h5>
        <form method="GET">
            <div class="row">
                <div class="col-md-6">
                    <select name="status_filter" class="form-control">
                        <option value="">All</option>
                        <option value="Accepted" <?php if ($filterStatus == 'Accepted') echo 'selected'; ?>>Accepted</option>
                        <option value="Rejected" <?php if ($filterStatus == 'Rejected') echo 'selected'; ?>>Rejected</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?php echo ucfirst($filterStatus) ?: 'New'; ?> Teaching Plan</h5>
        <p>List of <?php echo ucfirst($filterStatus) ?: 'New'; ?> Teaching Plans</p>
        
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Course</th>
                    <th>Credit Hour</th>
                    <th>Institution</th>
                    <th>Status</th>
                    <th>View Link</th>
                    <th>Request Date</th>
                    <?php if ($filterStatus == 'Accepted'): ?>
                        <th>Accepted Date</th>
                    <?php elseif ($filterStatus == 'Rejected'): ?>
                        <th>Rejected Date</th>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    $pdfLink = "../teachingplan/" . $row['tplink'];
                    $formattedDate = date('d/m/Y', strtotime($row['date']));
                    $formattedRDate = date('d/m/Y', strtotime($row['review_date']));

                    echo "
                        <tr>
                            <td>".$sn."</td>
                            <td>".$row['code'].'-'.$row['title']."</td>
                            <td>".$row['credit_hour']."</td>
                            <td>".$row['int_name']."</td>
                            <td>".$row['status']."</td>
                            <td><a href=\"".$pdfLink."\" target='_blank'>View</a></td>
                            <td>".$formattedDate."</td>";

                    // Display the appropriate date and action buttons based on the filter status
                    if ($filterStatus == 'Accepted') {
                        echo "<td>".$formattedRDate."</td>";
                    } elseif ($filterStatus == 'Rejected') {
                        echo "<td>".$formattedRDate."</td>
                              <td>
                                <form method='post'>
                                    <input type='hidden' name='tp_id' value='".$row['tp_id']."'>
                                    <button type='submit' name='action' value='accept' class='btn btn-success'>Accept and Upload</button>
                                    <button type='submit' name='action' value='reject' class='btn btn-danger'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reject &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                </form>
                                </form>
                              </td>";
                    }

                    echo "</tr>";
                }
            } else {
                $colspan = $filterStatus == 'Accepted' ? '8' : '9';
                echo "<tr><td colspan='".$colspan."' class='text-center'>No Record Found!</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <!-- End Table with striped rows -->
    </div>
</div>
        </div>
      </div>
    </div>
  </div>

</section>
</main><!-- End #main -->
</body>
</html>

<?php
include('footer.php');
?>
