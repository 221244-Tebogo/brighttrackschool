<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config.php'); 

try {

    $stmtStudents = $conn->prepare("SELECT COUNT(*) AS studentCount FROM Student");
    if (!$stmtStudents) {
        throw new Exception("Error preparing statement for students: " . $conn->error);
    }
    $stmtStudents->execute();
    $stmtStudents->bind_result($studentCount);
    $stmtStudents->fetch();
    $stmtStudents->close();

    $stmtTeachers = $conn->prepare("SELECT COUNT(*) AS teacherCount FROM Teacher");
    if (!$stmtTeachers) {
        throw new Exception("Error preparing statement for teachers: " . $conn->error);
    }
    $stmtTeachers->execute();
    $stmtTeachers->bind_result($teacherCount);
    $stmtTeachers->fetch();
    $stmtTeachers->close();

  
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


    $stmtTimetable = $conn->prepare("SELECT Day, StartTime, EndTime, SubjectID FROM Timetable ORDER BY Day, StartTime");
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/calendar.css">

</head>
<body>
</aside>
    <div class="container-grid">
    
    <aside class="navbar-left">
      <?php include('../components/admin_sidebar.php'); ?>
    </aside>


    <main class="content">
      <div class="welcome">
        <h1 class="display-4">
          <span class="material-symbols-outlined">person</span>
          Welcome, 
        </h1>
        <p class="lead">Here you can manage teachers, students, assignments and timetable.</p>
      </div>
        <div class="main-overview">
            <div class="overviewcard">
                <div class="icon-text">
                    <div class="icon-container">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <div class="overview-info">
                        <h4>Students</h4>
                        <p><?php echo $studentCount; ?> registered students</p>
                    </div>
                </div>
            </div>
            <div class="overviewcard">
                <div class="icon-text">
                    <div class="icon-container">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <div class="overview-info">
                        <h4>Teachers</h4>
                        <p><?php echo $teacherCount; ?> registered teachers</p>
                    </div>
                </div>
            </div>
        </div>

     
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
