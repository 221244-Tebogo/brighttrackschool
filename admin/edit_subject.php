<?php
require_once '../config.php';

if (isset($_GET['id'])) {
    $subjectID = $_GET['id'];
    $sql = "SELECT * FROM Subject WHERE SubjectID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $subjectID);
    $stmt->execute();
    $result = $stmt->get_result();
    $subject = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subjectID = $_POST['SubjectID'];
    $name = $_POST['Name'];

    $sql = "UPDATE Subject SET Name = ? WHERE SubjectID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $name, $subjectID);
    if ($stmt->execute()) {
        header("Location: class_list.php");
        exit;
    } else {
        echo "Error updating subject: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subject</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
</head>
<body>
<div class="sidebar">
    <?php include '../components/admin_sidebar.php'; ?>
</div>
<div class="main-container">
    <div class="edit-subject-container">
        <h1>Edit Subject</h1>
        <form method="post" action="edit_subject.php?id=<?= $subject['SubjectID'] ?>">
            <input type="hidden" name="SubjectID" value="<?= $subject['SubjectID'] ?>">
            <label for="subjectName">Subject Name</label>
            <input type="text" id="subjectName" name="Name" value="<?= htmlspecialchars($subject['Name']) ?>" required>
            <button type="submit" class="btn btn-primary">Update Subject</button>
        </form>
    </div>
</div>
</body>
</html>
