<?php
require_once '../config.php';

if (isset($_GET['id'])) {
    $subjectID = $_GET['id'];

    $sql = "DELETE FROM Subject WHERE SubjectID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $subjectID);
    if ($stmt->execute()) {
        header("Location: class_list.php");
        exit;
    } else {
        echo "Error deleting subject: " . $stmt->error;
    }
    $stmt->close();
}
?>
