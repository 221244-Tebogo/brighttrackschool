<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['Name'];

    $sql = "INSERT INTO Subject (Name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $name);
    if ($stmt->execute()) {
        echo "<p>Subject added successfully.</p>";
    } else {
        echo "<p>Error adding subject: " . $stmt->error . "</p>";
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
    <title>Add Subject</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="sidebar">
    <?php include '../components/admin_sidebar.php'; ?>
</div>
<div class="content">
    <h1>Add a New Subject</h1>
    <form method="post" action="add_subject.php">
        <input type="text" name="Name" placeholder="Enter subject name" required>
        <button type="submit">Add Subject</button>
    </form>
</body>
</html>
