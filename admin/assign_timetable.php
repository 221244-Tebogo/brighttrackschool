<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $classID = $_POST['classID'];
    $teacherID = $_POST['teacherID'];
    $day = $_POST['day'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $subjectID = $_POST['subjectID'];

   
    $sql = "INSERT INTO Timetable (ClassID, Day, StartTime, EndTime, SubjectID, TeacherID) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("isssii", $classID, $day, $startTime, $endTime, $subjectID, $teacherID);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Timetable assigned successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error assigning timetable: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert alert-danger'>Error preparing statement: " . $conn->error . "</div>";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Timetable Assignments</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
</head>
<body>
<div class="sidebar">
    <?php include '../components/admin_sidebar.php'; ?>
</div>
<div class="container mt-4">
    <h1>Assign Timetables</h1>

    <?php if (isset($message)) echo $message; ?>

    <form action="assign_timetable.php" method="post" class="mb-4">
        <div class="form-group">
            <label for="classSelect">Select Class:</label>
            <select id="classSelect" name="classID" class="form-control" required>
                <option value="">Select a Class</option>
                <?php
                $classes = $conn->query("SELECT ClassID, Grade FROM Class ORDER BY Grade");
                while ($class = $classes->fetch_assoc()) {
                    echo "<option value='{$class['ClassID']}'>{$class['Grade']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="teacherSelect">Select Teacher:</label>
            <select id="teacherSelect" name="teacherID" class="form-control" required>
                <option value="">Select a Teacher</option>
                <?php
                $teachers = $conn->query("SELECT TeacherID, FirstName, LastName FROM Teacher ORDER BY FirstName");
                while ($teacher = $teachers->fetch_assoc()) {
                    echo "<option value='{$teacher['TeacherID']}'>{$teacher['FirstName']} {$teacher['LastName']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="day">Day:</label>
            <select id="day" name="day" class="form-control" required>
                <option value="">Select a Day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
            </select>
        </div>
        <div class="form-group">
            <label for="startTime">Start Time:</label>
            <input type="time" id="startTime" name="startTime" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="endTime">End Time:</label>
            <input type="time" id="endTime" name="endTime" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="subjectSelect">Select Subject:</label>
            <select id="subjectSelect" name="subjectID" class="form-control" required>
                <option value="">Select a Subject</option>
                <?php
                $subjects = $conn->query("SELECT SubjectID, Name FROM Subject ORDER BY Name");
                while ($subject = $subjects->fetch_assoc()) {
                    echo "<option value='{$subject['SubjectID']}'>{$subject['Name']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assign Timetable</button>
    </form>

    <h2>Current Timetable Assignments</h2>
    <table class="table table-striped">
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
            $query = "SELECT t.Day, t.StartTime, t.EndTime, c.ClassName, s.Name AS SubjectName, CONCAT(te.FirstName, ' ', te.LastName) AS TeacherName 
                      FROM Timetable t 
                      JOIN Class c ON t.ClassID = c.ClassID 
                      JOIN Subject s ON t.SubjectID = s.SubjectID 
                      JOIN Teacher te ON t.TeacherID = te.TeacherID 
                      ORDER BY t.Day, t.StartTime";
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
