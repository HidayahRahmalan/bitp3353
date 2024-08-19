<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

$selectedYear = $_POST['year'] ?? null;
$selectedClass = $_POST['class'] ?? null;
$StudentID = $_POST['StudentID'] ?? null;

// Fetch Years and Classes
$years = [];
$classes = [];

// Fetch Years
$yearQuery = "SELECT DISTINCT r.CurrentYear FROM registerstudent r ORDER BY r.CurrentYear";
$yearResult = mysqli_query($conn, $yearQuery);
if ($yearResult) {
    while ($yearRow = mysqli_fetch_assoc($yearResult)) {
        $years[] = $yearRow['CurrentYear'];
    }
} else {
    echo "Error fetching years: " . mysqli_error($conn);
}

// Fetch Classes based on selected Year
if ($selectedYear) {
    $classQuery = "SELECT DISTINCT r.StudentClass FROM registerstudent r WHERE r.CurrentYear = '$selectedYear'";
    $classResult = mysqli_query($conn, $classQuery);
    if ($classResult) {
        while ($classRow = mysqli_fetch_assoc($classResult)) {
            $classes[] = $classRow['StudentClass'];
        }
    } else {
        echo "Error fetching classes: " . mysqli_error($conn);
    }
}

// Fetch Students based on Year and Class filters
$students = [];
$studentQuery = "SELECT DISTINCT s.StudentID, s.StudentName 
                 FROM student s
                 JOIN registerstudent r ON s.StudentID = r.StudentID
                 WHERE 1=1";

if ($selectedYear) {
    $studentQuery .= " AND r.CurrentYear = '$selectedYear'";
}
if ($selectedClass) {
    $studentQuery .= " AND r.StudentClass = '$selectedClass'";
}

$studentQuery .= " ORDER BY s.StudentName ASC";
$studentResult = mysqli_query($conn, $studentQuery);
if ($studentResult) {
    while ($studentRow = mysqli_fetch_assoc($studentResult)) {
        $students[] = $studentRow;
    }
} else {
    echo "Error fetching students: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="img/logo/attnlg.jpg" rel="icon">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .printable, .printable * {
                visibility: visible;
            }
            .printable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .printable-table {
                width: 100%;
                border-collapse: collapse;
            }
            .printable-table th, .printable-table td {
                border: 1px solid #ddd;
                padding: 8px;
            }
            .printable-table th {
                background-color: #f2f2f2;
            }
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "Includes/sidebar.php";?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "Includes/topbar.php";?>
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">View Students Report</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View Students Report</li>
                        </ol>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">View Students Report</h6>
                                </div>
                                <div class="card-body">
                                    <form action="" method="post">
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-4">
                                                <label class="form-control-label">Select Year<span class="text-danger ml-2">*</span></label>
                                                <select name="year" class="form-control mb-3" onchange="this.form.submit()">
                                                    <option value="">--Select Year--</option>
                                                    <?php foreach ($years as $year): ?>
                                                        <option value="<?php echo $year; ?>" <?php if ($year == $selectedYear) echo "selected"; ?>>
                                                            <?php echo $year; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-4">
                                                <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                                                <select name="class" class="form-control mb-3" onchange="this.form.submit()">
                                                    <option value="">--Select Class--</option>
                                                    <?php foreach ($classes as $class): ?>
                                                        <option value="<?php echo $class; ?>" <?php if ($class == $selectedClass) echo "selected"; ?>>
                                                            <?php echo $class; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-4">
                                                <label class="form-control-label">Select Student<span class="text-danger ml-2">*</span></label>
                                                <select name="StudentID" class="form-control mb-3">
                                                    <option value="">--Select Student--</option>
                                                    <?php foreach ($students as $student): ?>
                                                        <option value="<?php echo $student['StudentID']; ?>" <?php if ($student['StudentID'] == $StudentID) echo "selected"; ?>>
                                                            <?php echo $student['StudentName']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" name="search" class="btn btn-primary mt-4">Search</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Report Table -->
                            <?php if (isset($_POST['search'])): ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div class="table-responsive p-3 printable">
                                            <h1 class="h3 mb-0 text-gray-800">Student Co-Curriculum Report</h1>
                                            <h6 class="h3 mb-0 text-gray-800">Sekolah Menengah Arab Assaiyidah Khadijah</h6>
                                            <h6 class="m-0 font-weight-bold text-primary">
                                                <?php
                                                if ($StudentID) {
                                                    // Fetch Student Name
                                                    $query = "SELECT StudentName FROM student WHERE StudentID = ?";
                                                    $stmt = $conn->prepare($query);
                                                    $stmt->bind_param("i", $StudentID);
                                                    $stmt->execute();
                                                    $stmt->bind_result($studentName);
                                                    $stmt->fetch();
                                                    echo $studentName;
                                                    $stmt->close();
                                                }
                                                ?>
                                            </h6>
                                            <br></br>

<?php
// Build query based on filters and total marks
$sql = "SELECT r.RegisterStudentID, s.StudentName, s.StudentIc, r.StudentClass, r.CurrentYear, 
   GROUP_CONCAT(DISTINCT CONCAT('<b>', ct.ClubTypeName, '</b>: ', cl.ClubName) SEPARATOR '. ') AS Clubs,
   r.ClubPosition, 
   SUM(DISTINCT r.TotalMark) AS TotalMark,  -- Summing distinct TotalMark for same StudentID, CurrentYear, different ClubID
   r.Grade, 
   GROUP_CONCAT(DISTINCT CONCAT('<b>', clubTypeConcat.ClubTypeName, '</b>: ', clubTypeConcat.Competitions) SEPARATOR '. ') AS Competitions,
   GROUP_CONCAT(DISTINCT CONCAT('<b>', typeConcat.ClubTypeName, '</b>: ', typeConcat.Activities) SEPARATOR '. ') AS Activities
FROM registerstudent r
JOIN student s ON r.StudentID = s.StudentID
JOIN club cl ON r.ClubID = cl.ClubID
JOIN clubtype ct ON cl.ClubTypeID = ct.ClubTypeID
LEFT JOIN attendance a ON r.RegisterStudentID = a.RegisterStudentID
LEFT JOIN competition c ON c.CompetitionID = a.CompetitionID
LEFT JOIN activity ac ON ac.ActivityID = a.ActivityID
LEFT JOIN (
    SELECT ct.ClubTypeName, 
           GROUP_CONCAT(c.CompetitionName SEPARATOR ', ') AS Competitions,
           r.RegisterStudentID
    FROM registerstudent r
    JOIN club cl ON r.ClubID = cl.ClubID
    JOIN clubtype ct ON cl.ClubTypeID = ct.ClubTypeID
    LEFT JOIN attendance a ON r.RegisterStudentID = a.RegisterStudentID
    LEFT JOIN competition c ON c.CompetitionID = a.CompetitionID
    WHERE 1=1
    GROUP BY ct.ClubTypeName, r.RegisterStudentID
) clubTypeConcat ON r.RegisterStudentID = clubTypeConcat.RegisterStudentID
LEFT JOIN (
    SELECT at.ClubTypeName, 
           GROUP_CONCAT(ac.ActivityName SEPARATOR ', ') AS Activities,
           r.RegisterStudentID
    FROM registerstudent r
    JOIN club cl ON r.ClubID = cl.ClubID
    JOIN clubType at ON cl.ClubTypeID = at.ClubTypeID
    LEFT JOIN attendance a ON r.RegisterStudentID = a.RegisterStudentID
    JOIN activity ac ON ac.ActivityID = a.ActivityID
    WHERE 1=1
    GROUP BY at.ClubTypeName, r.RegisterStudentID
) typeConcat ON r.RegisterStudentID = typeConcat.RegisterStudentID
WHERE 1=1";

if ($selectedYear) {
    $sql .= " AND r.CurrentYear = '$selectedYear'";
}
if ($selectedClass) {
    $sql .= " AND r.StudentClass = '$selectedClass'";
}
if ($StudentID) {
    $sql .= " AND r.StudentID = '$StudentID'";
}

$sql .= " GROUP BY r.StudentID, r.CurrentYear, r.StudentClass
          ORDER BY r.CurrentYear DESC, r.StudentClass ASC, s.StudentName ASC";

$result = mysqli_query($conn, $sql);
$registerStudent = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Perform the final calculation for total marks
        $adjustedTotalMark = round(($row['TotalMark'] / 300) * 100);  // Round to whole number
        $row['TotalMark'] = $adjustedTotalMark;

        // Determine the grade based on the adjusted total mark
        if ($adjustedTotalMark >= 80) {
            $row['Grade'] = 'A';
        } elseif ($adjustedTotalMark >= 65) {
            $row['Grade'] = 'B';
        } elseif ($adjustedTotalMark >= 50) {
            $row['Grade'] = 'C';
        } elseif ($adjustedTotalMark >= 35) {
            $row['Grade'] = 'D';
        } else {
            $row['Grade'] = 'E';
        }

        $registerStudent[$row['RegisterStudentID']] = $row;
    }
} else {
    echo "Error fetching data: " . mysqli_error($conn);
}
?>


<table id="dataTable" class="table table-striped table-bordered"> 
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Student IC</th>
            <th>Year</th>
            <th>Club</th>
            <th>Class</th>
            <th>Club Position</th>
            <th>Activities</th>
            <th>Competitions</th>
            <th>Total Mark</th>
            <th>Grade</th>
            <th>Print</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($registerStudent) > 0): ?>
            <?php foreach ($registerStudent as $registerStudentID => $row): ?>
            <tr id="row-<?php echo $registerStudentID; ?>">
                <td><?= htmlspecialchars($row['StudentName']) ?></td>
                <td><?= htmlspecialchars($row['StudentIc']) ?></td>
                <td><?= htmlspecialchars($row['CurrentYear']) ?></td>
                <td><?= $row['Clubs'] ?></td>
                <td><?= htmlspecialchars($row['StudentClass']) ?></td>
                <td><?= htmlspecialchars($row['ClubPosition']) ?></td>
                <td><?= $row['Activities'] ?></td>
                <td><?= $row['Competitions'] ?></td>
                <td><?= $row['TotalMark'] ?></td>
                <td><?= htmlspecialchars($row['Grade']) ?></td>
                <td><button type="button" class="btn btn-success" onclick="printReport('<?php echo $registerStudentID; ?>')">Print</button></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="11">No Record Found</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>



                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php include "Includes/footer.php";?>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        function printReport(registerStudentID) {
            var originalTable = document.querySelector('.table');
            var printContent = originalTable.cloneNode(true);
            
            var headers = printContent.querySelectorAll('thead th');
            var indexToRemove = Array.from(headers).findIndex(header => header.textContent.trim() === 'Print');
            if (indexToRemove !== -1) {
                headers[indexToRemove].remove(); // Remove header column
                var rows = printContent.querySelectorAll('tbody tr');
                rows.forEach(row => row.children[indexToRemove].remove()); // Remove cells in each row
            }

            var tbody = printContent.querySelector('tbody');
            tbody.innerHTML = '';

            var row = document.getElementById('row-' + registerStudentID);
            if (row) {
                var rowClone = row.cloneNode(true);
                if (indexToRemove !== -1) {
                    rowClone.children[indexToRemove].remove();
                }
                tbody.appendChild(rowClone);
            }

            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Report</title>');
            printWindow.document.write('<style>@media print { .printable-table { width: 100%; border-collapse: collapse; } .printable-table th, .printable-table td { border: 1px solid #ddd; padding: 8px; } .printable-table th { background-color: #f2f2f2; } }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h1>Student Co-Curriculum Report</h1>');
            printWindow.document.write('<h2>Sekolah Menengah Arab Assaiyidah Khadijah</h2>');
            printWindow.document.write('<table class="printable-table">');
            printWindow.document.write(printContent.querySelector('thead').outerHTML);
            printWindow.document.write(printContent.querySelector('tbody').outerHTML);
            printWindow.document.write('</table>');
            printWindow.document.write('</body></html>');

            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
    </script>
    <script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#dataTable')) {
        $('#dataTable').DataTable().destroy();
    }

    $('#dataTable').DataTable({
        "order": [[0, "asc"]], // Default sort by the first column (Student Name) in ascending order
        "columnDefs": [
            { "orderable": true, "targets": 0 }, // Enable sorting on the first column (Student Name)
            { "orderable": true, "targets": 1 }, // Enable sorting on the second column (Student IC)
            { "orderable": true, "targets": 2 }, // Enable sorting on the third column (Year)
            { "orderable": true, "targets": 3 }, // Enable sorting on the fourth column (Club)
            { "orderable": true, "targets": 4 }, // Enable sorting on the fifth column (Class)
            { "orderable": true, "targets": 5 }, // Enable sorting on the sixth column (Club Position)
            { "orderable": true, "targets": 6 }, // Enable sorting on the seventh column (Activities)
            { "orderable": true, "targets": 7 }, // Enable sorting on the eighth column (Competitions)
            { "orderable": true, "targets": 8 }, // Enable sorting on the ninth column (Total Mark)
            { "orderable": true, "targets": 9 }, // Enable sorting on the tenth column (Grade)
            { "orderable": false, "targets": 10 } // Disable sorting on the eleventh column (Print button)
        ]
    });
});
</script>
</body>
</html>
