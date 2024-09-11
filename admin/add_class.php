<?php
require_once '../config.php';

$teacherOptions = "";
$teacherSql = "SELECT TeacherID, FirstName, LastName FROM Teacher ORDER BY LastName, FirstName";
$teachers = $conn->query($teacherSql);
if ($teachers->num_rows > 0) {
    while ($teacher = $teachers->fetch_assoc()) {
        $teacherOptions .= "<option value='" . $teacher['TeacherID'] . "'>" . htmlspecialchars($teacher['FirstName'] . " " . $teacher['LastName']) . "</option>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $className = $_POST['ClassName'];
    $grade = $_POST['Grade'];
    $teacherID = $_POST['TeacherID'];

    $sql = "INSERT INTO Class (ClassName, Grade, TeacherID) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssi", $className, $grade, $teacherID);
    if ($stmt->execute()) {
        echo "<p>Class added successfully.</p>";
    } else {
        echo "<p>Error adding class: " . $stmt->error . "</p>";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
</head>
<body>
<div class="sidebar">
    <?php include '../components/admin_sidebar.php'; ?>
</div>
<div class="content">
    <h1>Add a New Class</h1>
    <form method="post" action="add_class.php">
        <input type="text" name="ClassName" placeholder="Enter class name" required>
        <input type="text" name="Grade" placeholder="Enter grade" required>
        <select name="TeacherID" required>
            <option value="">Select a teacher</option>
            <?= $teacherOptions ?>
        </select>
        <button type="submit">Add Class</button>
    </form>
</div>
</body>
</html>
