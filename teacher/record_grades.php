<?php
include('config.php');
include('header.php'); // Include header

// Assume you have a form where these values come from
$enrollmentID = $_POST['enrollmentID'];
$grade = $_POST['grade'];
$comment = $_POST['comment'];

$sql = "UPDATE Enrollment SET Grade = ?, Comment = ? WHERE EnrollmentID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $grade, $comment, $enrollmentID);
if ($stmt->execute()) {
    echo "Grade recorded successfully!";
} else {
    echo "Error recording grade: " . $stmt->error;
}
$stmt->close();

include('footer.php'); // Include footer
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Record Grades</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Record Student Grades</h1>
    <form action="record_grades.php" method="post">
        <label for="enrollmentID">Enrollment ID:</label>
        <input type="number" id="enrollmentID" name="enrollmentID" required><br>

        <label for="grade">Grade:</label>
        <input type="text" id="grade" name="grade" required><br>

        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment"></textarea><br>

        <button type="submit">Submit Grade</button>
    </form>
</body>
</html>
