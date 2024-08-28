<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Assignment</title>
    <link rel="stylesheet" href="../../assets/css/style.css">

</head>
<body>
<?php include '../components/sidebar.php'; ?>
  <script src="../components/sidebar.js"></script>

    <h1>Submit Assignment</h1>
    <form action="submit_assignment_process.php" method="post" enctype="multipart/form-data">
        <input type="number" name="assignmentID" placeholder="Assignment ID" required><br>
        <input type="number" name="studentID" placeholder="Student ID" required><br>
        <input type="file" name="fileUpload" required><br>
        <button type="submit">Submit Assignment</button>
    </form>
</body>
</html>
