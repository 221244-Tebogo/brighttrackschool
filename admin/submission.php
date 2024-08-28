<?php
require_once '../config.php';

$sql = "SELECT SubmissionID, AssignmentID, StudentID, SubmittedDate, FilePath, Grade FROM Submission";
$stmt = $conn->prepare($sql);
$stmt->execute();
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($submissions as $submission) {
    echo "Submission ID: " . $submission['SubmissionID'] . "<br>";
    echo "Assignment ID: " . $submission['AssignmentID'] . "<br>";
    echo "Student ID: " . $submission['StudentID'] . "<br>";
    echo "Submitted Date: " . $submission['SubmittedDate'] . "<br>";
    echo "File Path: " . $submission['FilePath'] . "<br>";
    echo "Grade: " . $submission['Grade'] . "<br><br>";
}
?>
