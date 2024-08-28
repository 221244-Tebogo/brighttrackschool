<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    // Convert $start and $end into SQL time format
    $startTime = date('H:i:s', strtotime($start));
    $endTime = date('H:i:s', strtotime($end));

    // Update query
    $sql = "UPDATE Timetable SET StartTime = ?, EndTime = ? WHERE TimetableID = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("ssi", $startTime, $endTime, $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Event updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating event: ' . $stmt->error]);
    }
    $stmt->close();
    $conn->close();
}
?>
