<?php
require_once '../config.php';

// Fetch classes and subjects for dropdowns
$classOptions = '';
$classQuery = "SELECT ClassID, ClassName FROM Class ORDER BY ClassName";
$classResult = $conn->query($classQuery);
if ($classResult) {
    while ($class = $classResult->fetch_assoc()) {
        $classOptions .= "<option value='" . $class['ClassID'] . "'>" . htmlspecialchars($class['ClassName']) . "</option>";
    }
} else {
    echo "Error fetching classes: " . $conn->error;
}

$subjectOptions = '';
$subjectQuery = "SELECT SubjectID, Name FROM Subject ORDER BY Name";
$subjectResult = $conn->query($subjectQuery);
if ($subjectResult) {
    while ($subject = $subjectResult->fetch_assoc()) {
        $subjectOptions .= "<option value='" . $subject['SubjectID'] . "'>" . htmlspecialchars($subject['Name']) . "</option>";
    }
} else {
    echo "Error fetching subjects: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Timetable Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
    <script>
    $(document).ready(function() {
        var calendar = $('#calendar').fullCalendar({
            defaultView: 'agendaWeek',
            minTime: "07:30:00",
            maxTime: "18:00:00",
            slotDuration: '00:30:00',
            allDaySlot: false,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            editable: true,
            selectable: true,
            selectHelper: true,
            select: function(start, end) {
                var title = prompt('Enter Event Title:');
                var classID = $('#classSelect').val();
                var subjectID = $('#subjectSelect').val();
                if (title && classID && subjectID) {
                    var eventData = {
                        title: title,
                        start: moment(start).format("YYYY-MM-DD HH:mm:ss"),
                        end: moment(end).format("YYYY-MM-DD HH:mm:ss"),
                        classID: classID,
                        subjectID: subjectID
                    };
                    $.post('add_event.php', eventData, function(response) {
                        if (response.status === 'success') {
                            calendar.fullCalendar('renderEvent', eventData, true); // stick? = true
                            alert("Added Successfully");
                        } else {
                            alert(response.message);
                        }
                    }, 'json');
                }
                calendar.fullCalendar('unselect');
            },
            eventLimit: true, // allow "more" link when too many events
        });
    });
    </script>
</head>
<body>
    <div class="sidebar">
        <?php include '../components/admin_sidebar.php'; ?>
    </div>
    <div class="main-container" style="margin-left: 260px; padding: 20px;">
        <h1 class="timetable_title">Timetable Management</h1>
        <div class="dropdown-container">
            <label for="classSelect">Select Class:</label>
            <select id="classSelect" class="dropdown">
                <option value="">Select Class</option>
                <?= $classOptions ?>
            </select>
            <label for="subjectSelect">Select Subject:</label>
            <select id="subjectSelect" class="dropdown">
                <option value="">Select Subject</option>
                <?= $subjectOptions ?>
            </select>
        </div>

        <button class="timetable_button">Add Event</button>
        <button class="timetable_button2">View Timetable</button>

        <div class="new_timetable">
            <div class="new_timetable_entries">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</body>
</html>
