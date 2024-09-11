<?php
require_once '../config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['TeacherID'])) {
    $teacherID = $_SESSION['TeacherID'];
    
    $name = $conn->real_escape_string($_POST['name']);
    $dueDate = $conn->real_escape_string($_POST['dueDate']);
    $description = $conn->real_escape_string($_POST['description']);
    $subjectID = (int)$_POST['subjectID'];
    $classID = (int)$_POST['classID'];

    $sql = "INSERT INTO Assignment (TeacherID, Name, DueDate, Description, SubjectID, ClassID) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("isssii", $teacherID, $name, $dueDate, $description, $subjectID, $classID);
        if ($stmt->execute()) {
            header("Location: view_assignments.php?success=1");
            exit();
        } else {
            echo "Failed to create assignment: " . $stmt->error;
        }
        $stmt->close();
    } else {
        die("Prepare failed: " . $conn->error);
    }
    $conn->close();
} else {
    header('Location: ../auth/login.php');
    exit();
}
