<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submissionID = $_POST['submissionID'];
    $marks = $_POST['marks'];
    $comment = $_POST['comment'];

    $checkSql = "SELECT * FROM Submission WHERE SubmissionID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $submissionID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $updateSql = "UPDATE Submission SET Grade = ?, Comment = ? WHERE SubmissionID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("isi", $marks, $comment, $submissionID);
        if ($updateStmt->execute()) {
            echo "Results assigned successfully!";
        } else {
            echo "Failed to assign results: " . $updateStmt->error;
        }
        $updateStmt->close();
    } else {
        echo "No valid submission found.";
    }
    $checkStmt->close();
    $conn->close();
}
?>
