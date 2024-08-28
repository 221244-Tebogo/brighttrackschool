<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Assignment</title>
</head>
<body>
    <h1>Create Assignment</h1>
    <form action="create_assignment_process.php" method="post">
        <input type="text" name="name" placeholder="Assignment Name" required><br>
        <input type="date" name="dueDate" placeholder="Due Date" required><br>
        <textarea name="description" placeholder="Description"></textarea><br>
        <select name="subjectID" required>
            <option value="">Select Subject</option>
            <?php
            require_once '../config.php';
            $stmt = $conn->prepare("SELECT SubjectID, Name FROM Subject");
            $stmt->execute();
            $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($subjects as $subject) {
                echo '<option value="'.$subject['SubjectID'].'">'.$subject['Name'].'</option>';
            }
            ?>
        </select><br>
        <button type="submit">Create Assignment</button>
    </form>
</body>
</html>
