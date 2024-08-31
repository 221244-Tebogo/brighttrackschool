<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $dueDate = $_POST['dueDate'];
    $description = $_POST['description'];
    $subjectID = $_POST['subjectID'];

    $sql = "INSERT INTO Assignment (Name, DueDate, Description, SubjectID) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssi", $name, $dueDate, $description, $subjectID);

    if ($stmt->execute()) {
        echo "Assignment created successfully!";
    } else {
        echo "Failed to create assignment: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
