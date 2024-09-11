<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['TeacherID']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(['success' => false]);
    exit;
}

$assignmentID = $_POST['AssignmentID'] ?? null;

if ($assignmentID) {
    $sql = "DELETE FROM Assignment WHERE AssignmentID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $assignmentID);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid assignment ID']);
}

$conn->close();
?>
