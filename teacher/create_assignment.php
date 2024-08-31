<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Assignment</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php
    require_once '../config.php';  // Ensure this path is correct
    ?>

    <h1>Create Assignment</h1>
    <form action="create_assignment_process.php" method="post">
        <input type="text" name="name" placeholder="Assignment Name" required><br>
        <input type="date" name="dueDate" placeholder="Due Date" required><br>
        <textarea name="description" placeholder="Description" required></textarea><br>
        <select name="subjectID" required>
            <option value="">Select Subject</option>
            <?php
            // Fetch all subjects from the database
            $stmt = $conn->prepare("SELECT SubjectID, Name FROM Subject");
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
                while ($subject = $result->fetch_assoc()) {
                    echo '<option value="'.$subject['SubjectID'].'">'.htmlspecialchars($subject['Name']).'</option>';
                }
                $stmt->close();
            } else {
                echo '<option disabled>Error loading subjects.</option>';
                error_log("MySQL prepare error: " . $conn->error); // Logging error
            }
            ?>
        </select><br>
        <button type="submit">Create Assignment</button>
    </form>

    <?php
    $conn->close(); // Close the database connection
    ?>
</body>
</html>
