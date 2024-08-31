<?php
include('config.php');
include('header.php'); // Include header

$enrollmentID = $_POST['enrollmentID']; // Get this from a form submission
$attendance = $_POST['attendance']; // 'Present' or 'Absent'
$date = date("Y-m-d");

$sql = "INSERT INTO Attendance (EnrollmentID, Date, Status) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $enrollmentID, $date, $attendance);
if ($stmt->execute()) {
    echo "Attendance marked successfully!";
} else {
    echo "Error marking attendance: " . $stmt->error;
}
$stmt->close();

include('footer.php'); // Include footer
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Mark Attendance</h1>
    <form action="mark_attendance.php" method="post">
        <label for="enrollmentID">Enrollment ID:</label>
        <input type="number" id="enrollmentID" name="enrollmentID" required><br>

        <label>Status:</label>
        <select name="attendance" required>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
        </select><br>

        <button type="submit">Mark Attendance</button>
    </form>
</body>
</html>
