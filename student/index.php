<?php
include('../config.php');
include('../components/sidebar.php');

$studentID = $_SESSION['student_id']; 

$stmtName = $conn->prepare("SELECT FirstName FROM Student WHERE StudentID = ?");
$stmtName->bind_param("i", $studentID);
$stmtName->execute();
$resultName = $stmtName->get_result();
$studentName = '';
if ($resultName->num_rows > 0) {
    $studentName = $resultName->fetch_assoc()['FirstName'];
}

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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;600&family=Material+Symbols+Rounded:wght@400;600&family=Material+Symbols+Sharp:wght@400;600&family=Material+Symbols+Two+Tone:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body>
    <?php include '../components/sidebar.php'; ?>

    <div class="container mt-4">
        <div class="welcome">
            <h1 class="display-4">
                <span class="material-symbols-outlined">person</span>
                Welcome, <?php echo htmlspecialchars($studentName); ?>!
            </h1>
            <p class="lead">We're glad to see you back. Here are your current assignments:</p>
        </div>

        <div class="main-content">
            <h2>Student Projects <span class="material-symbols-outlined">assignment</span></h2>
            <p>Details about ongoing projects or assignments:</p>
            <?php
            if ($result->num_rows > 0) {
                while ($assignment = $result->fetch_assoc()) {
                    echo "<div class='card mb-3'>";
                    echo "<div class='card-body'>";
                    echo "<h3 class='card-title'><span class='material-symbols-outlined'>description</span> " . htmlspecialchars($assignment['Name']) . "</h3>";
                    echo "<p class='card-text'><span class='material-symbols-outlined'>calendar_today</span> <strong>Due date:</strong> " . htmlspecialchars($assignment['DueDate']) . "</p>";
                    echo "<p class='card-text'><span class='material-symbols-outlined'>info</span> " . htmlspecialchars($assignment['Description']) . "</p>";
                    echo "<a href='submit_assignment.php?assignmentID=" . htmlspecialchars($assignment['AssignmentID']) . "' class='btn btn-primary'><span class='material-symbols-outlined'>send</span> Submit Assignment</a>";
                    echo "</div></div>";
                }
            } else {
                echo "<p class='alert alert-info'><span class='material-symbols-outlined'>info</span> No assignments found.</p>";
            }
            ?>
        </div>
    </div>
    <?php include('../components/footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
