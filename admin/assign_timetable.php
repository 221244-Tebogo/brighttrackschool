<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect POST data
    $classID = $_POST['classID'];
    $teacherID = $_POST['teacherID'];
    $day = $_POST['day'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $subjectID = $_POST['subjectID'];

    // Prepare SQL Statement
    $sql = "INSERT INTO Timetable (ClassID, Day, StartTime, EndTime, SubjectID, TeacherID) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Bind parameters and execute statement
        $stmt->bind_param("isssii", $classID, $day, $startTime, $endTime, $subjectID, $teacherID);
        if ($stmt->execute()) {
            echo "<p>Timetable assigned successfully!</p>";
        } else {
            echo "<p>Error assigning timetable: " . $stmt->error . "</p>";
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
    <title>Manage Timetable Assignments</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="sidebar">
    <?php include '../components/admin_sidebar.php'; ?>
</div>
<div class="content">
    <h1>Assign Timetables</h1>
    <form action="assign_timetable.php" method="post">
        <div>
            <label for="classSelect">Select Class:</label>
            <select id="classSelect" name="classID">
                <option value="">Select a Class</option>
                <?php
                $classes = $conn->query("SELECT ClassID, ClassName FROM Class ORDER BY ClassName");
                while ($class = $classes->fetch_assoc()) {
                    echo "<option value='{$class['ClassID']}'>{$class['ClassName']}</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label for="teacherSelect">Select Teacher:</label>
            <select id="teacherSelect" name="teacherID">
                <option value="">Select a Teacher</option>
                <?php
                $teachers = $conn->query("SELECT TeacherID, FirstName, LastName FROM Teacher ORDER BY FirstName");
                while ($teacher = $teachers->fetch_assoc()) {
                    echo "<option value='{$teacher['TeacherID']}'>{$teacher['FirstName']} {$teacher['LastName']}</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label for="day">Day:</label>
            <select id="day" name="day" required>
                <option value="">Select a Day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
            </select>
        </div>
        <div>
            <label for="startTime">Start Time:</label>
            <input type="time" id="startTime" name="startTime" required>
        </div>
        <div>
            <label for="endTime">End Time:</label>
            <input type="time" id="endTime" name="endTime" required>
        </div>
        <div>
            <label for="subjectSelect">Select Subject:</label>
            <select id="subjectSelect" name="subjectID">
                <option value="">Select a Subject</option>
                <?php
                $subjects = $conn->query("SELECT SubjectID, Name FROM Subject ORDER BY Name");
                while ($subject = $subjects->fetch_assoc()) {
                    echo "<option value='{$subject['SubjectID']}'>{$subject['Name']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit">Assign Timetable</button>
    </form>
    <h2>Current Timetable Assignments</h2>
    <table>
        <thead>
            <tr>
                <th>Class</th>
                <th>Teacher</th>
                <th>Day</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Subject</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT t.Day, t.StartTime, t.EndTime, c.ClassName, s.Name AS SubjectName, CONCAT(te.FirstName, ' ', te.LastName) AS TeacherName FROM Timetable t JOIN Class c ON t.ClassID = c.ClassID JOIN Subject s ON t.SubjectID = s.SubjectID JOIN Teacher te ON t.TeacherID = te.TeacherID ORDER BY t.Day, t.StartTime";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['ClassName']}</td>
                            <td>{$row['TeacherName']}</td>
                            <td>{$row['Day']}</td>
                            <td>{$row['StartTime']}</td>
                            <td>{$row['EndTime']}</td>
                            <td>{$row['SubjectName']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No timetable assignments found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script src="../assets/js/admin.js"></script>
</body>
</html>
