<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");  // Updated path based on the folder structure
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
    <ul>
        <li><a href="manage_teachers.php">Manage Teachers</a></li>
        <li><a href="manage_students.php">Manage Students</a></li>
        <li><a href="manage_classes.php">Manage Classes</a></li>
        <li><a href="manage_subjects.php">Manage Subjects</a></li>
        <li><a href="../../auth/logout.php">Logout</a></li> <!-- Correct path based on folder structure -->
    </ul>
</body>
</html>
