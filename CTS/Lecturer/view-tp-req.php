<?php
error_reporting();
include('header.php');
include('../include/connection.php');

// Handle status update
if (isset($_POST['req_id']) && isset($_POST['action'])) {
    $requestId = $_POST['req_id'];
    $action = $_POST['action'];

    if ($action == 'accept') {
        $updateQuery = "UPDATE request SET status='Accepted' WHERE req_id=?";
    } elseif ($action == 'reject') {
        $updateQuery = "UPDATE request SET status='Rejected' WHERE req_id=?";
    }

    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $requestId);
    if ($stmt->execute()) {
        if ($action == 'accept') {
            $statusMsg = "<div class='alert alert-success' style='margin-right:9px;'>Request Accepted Successfully!</div>";

        } elseif ($action == 'reject') {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:9px;'>Request Rejected Successfully!</div>";
        }
    } else {
        $statusMsg = "<div class='alert alert-warning style='margin-right:9px;'>An error occurred!</div>";
    }
    $stmt->close();
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
      <li class="breadcrumb-item active">View Request Status</li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<?php if (isset($statusMsg)) echo $statusMsg; ?>


<!-- pending tp req update -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">

<!-- DataTable for list of institutions -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Request Update Status</h5>
        <p>List of teaching plan update status</p>
        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Requester</th>
                    <th>Status</th>
                    <th>View Link</th>
                    <th>Request Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetching data from the database
            $query = "SELECT r.req_id, c.course_code, c.title, r.message, r.status, r.link, r.request_date, s.stud_id, s.name, i.int_id, i.int_name, a.lect_id, a.lect_name
                        FROM request r
                        JOIN course c ON r.course = c.course_id
                        JOIN student s ON r.stud_id = s.stud_id
                        JOIN institution i ON s.stud_id = i.int_id
                        JOIN lecturer a ON s.lect_id = a.lect_id
                        WHERE r.status = 'pending'
                        ORDER BY request_date";
            $rs = $conn->query($query);
            $sn = 0;

            if ($rs && $rs->num_rows > 0) {
                while ($row = $rs->fetch_assoc()) {
                    $sn++;
                    $pdfLink = $row['link'];
                    echo "
                        <tr>
                            <td>".$sn."</td>
                            <td>" .$row['course_code']. '-'.$row['title']. ' <br> <i>('. $row['int_name']. ")</i></td>
                            <td>".$row['message']."</td>
                            <td>".$row['name']. '<br><i>(' .$row['lect_name'].")</i></td>
                            <td>".$row['status']."</td>
                            <td><a href='".$pdfLink."' target='_blank'>View</a></td>
                            <td>".$row['request_date']."</td>
                            <td>
                                <form method='post' onsubmit='return confirmAction(this);'>
                                    <input type='hidden' name='req_id' value='".$row['req_id']."'>
                                    <button type='submit' name='action' value='accept' class='btn btn-success' ".($row['status'] == 'Accepted' ? 'disabled' : '').">Accept</button>
                                    <button type='submit' name='action' value='reject' class='btn btn-danger' ".($row['status'] == 'Rejected' ? 'disabled' : '').">Reject</button>
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

<!-- Accept and Reject Section -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">

<!-- Filter Form and Filtered Requests Table -->
<section class="section">
  <div class="row">
    <div class="col-lg-12">

<!-- Filter Form -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Filter by Status</h5>
        <form method="GET">
            <div class="row">
                <div class="col-md-6">
                    <select name="status_filter" class="form-control">
                        <option value="">All</option>
                        <option value="Accepted" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Accepted') echo 'selected'; ?>>Accepted</option>
                        <option value="Rejected" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Filtered Requests Table -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Request Update Status</h5>
        <p>List of teaching plan update status</p>

        <!-- Table with striped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>View Link</th>
                    <th>Request Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetch the selected status from the filter form
            $statusFilter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

            // Initialize the base query
            $baseQuery = "SELECT r.req_id, c.course_code, c.title, r.message, r.status, r.link, r.request_date 
                         FROM request r
                         JOIN course c ON r.course = c.course_id";

            // Determine the WHERE clause and parameters
            if ($statusFilter === 'Accepted' || $statusFilter === 'Rejected') {
                $whereClause = " WHERE r.status = ?";
                $params = [$statusFilter];
                $types = "s";
            } elseif ($statusFilter === '') {
                // "All" selected: include only "Accepted" and "Rejected" statuses
                $whereClause = " WHERE r.status IN (?, ?)";
                $params = ['Accepted', 'Rejected'];
                $types = "ss";
            } else {
                // Default case: include only "Accepted" and "Rejected" statuses
                $whereClause = " WHERE r.status IN (?, ?)";
                $params = ['Accepted', 'Rejected'];
                $types = "ss";
            }

            // Finalize the query
            $finalQuery = $baseQuery . $whereClause . " ORDER BY r.request_date DESC";

            // Prepare the statement
            $stmt = $conn->prepare($finalQuery);

            if ($stmt) {
                // Bind parameters if necessary
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }

                // Execute the statement
                $stmt->execute();

                // Get the result
                $rs = $stmt->get_result();
                $sn = 0;

                // Process the results
                if ($rs && $rs->num_rows > 0) {
                    while ($row = $rs->fetch_assoc()) {
                        $sn++;
                        $pdfLink = htmlspecialchars($row['link'], ENT_QUOTES, 'UTF-8');
                        echo "
                            <tr>
                                <td>".$sn."</td>
                                <td>" . htmlspecialchars($row['course_code'], ENT_QUOTES, 'UTF-8') . '-' . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "</td>
                                <td>" . htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8') . "</td>
                                <td>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</td>
                                <td><a href='" . $pdfLink . "' target='_blank'>View</a></td>
                                <td>" . htmlspecialchars($row['request_date'], ENT_QUOTES, 'UTF-8') . "</td>
                                <td>
                                    <form method='post' onsubmit='return confirmAction(this);'> 
                                        <input type='hidden' name='req_id' value='" . htmlspecialchars($row['req_id'], ENT_QUOTES, 'UTF-8') . "'>
                                        <button type='submit' name='action' value='accept' class='btn btn-success' " . ($row['status'] == 'Accepted' ? 'disabled' : '') . ">Accept</button>
                                        <button type='submit' name='action' value='reject' class='btn btn-danger' " . ($row['status'] == 'Rejected' ? 'disabled' : '') . ">Reject</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No Record Found!</td></tr>";
                }

                // Close the statement
                $stmt->close();
            } else {
                // Handle query preparation error
                echo "<tr><td colspan='7' class='text-center'>Error preparing the query.</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <!-- End Table with striped rows -->
    </div>
</div>

    </div>
  </div>
</section>

</main><!-- End #main -->

<script>
function confirmAction(form) {
    const action = form.querySelector('button[name="action"][type="submit"]:focus').value;
    const message = action === 'accept' ? 'Are you sure you want to accept this request?' : 'Are you sure you want to reject this request?';
    return confirm(message);
}
</script>

</body>
</html>

<?php
include('footer.php');
?>
