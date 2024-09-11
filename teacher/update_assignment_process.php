<?php
require_once '../config.php';
session_start();


if (!isset($_SESSION['TeacherID']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: /brighttrackschool/auth/login.php");
    exit();
}

$assignmentID = $_POST['assignmentID'];
$teacherID = $_SESSION['TeacherID'];


$name = $conn->real_escape_string($_POST['name']);
$dueDate = $conn->real_escape_string($_POST['dueDate']);
$description = $conn->real_escape_string($_POST['description']);
$subjectID = (int)$_POST['subjectID'];


$sql = "UPDATE Assignment SET Name = ?, DueDate = ?, Description = ?, SubjectID = ? WHERE AssignmentID = ? AND TeacherID = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssiii", $name, $dueDate, $description, $subjectID, $assignmentID, $teacherID);

    if ($stmt->execute()) {
        header("Location: view_assignments.php?success=1");
        exit();
    } else {
        echo "Failed to update assignment: " . $stmt->error;
    }

    $stmt->close();
} else {
    die("Prepare failed: " . $conn->error);
}

$conn->close();
?>
