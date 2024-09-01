<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['Name'];

    $sql = "INSERT INTO Subject (Name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $message = "<div class='alert alert-danger'>Prepare failed: " . $conn->error . "</div>";
    } else {
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Subject added successfully.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error adding subject: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
</head>
<body>
<div class="sidebar">
    <?php include '../components/admin_sidebar.php'; ?>
</div>
<div class="container mt-4">

<div class="content">
    <h1>Add a New Subject</h1>
    
    <?php if (isset($message)) echo $message; ?>

    <form method="post" action="add_subject.php" class="mb-4">
        <div class="form-group">
            <label for="subjectName">Subject Name:</label>
            <input type="text" name="Name" id="subjectName" class="form-control" placeholder="Enter subject name" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Subject</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
