<?php
require_once '../config.php';

if (isset($_GET['id'])) {
    $classID = $_GET['id'];

    $sql = "DELETE FROM Class WHERE ClassID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $classID);
    if ($stmt->execute()) {
        header("Location: ../class_list.php");
        exit;
    } else {
        echo "Error deleting class: " . $stmt->error;
    }
    $stmt->close();
}
?>
