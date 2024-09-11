<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['StudentID'])) {
    header("Location: /brighttrackschool/student/login.php");
    exit;
}

$studentID = $_SESSION['StudentID'];
$studentName = "";
$assignedClasses = [];
$currentDate = date('Y-m-d');

include('../config.php');

// Fetch the student's name
$stmt = $conn->prepare("SELECT FirstName FROM Student WHERE StudentID = ?");
$stmt->bind_param("i", $studentID);
$stmt->execute();
$stmt->bind_result($studentName);
$stmt->fetch();
$stmt->close();

// Fetch the student's assigned classes, including the grade
$sql = "SELECT c.ClassName, c.Grade, s.Name AS SubjectName, t.Day, t.StartTime, t.EndTime 
        FROM Timetable t
        JOIN Class c ON t.ClassID = c.ClassID
        JOIN Subject s ON t.SubjectID = s.SubjectID
        JOIN Enrollment e ON c.ClassID = e.ClassID
        WHERE e.StudentID = ? ORDER BY t.Day, t.StartTime";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $assignedClasses[] = $row;
    }
}
$stmt->close();
$conn->close();

include_once('../components/calendar.php');
use brighttrackschool\Components\Calendar;

$calendar = new \brighttrackschool\Components\Calendar($currentDate);

foreach ($assignedClasses as $class) {
    $eventDate = date('Y-m-d', strtotime('next ' . $class['Day']));
    $calendar->add_event($class['ClassName'] . ' - ' . $class['SubjectName'], $eventDate);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Timetable</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;700&display=swap">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/calendar.css">
</head>
<body>
  <div class="container-grid">
    <aside class="navbar-left">
      <?php include('../components/sidebar.php'); ?>
    </aside>

    <main class="main-content">
      <div class="welcome">
        <h1 class="display-4">
          <span class="material-symbols-outlined">calendar_today</span>
          Your Timetable, <?php echo htmlspecialchars($studentName); ?>!
        </h1>
        <p class="lead">Here is your timetable. You can see all your assigned classes.</p>
      </div>

      <div class="calendar-container">
        <div class="calendar">
          <?php echo $calendar; ?> 
        </div>
      </div>
    </main>

    <aside id="sidebar-right">
      <div class="calendar_events">
        <p class="ce_title">Upcoming Classes</p>
        <?php if (count($assignedClasses) > 0): ?>
          <?php foreach ($assignedClasses as $class): ?>
            <div class="event_item">
              <div class="ei_Copy"><?php echo htmlspecialchars($class['StartTime']); ?> - <?php echo htmlspecialchars($class['EndTime']); ?></div>
              <div class="ei_Copy">Grade <?php echo htmlspecialchars($class['Grade']); ?> - <?php echo htmlspecialchars($class['SubjectName']); ?> (<?php echo htmlspecialchars($class['Day']); ?>)</div>
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
