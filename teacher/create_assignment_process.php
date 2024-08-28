<?php
require_once '../config.php';

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $dueDate = $_POST['dueDate'];
    $description = $_POST['description'];
    $subjectID = $_POST['subjectID'];

    // Prepare an insert statement
    $sql = "INSERT INTO Assignment (Name, DueDate, Description, SubjectID) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("MySQL prepare error: " . $conn->error);
    }

    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("sssi", $name, $dueDate, $description, $subjectID);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        echo "Assignment created successfully!";
    } else {
        echo "Failed to create assignment: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
