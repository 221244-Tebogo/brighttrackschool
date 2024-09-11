

<?php

function fetchTeachers() {
    global $conn;
    $sql = "SELECT TeacherID, Name FROM Teachers";
    $result = $conn->query($sql);
    if (!$result) {
        die("Error fetching teachers: " . $conn->error);
    }
    $teachers = [];
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
    return $teachers;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'fetch_data.php'; 

    $teacherID = $_POST['teacher_id'];
    $classID = $_POST['class_id'];

    $sql = "INSERT INTO TeacherClassAssignments (TeacherID, ClassID) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ii", $teacherID, $classID);
    if ($stmt->execute()) {
        echo "Class assigned successfully!";
    } else {
        echo "Failed to assign class: " . $stmt->error;
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
    <title>Assign Classes to Teachers</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Assign Classes to Teachers</h2>
        <form action="assign_teacher.php" method="post">
            <div class="form-group">
                <label for="teacher">Select Teacher:</label>
                <select class="form-control" id="teacher" name="teacher_id">
                    <?php
                    include 'fetch_data.php';
                    $teachers = fetchTeachers();
                    foreach ($teachers as $teacher) {
                        echo "<option value='{$teacher['TeacherID']}'>{$teacher['Name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="class">Select Class:</label>
                <select class="form-control" id="class" name="class_id">
                    <?php
                    $classes = fetchClasses();
                    foreach ($classes as $class) {
                        echo "<option value='{$class['ClassID']}'>{$class['ClassName']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign Class</button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
