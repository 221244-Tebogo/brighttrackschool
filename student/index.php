<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['StudentID'])) {
    header("Location: /brighttrackschool/auth/login.php");
    exit;
}

include('../config.php');

$studentID = $_SESSION['StudentID'];
$stmtName = $conn->prepare("SELECT FirstName FROM Student WHERE StudentID = ?");
$stmtName->bind_param("i", $studentID);
$stmtName->execute();
$resultName = $stmtName->get_result();
$studentName = '';
if ($resultName && $resultName->num_rows > 0) {
    $studentName = $resultName->fetch_assoc()['FirstName'];
}
$stmtName->close();

$query = "
    SELECT a.AssignmentID, a.Name, a.DueDate, a.Description
    FROM Assignment a
    JOIN Class c ON a.ClassID = c.ClassID
    JOIN Enrollment e ON e.ClassID = c.ClassID
    WHERE e.StudentID = ?
    ORDER BY a.DueDate ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

$sql = "SELECT c.ClassName, c.Grade, s.Name AS SubjectName, t.Day, t.StartTime, t.EndTime 
        FROM Timetable t
        JOIN Class c ON t.ClassID = c.ClassID
        JOIN Subject s ON t.SubjectID = s.SubjectID
        JOIN Enrollment e ON c.ClassID = e.ClassID
        WHERE e.StudentID = ? ORDER BY t.Day, t.StartTime";
$stmtClasses = $conn->prepare($sql);
$stmtClasses->bind_param("i", $studentID);
$stmtClasses->execute();
$resultClasses = $stmtClasses->get_result();
$assignedClasses = [];
if ($resultClasses && $resultClasses->num_rows > 0) {
    while ($row = $resultClasses->fetch_assoc()) {
        $assignedClasses[] = $row;
    }
}
$stmtClasses->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/calendar.css">

</head>
<body>
    <div class="container-grid">
        
        <aside class="navbar-left">
            <?php include '../components/sidebar.php'; ?>
        </aside>

        <main class="main-content">
            <div class="welcome">
                <h1 class="display-4">
                    <span class="material-symbols-outlined">person</span>
                    Welcome, <?php echo htmlspecialchars($studentName); ?>!
                </h1>
                <p class="lead">Here are your current assignments and upcoming classes:</p>
            </div>

            <div class="main-overview">
                <div class="overviewcard">
                    <h2>Student Projects <span class="material-symbols-outlined">assignment</span></h2>
                    <p>Details about ongoing projects or assignments:</p>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($assignment = $result->fetch_assoc()) {
                            echo "<div class='card mb-3'>";
                            echo "<div class='card-body'>";
                            echo "<h3 class='card-title'><span class='material-symbols-outlined'>description</span> " . htmlspecialchars($assignment['Name']) . "</h3>";
                            echo "<p class='card-text'><span class='material-symbols-outlined'>calendar_today</span> <strong>Due date:</strong> " . htmlspecialchars($assignment['DueDate']) . "</p>";
                            echo "<p class='card-text'><span class='material-symbols-outlined'>info</span> " . htmlspecialchars($assignment['Description']) . "</p>";
                            echo "<a href='submit_assignment.php?assignmentID=" . htmlspecialchars($assignment['AssignmentID']) . "&csrf_token=" . $_SESSION['csrf_token'] . "' class='btn btn-primary'><span class='material-symbols-outlined'>send</span> Submit Assignment</a>";
                            echo "</div></div>";
                        }
                    } else {
                        echo "<p class='alert alert-info'><span class='material-symbols-outlined'>info</span> No assignments found.</p>";
                    }
                    ?>
                </div>
            </div>
        </main>

        <aside id="sidebar-right">
            <div class="small-calendar">
                <?php
                $days = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
                echo '<div class="day-names">';
                foreach ($days as $day) {
                    echo '<span class="day">' . $day . '</span>';
                }
                echo '</div>';
                
                $num_days = date('t'); 
                echo '<div class="days">';
                for ($i = 1; $i <= $num_days; $i++) {
                    echo '<span class="day_num">' . $i . '</span>';
                }
                echo '</div>';
                ?>
            </div>

            <div class="calendar_events">
                <p class="ce_title">Upcoming Classes</p>
                <?php if (count($assignedClasses) > 0): ?>
                    <?php foreach ($assignedClasses as $class): ?>
                        <div class="event_item">
                            <div class="ei_Copy"><?php echo htmlspecialchars($class['StartTime']); ?> - <?php echo htmlspecialchars($class['EndTime']); ?></div>
                            <div class="ei_Copy">Grade <?php echo htmlspecialchars($class['Grade']); ?> - <?php echo htmlspecialchars($class['ClassName']); ?> - <?php echo htmlspecialchars($class['SubjectName']); ?> (<?php echo htmlspecialchars($class['Day']); ?>)</div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="event_item">
                        <div class="ei_Title">No assigned classes</div>
                    </div>
                <?php endif; ?>
            </div>
        </aside>
    </div>

    <?php include('../components/footer.php'); ?>
</body>
</html>
