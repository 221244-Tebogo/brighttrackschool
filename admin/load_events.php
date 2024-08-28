<?php
require_once '../config.php';

$sql = "SELECT TimetableID, ClassID, Day, StartTime, EndTime, SubjectID FROM Timetable";
$result = $conn->query($sql);
$events = [];

while ($row = $result->fetch_assoc()) {
    $events[] = [
        'id' => $row['TimetableID'],
        'title' => $row['SubjectID'], // Modify as needed to show relevant title
        'start' => $row['Day'] . ' ' . $row['StartTime'], // Combine date and start time
        'end' => $row['Day'] . ' ' . $row['EndTime'] // Combine date and end time
    ];
}

echo json_encode($events);
?>
