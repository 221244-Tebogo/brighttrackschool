<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['TeacherID'])) {
    header("Location: /brighttrackschool/auth/login.php");
    exit;
}

$teacherID = $_SESSION['TeacherID'];
$teacherName = "";
$assignedClasses = [];
$currentDate = date('Y-m-d');

include('../config.php');
include_once('../components/calendar.php');

use brighttrackschool\Components\Calendar;

$stmt = $conn->prepare("SELECT FirstName FROM Teacher WHERE TeacherID = ?");
$stmt->bind_param("i", $teacherID);
$stmt->execute();
$stmt->bind_result($teacherName);
$stmt->fetch();
$stmt->close();

$sql = "SELECT c.ClassName, c.Grade, s.Name AS SubjectName, t.Day, t.StartTime, t.EndTime 
        FROM Timetable t
        JOIN Class c ON t.ClassID = c.ClassID
        JOIN Subject s ON t.SubjectID = s.SubjectID
        WHERE t.TeacherID = ? ORDER BY t.Day, t.StartTime";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacherID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $assignedClasses[] = $row;
    }
}
$stmt->close();
$conn->close();

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
  <title>Teacher Timetable</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/calendar.css">
</head>
<body>
  <div class="container-grid">
    <aside class="navbar-left">
      <?php include('../components/teacher_sidebar.php'); ?>
    </aside>

    <main class="main-content">
      <div class="welcome">
        <h1 class="display-4">
          <span class="material-symbols-outlined">calendar_today</span>
          Your Timetable, <?php echo htmlspecialchars($teacherName); ?>!
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
            <div class="user-profile">
                <a class="nav-link" href="profile.php">
                    <div class="icon-text">
                        <div class="icon-container">
                            <?php if (!empty($profileImage)): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" class="user-image" style="border-radius:50%; width: 48px; height: 48px;">
                            <?php else: ?>
                                <span class="material-symbols-outlined" style="font-size: 48px;">person</span>
                            <?php endif; ?>
                        </div>
                        <div class="overview-info">
                            <p><?php echo htmlspecialchars($teacherName); ?></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="small-calendar">
                <div class="day-names">
                    <?php foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day): ?>
                        <span class="day"><?php echo $day; ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="days">
                    <?php for ($i = 1; $i <= date('t'); $i++): ?>
                        <span class="day_num"><?php echo $i; ?></span>
                    <?php endfor; ?>
                </div>
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
