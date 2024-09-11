<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Assignment</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../components/sidebar.php'; ?>

    <h1>Submit Assignment</h1>
    <form action="submit_assignment_process.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="assignmentID" value="<?= htmlspecialchars($assignmentID) ?>" required>
        <input type="hidden" name="studentID" value="<?= htmlspecialchars($studentID) ?>" required>
        <input type="file" name="fileUpload" required>
        <button type="submit">Submit Assignment</button>
    </form>
</body>
</html>
