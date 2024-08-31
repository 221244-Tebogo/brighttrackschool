<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_POST['studentID'];
    $classID = $_POST['classID'];

    $sql = "INSERT INTO Enrollment (StudentID, ClassID) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $studentID, $classID);
        if ($stmt->execute()) {
            echo "<p>Student enrolled successfully!</p>";
        } else {
            echo "<p>Error enrolling student: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enroll Student</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Verify the path to your CSS -->
</head>
<body>
    <h1>Enroll Student</h1>
    <form action="enroll_students.php" method="post">
        <label for="studentID">Student ID:</label>
        <input type="number" id="studentID" name="studentID" required><br>

        <label for="classID">Class ID:</label>
        <input type="number" id="classID" name="classID" required><br>

        <button type="submit">Enroll</button>
    </form>
</body>
</html>
