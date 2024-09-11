<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $day = date('l', strtotime($start)); 
    $classID = 1;
    $subjectID = 1;

    $startTime = date('H:i:s', strtotime($start));
    $endTime = date('H:i:s', strtotime($end));

    $sql = "INSERT INTO Timetable (ClassID, Day, StartTime, EndTime, SubjectID) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("issss", $classID, $day, $startTime, $endTime, $subjectID);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Event added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding event: ' . $stmt->error]);
    }
    $stmt->close();
    $conn->close();
}
?>
