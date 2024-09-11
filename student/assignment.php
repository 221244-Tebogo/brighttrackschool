<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CSRF Token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if the student is logged in
if (!isset($_SESSION['StudentID'])) {
    header("Location: /brighttrackschool/auth/login.php");
    exit;
}

include('../config.php');

// Get student information
$studentID = $_SESSION['StudentID'];
$stmtName = $conn->prepare("SELECT FirstName FROM Student WHERE StudentID = ?");
$stmtName->bind_param("i", $studentID);
$stmtName->execute();
$resultName = $stmtName->get_result();
$studentName = '';
if ($resultName->num_rows > 0) {
    $studentName = $resultName->fetch_assoc()['FirstName'];
}
$stmtName->close();

// Get assignments based on student enrollment
$query = "
    SELECT a.AssignmentID, a.Name, a.DueDate, a.Description
    FROM Assignment a
    JOIN Class c ON a.ClassID = c.ClassID
    JOIN Enrollment e ON e.ClassID = c.ClassID
    WHERE e.StudentID = ?
    ORDER BY a.DueDate ASC";

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
</head>
<body>
    <?php include '../components/sidebar.php'; ?>

    <div class="container mt-4">
        <div class="welcome">
            <h1 class="display-4">Welcome, <?php echo htmlspecialchars($studentName); ?>!</h1>
            <p class="lead">Here are your current assignments:</p>
        </div>

        <div class="main-content">
            <h2>Student Projects</h2>
            <?php
            if ($result->num_rows > 0) {
                while ($assignment = $result->fetch_assoc()) {
                    echo "<div class='card mb-3'>";
                    echo "<div class='card-body'>";
                    echo "<h3 class='card-title'>" . htmlspecialchars($assignment['Name']) . "</h3>";
                    echo "<p class='card-text'><strong>Due date:</strong> " . htmlspecialchars($assignment['DueDate']) . "</p>";
                    echo "<p class='card-text'>" . htmlspecialchars($assignment['Description']) . "</p>";
                    echo "<a href='submit_assignment.php?assignmentID=" . htmlspecialchars($assignment['AssignmentID']) . "&csrf_token=" . $_SESSION['csrf_token'] . "' class='btn btn-primary'>Submit Assignment</a>";
                    echo "</div></div>";
                }
            } else {
                echo "<p class='alert alert-info'>No assignments found.</p>";
            }
            ?>
        </div>
    </div>

    <?php include('../components/footer.php'); ?>
</body>
</html>
