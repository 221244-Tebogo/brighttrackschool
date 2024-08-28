<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config.php'); 
try {
    // Fetch Assignments
    $stmtAssignments = $conn->prepare("SELECT * FROM Assignment ORDER BY Date DESC");
    $stmtAssignments->execute();
    $assignments = $stmtAssignments->fetchAll();

    // Fetch Timetable (example for a specific class, you might need to adjust for student-specific)
    $stmtTimetable = $conn->prepare("SELECT * FROM Timetable ORDER BY Day, StartTime");
    $stmtTimetable->execute();
    $timetable = $stmtTimetable->fetchAll();

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="main-content">
        <h1>Student Dashboard</h1>
        
        <h2>Upcoming Assignments</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($assignments)) { ?>
                    <?php foreach ($assignments as $assignment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($assignment['Date']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['Name']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['Type']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <tr><td colspan="3">No assignments available.</td></tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Timetable</h2>
        <table>
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Subject</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($timetable)) { ?>
                    <?php foreach ($timetable as $time): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($time['Day']); ?></td>
                            <td><?php echo htmlspecialchars($time['StartTime']); ?></td>
                            <td><?php echo htmlspecialchars($time['EndTime']); ?></td>
                            <td><?php echo htmlspecialchars($time['Subject']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <tr><td colspan="4">No timetable available.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
