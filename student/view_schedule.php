<?php
include('config.php');
include('header.php'); 
session_start();

$studentID = $_SESSION['student_id'] ?? 0;

if ($studentID == 0) {
    echo "You are not logged in.";
    exit;
}

$sql = "SELECT c.ClassName, t.Day, t.StartTime, t.EndTime 
        FROM Enrollment e 
        JOIN Class c ON e.ClassID = c.ClassID 
        JOIN Timetable t ON c.ClassID = t.ClassID 
        WHERE e.StudentID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table><tr><th>Class</th><th>Day</th><th>Start Time</th><th>End Time</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['ClassName'] . "</td><td>" . $row['Day'] . "</td><td>" . $row['StartTime'] . "</td><td>" . $row['EndTime'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No schedule found.";
}

include('footer.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Schedule</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Student Schedule</h1>
</body>
</html>

