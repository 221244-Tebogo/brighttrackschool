<?php
session_start();
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['fileUpload'])) {
    $assignmentID = $_POST['assignmentID'];
    $studentID = $_POST['studentID'];
    $filePath = "uploads/" . basename($_FILES['fileUpload']['name']);

    // Attempt to upload file
    if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $filePath)) {
        $submittedDate = date("Y-m-d H:i:s"); // Current date and time
        $query = "INSERT INTO Submission (AssignmentID, StudentID, SubmittedDate, FilePath) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if ($stmt === false) {
            echo "Error preparing statement: " . $conn->error;
            exit;
        }

        $stmt->bind_param("iiss", $assignmentID, $studentID, $submittedDate, $filePath);
        
        if ($stmt->execute()) {
            echo "Assignment submitted successfully.";
        } else {
            echo "Error submitting assignment: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Error uploading file.";
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
