<?php
require_once '../config.php';

// Fetch all students
$studentsQuery = "SELECT StudentID, FirstName, LastName FROM Student ORDER BY FirstName, LastName";
$studentsResult = $conn->query($studentsQuery);
$students = [];
while ($row = $studentsResult->fetch_assoc()) {
    $students[] = $row;
}

// Fetch all classes
$classesQuery = "SELECT ClassID, ClassName, Grade FROM Class ORDER BY ClassName";
$classesResult = $conn->query($classesQuery);
$classes = [];
while ($row = $classesResult->fetch_assoc()) {
    $classes[] = $row;
}

// Handle POST request
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
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="sidebar">
    <?php include '../components/admin_sidebar.php'; ?>
</div>
    <h1>Enroll Student</h1>
    <form action="enroll_students.php" method="post">
        <label for="studentID">Students:</label>
        <select id="studentID" name="studentID" required>
            <option value="">Select a Student</option>
            <?php foreach ($students as $student): ?>
                <option value="<?= $student['StudentID'] ?>"><?= htmlspecialchars($student['FirstName'] . ' ' . $student['LastName']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="classID">Classes:</label>
        <select id="classID" name="classID" required>
            <option value="">Select a Class</option>
            <?php foreach ($classes as $class): ?>
                <option value="<?= $class['ClassID'] ?>"><?= htmlspecialchars($class['ClassName'] . ' - Grade ' . $class['Grade']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Enroll</button>
    </form>
</body>
</html>
