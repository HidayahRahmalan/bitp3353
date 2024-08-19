<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('header.php');
include('../include/connection.php');

$success = false;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stud_id = $_POST['stud_id'];
    $dipids = isset($_POST['course_id']) ? $_POST['course_id'] : [];
    $grades = isset($_POST['grade']) ? $_POST['grade'] : [];

    if (count($dipids) == count($grades)) {
        $conn->begin_transaction();
        try {
            for ($i = 0; $i < count($dipids); $i++) {
                $dipid = $conn->real_escape_string($dipids[$i]);
                $grade = $conn->real_escape_string($grades[$i]);

                $query = "INSERT INTO grade (stud_id, course_id, grade) VALUES ('$stud_id', '$dipid', '$grade')";
                if (!$conn->query($query)) {
                    throw new Exception("Insert failed: " . $conn->error);
                }
            }
            $conn->commit();
            $success = true;
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = $e->getMessage();
        }
    } else {
        $errors[] = "Mismatch in the number of courses and grades provided.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Add Diploma Course Grade</title>
</head>

<body>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Add Diploma Course Grade</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Diploma Grade</li>
          <li class="breadcrumb-item active">Add Diploma Grade</li>
        </ol>
      </nav>
    </div>
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Add Diploma Grade</h5>
              <p>Direct Entry Students must add the diploma course grade before transfer credit</p>

              <?php
              if ($success) {
                  echo '<div class="alert alert-success">Grades added successfully!</div>';
              }

              if (!empty($errors)) {
                  echo '<div class="alert alert-danger">';
                  foreach ($errors as $error) {
                      echo '<p>' . htmlspecialchars($error) . '</p>';
                  }
                  echo '</div>';
              }
              ?>

              <form class="row g-1" id="studentForm" method="POST" action="">
                <input type="text" class="form-control" name="stud_id" value="<?php echo $_SESSION['stud_id']; ?>">
                
                <div class="col-md-7">
                  <select id="inputState-1" class="form-select" name="course_id[]">
                    <option selected="true" disabled="disabled">Select Course</option>
                    <?php
                    $dip = $conn->query("SELECT * FROM course WHERE course_code LIKE '%BIT%' ORDER BY course_id ASC");
                    while($row = $dip->fetch_assoc()){
                    ?>
                      <option value="<?php echo $row['course_id']; ?>"><?php echo $row['title']; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="col-md-2">
                  <select id="inputState-grade-1" class="form-select" name="grade[]">
                    <option selected="" disabled="disabled">Select Grade</option>
                    <option value="A">A</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B">B</option>
                    <option value="B-">B-</option>
                    <option value="C+">C+</option>
                    <option value="C">C</option>
                    <option value="C-">C-</option>
                    <option value="D+">D+</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                  </select>
                </div>

                <div class="col-md-2">
                  <button type="button" id="addMoreBtn" class="btn btn-success">Add More</button>
                </div>
            
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form>

            </div>
          </div>
        </div>

      
      </div>
    </section>
  </main>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        const maxForms = 12;
        let formCount = 1;
        const form = document.getElementById("studentForm");
        const addMoreBtn = document.getElementById("addMoreBtn");

        function updateSelectOptions() {
            const selectedValues = Array.from(document.querySelectorAll('.form-select[name="course_id[]"]'))
                .map(select => select.value)
                .filter(value => value !== "");

            document.querySelectorAll('.form-select[name="course_id[]"]').forEach(select => {
                const currentValue = select.value;
                const options = Array.from(select.options);
                select.innerHTML = options.map(option => {
                    if (!selectedValues.includes(option.value) || option.value === currentValue) {
                        return `<option value="${option.value}" ${option.value === currentValue ? 'selected' : ''}>${option.text}</option>`;
                    }
                    return '';
                }).join('');
            });
        }

        form.addEventListener("change", function(event) {
            if (event.target.classList.contains('form-select') && event.target.name === 'course_id[]') {
                updateSelectOptions();
            }
        });

        addMoreBtn.addEventListener("click", function() {
            if (formCount < maxForms) {
                formCount++;
                const newInputGroup = document.createElement("div");
                newInputGroup.className = "row g-1 input-group";
                newInputGroup.id = `input-group-${formCount}`;
                newInputGroup.innerHTML = `
                    <div class="col-md-7">
                        <select id="inputState-${formCount}" class="form-select" name="course_id[]">
                            <option selected="true" disabled="disabled">Select Course</option>
                            <?php
                            $dip = $conn->query("SELECT * FROM course WHERE course_code LIKE '%BIT%' ORDER BY course_id ASC");
                            while($row = $dip->fetch_assoc()){
                            ?>
                              <option value="<?php echo $row['course_id']; ?>"><?php echo $row['title']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                      <select id="inputState-grade-${formCount}" class="form-select" name="grade[]">
                        <option selected="" disabled="disabled">Select Grade</option>
                        <option value="A">A</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B">B</option>
                        <option value="B-">B-</option>
                        <option value="C+">C+</></option>
                        <option value="C">C</option>
                        <option value="C-">C-</option>
                        <option value="D+">D+</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-danger remove-btn">Remove</button>
                    </div>
                `;
                form.insertBefore(newInputGroup, addMoreBtn.parentNode);
                updateSelectOptions();

                newInputGroup.querySelector('.remove-btn').addEventListener('click', function() {
                    newInputGroup.remove();
                    formCount--;
                    updateSelectOptions();
                });
            } else {
                alert("Maximum of 12 entries can be added.");
            }
        });
    });
  </script>

  <?php include('footer.php'); ?>

</body>
</html>
