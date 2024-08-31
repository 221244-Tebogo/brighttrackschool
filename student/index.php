<?php
include('../config.php');
include('../components/header.php');
include('../components/sidebar.php');

// Assuming StudentID is stored in session after login
$studentID = $_SESSION['student_id']; 

// Updated query: Fetch assignments for the student's enrolled classes
$query = "SELECT a.AssignmentID, a.Name, a.DueDate, a.Description
          FROM Assignment a
          JOIN Class c ON a.SubjectID = c.SubjectID
          JOIN Enrollment e ON c.ClassID = e.ClassID
          WHERE e.StudentID = ?";

$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}

$stmt->bind_param("i", $studentID);
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

    <div style="margin-left: 260px; padding: 20px;">
        <h1>Welcome to the Student Dashboard</h1>
        <div class="main-content">
            <h2>Student Projects</h2>
            <p>Details about ongoing projects or assignments:</p>
            <?php
            if ($result->num_rows > 0) {
                while ($assignment = $result->fetch_assoc()) {
                    echo "<div><h3>" . htmlspecialchars($assignment['Name']) . "</h3>";
                    echo "<p>Due date: " . htmlspecialchars($assignment['DueDate']) . "</p>";
                    echo "<p>Description: " . htmlspecialchars($assignment['Description']) . "</p>";
                    echo "<a href='submit_assignment.php?assignmentID=" . htmlspecialchars($assignment['AssignmentID']) . "'>Submit Assignment</a></div>";
                }
            } else {
                echo "<p>No assignments found.</p>";
            }
            ?>
        </div>
    </div>
    <?php include('../components/footer.php'); ?>
</body>
</html>
