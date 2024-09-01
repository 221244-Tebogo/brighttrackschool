<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config.php'); 

try {
    
    $stmtAssignments = $conn->prepare("SELECT Name, DueDate, Description FROM Assignment ORDER BY DueDate DESC");
    if (!$stmtAssignments) {
        throw new Exception("Error preparing statement for assignments: " . $conn->error);
    }
    $stmtAssignments->execute();
    $resultAssignments = $stmtAssignments->get_result();
    $assignments = [];
    while ($row = $resultAssignments->fetch_assoc()) {
        $assignments[] = $row;
    }
    $stmtAssignments->close();


    $stmtTimetable = $conn->prepare("
        SELECT Day, StartTime, EndTime, SubjectID 
        FROM Timetable 
        ORDER BY Day, StartTime
    ");
    if (!$stmtTimetable) {
        throw new Exception("Error preparing statement for timetable: " . $conn->error);
    }
    $stmtTimetable->execute();
    $resultTimetable = $stmtTimetable->get_result();
    $timetable = [];
    while ($row = $resultTimetable->fetch_assoc()) {
        $timetable[] = $row;
    }
    $stmtTimetable->close();

} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css"></head>
<body>
    <div class="sidebar">
        <?php include '../components/admin_sidebar.php'; ?>
    </div>
    <div class="container mt-4">
        <h1 class="mb-4">Dashboard</h1>
        
        <h2>Upcoming Assignments</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Due Date</th>
                    <th>Name</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($assignments)) { ?>
                    <?php foreach ($assignments as $assignment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($assignment['DueDate']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['Name']); ?></td>
                            <td><?php echo htmlspecialchars($assignment['Description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <tr><td colspan="3">No assignments available.</td></tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Timetable</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Subject ID</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($timetable)) { ?>
                    <?php foreach ($timetable as $time): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($time['Day']); ?></td>
                            <td><?php echo htmlspecialchars($time['StartTime']); ?></td>
                            <td><?php echo htmlspecialchars($time['EndTime']); ?></td>
                            <td><?php echo htmlspecialchars($time['SubjectID']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <tr><td colspan="4">No timetable available.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
