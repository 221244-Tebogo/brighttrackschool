<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assignments</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container-grid">
        
        <aside class="navbar-left">
            <?php include('../components/teacher_sidebar.php'); ?>
        </aside>

        <main class="main-content">
            <div class="welcome">
                <h1 class="display-4">
                    <span class="material-symbols-outlined">description</span>
                    View Assignments
                </h1>
                <p class="lead">Manage your assignments and view submissions here.</p>
            </div>

            <div class="assignment-list">
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>Assignment Name</th>
                            <th>Due Date</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    session_start();
                    if (!isset($_SESSION['TeacherID'])) {
                        header("Location: /brighttrackschool/auth/login.php");
                        exit();
                    }

                    require '../config.php';
                    $teacherID = $_SESSION['TeacherID'];

                    $query = "SELECT * FROM Assignment WHERE TeacherID = ? ORDER BY DueDate DESC";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $teacherID);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($assignment = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($assignment['Name']) . "</td>";
                            echo "<td>" . htmlspecialchars($assignment['DueDate']) . "</td>";
                            echo "<td>" . htmlspecialchars($assignment['Description']) . "</td>";
                            echo "<td>
                                    <button onclick='editAssignment(" . $assignment['AssignmentID'] . ")' class='btn btn-primary'>Edit</button> 
                                    <button onclick='deleteAssignment(" . $assignment['AssignmentID'] . ")' class='btn btn-danger'>Delete</button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No assignments found.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </main>

        <aside id="sidebar-right">
        <div class="sidebar-inner">
                <div class="user-profile">
                    <a class="nav-link" href="profile.php">
                        <img src="<?php echo (!empty($profileImage)) ? '../assets/images/' . htmlspecialchars($profileImage) : '../assets/images/default-profile.png'; ?>" alt="User" class="user-image"/>
                        <p><?php echo htmlspecialchars($teacherName); ?></p>
                    </a>
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

            <div class="sidebar-inner">
                <div class="calendar_events">
                    <p class="ce_title">Upcoming Assignments</p>
                    <?php if ($result->num_rows > 0): ?>
                        <?php
                        
                        foreach ($result as $assignment): ?>
                            <div class="event_item">
                                <div class="ei_Copy"><?php echo htmlspecialchars($assignment['Name']); ?></div>
                                <div class="ei_Copy"><?php echo htmlspecialchars($assignment['DueDate']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="event_item">
                            <div class="ei_Title">No upcoming assignments</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </aside>
    </div>

    <script>
    function editAssignment(id) {
        window.location.href = 'edit_assignment.php?AssignmentID=' + id;
    }

    function deleteAssignment(id) {
        if (confirm('Are you sure you want to delete this assignment?')) {
            $.post('delete_assignment.php', { AssignmentID: id }, function(data) {
                if (data.success) {
                    alert('Assignment deleted successfully.');
                    window.location.reload();
                } else {
                    alert('Failed to delete assignment.');
                }
            }, 'json');
        }
    }
    </script>

    <?php
    $conn->close();
    include('../components/footer.php');
    ?>
</body>
</html>
