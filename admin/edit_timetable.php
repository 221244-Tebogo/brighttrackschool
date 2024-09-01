<?php
require_once '../config.php';
$id = $_GET['id'] ?? null;
$timetable = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM Timetable WHERE TimetableID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $timetable = $result->fetch_assoc();
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the POST to update the timetable entry
    $classID = $_POST['classID'];
    $teacherID = $_POST['teacherID'];
    $day = $_POST['day'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $subjectID = $_POST['subjectID'];

    $sql = "UPDATE Timetable SET ClassID=?, Day=?, StartTime=?, EndTime=?, SubjectID=?, TeacherID=? WHERE TimetableID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssiii", $classID, $day, $startTime, $endTime, $subjectID, $teacherID, $id);
    if ($stmt->execute()) {
        echo "<p>Timetable updated successfully!</p>";
        // Redirect or clear the form
    } else {
        echo "<p>Error updating timetable: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>Edit Timetable</title>
</head>
<body>
    <h1>Edit Timetable Entry</h1>
    <?php if ($timetable): ?>
    <form action="edit_timetable.php?id=<?= htmlspecialchars($id) ?>" method="post">
        <!-- Add form fields pre-filled with data from $timetable -->
        <button type="submit">Update</button>
    </form>
    <?php else: ?>
    <p>Timetable entry not found.</p>
    <?php endif; ?>
</body>
</html>
