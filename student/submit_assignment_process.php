<?php
require_once '../config.php';

$assignmentID = $_POST['assignmentID'];
$studentID = $_POST['studentID'];
$file = $_FILES['fileUpload'];

$filePath = 'uploads/' . $file['name'];
move_uploaded_file($file['tmp_name'], $filePath);

$sql = "INSERT INTO Submission (AssignmentID, StudentID, SubmittedDate, FilePath) VALUES (?, ?, NOW(), ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$assignmentID, $studentID, $filePath]);

if ($stmt->rowCount()) {
    echo "Submission successful!";
} else {
    echo "Failed to submit.";
}
?>
