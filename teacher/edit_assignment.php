<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['TeacherID'])) {
    header("Location: ../teacher/dashboard.php");
    exit();
}

if (isset($_GET['AssignmentID']) && is_numeric($_GET['AssignmentID'])) {
    $assignmentID = $_GET['AssignmentID'];
} else {
    header("Location: view_assignments.php?error=invalid_assignment");
    exit();
}

$teacherID = $_SESSION['TeacherID'];
$query = "SELECT * FROM Assignment WHERE AssignmentID = ? AND TeacherID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $assignmentID, $teacherID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $assignment = $result->fetch_assoc();
} else {
    header("Location: view_assignments.php?error=not_found");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assignment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include('../components/teacher_sidebar.php'); ?>

    <main class="container mt-4">
        <h1>Edit Assignment</h1>

        <form action="update_assignment_process.php" method="POST">
            <input type="hidden" name="assignmentID" value="<?php echo $assignmentID; ?>">

            <div class="form-group">
                <label for="name">Assignment Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($assignment['Name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="dueDate">Due Date:</label>
                <input type="date" class="form-control" id="dueDate" name="dueDate" value="<?php echo htmlspecialchars($assignment['DueDate']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($assignment['Description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="subjectID">Subject:</label>
                <select class="form-control" id="subjectID" name="subjectID" required>
                    <?php
                    require '../config.php';
                    $sql = "SELECT SubjectID, Name FROM Subject ORDER BY Name";
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row['SubjectID'] == $assignment['SubjectID']) ? 'selected' : '';
                        echo "<option value='{$row['SubjectID']}' $selected>" . htmlspecialchars($row['Name']) . "</option>";
                    }
                    $conn->close();
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Assignment</button>
        </form>
    </main>

    <?php include('../components/footer.php'); ?>
</body>
</html>
