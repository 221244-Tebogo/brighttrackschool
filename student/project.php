<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); 

include('../config.php');
include('../components/header.php');
include('../components/sidebar.php');

if (!isset($_SESSION['student_id'])) {
    echo "You are not logged in.";
    exit; 
}

$student_id = $_SESSION['student_id'];

// Corrected query: assuming there is an Enrollment table and AssignmentID exists in Assignment table
$query = "SELECT a.AssignmentID, a.Description, a.DueDate 
          FROM Assignment a 
          JOIN Class c ON a.ClassID = c.ClassID 
          JOIN Enrollment e ON c.ClassID = e.ClassID 
          WHERE e.StudentID = ?";

$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}

$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();


if ($stmt === false) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}

$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body>
  <?php include '../components/sidebar.php'; ?>
  <script src="../components/sidebar.js"></script>

  <div style="margin-left: 260px; padding: 20px;">
    <h1>Welcome to the Student Dashboard</h1>
<div class="main-content">
    <h1>Student Projects</h1>
    <p>Details about ongoing projects or assignments.</p>
    <?php
    if ($result->num_rows > 0) {
        while ($assignment = $result->fetch_assoc()) {
            echo "<div><h3>" . htmlspecialchars($assignment['description']) . "</h3>";
            echo "<p>Due date: " . htmlspecialchars($assignment['due_date']) . "</p>";
            echo "<a href='submit_assignment.php?assignmentID=" . htmlspecialchars($assignment['assignmentID']) . "'>Submit Assignment</a></div>";
        }
    } else {
        echo "<p>No assignments found.</p>";
    }
    ?>
</div>

<?php
include('../components/footer.php');
?>


// Retrieve assignment details if needed
$assignmentID = $_GET['assignmentID'] ?? '';
?>

    <h1>Submit Assignment</h1>
    <form action="submit_assignment_process.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="assignmentID" value="<?= $assignmentID ?>" required>
        <input type="hidden" name="studentID" value="<?= $_SESSION['student_id'] ?>" required>
        <input type="file" name="fileUpload" required>
        <button type="submit">Submit Assignment</button>
    </form>
</body>
</html>
