<aside id="sidebar" class="sidebar" style="width: 60px;">
    <div class="handle" onmousedown="initResize(event)"></div>
    <div class="sidebar-inner">
        <header class="sidebar-header">
           
            <img src="../assets/images/Logo.svg" class="sidebar-logo" alt="Logo">
            <div class="user-profile">
                <img src="../assets/images/user_placeholder.png" class="user-icon" alt="User Icon">
                <p class="user-name">Welcome, <?php echo $username; ?></p>
            </div>
        </header>
        <nav class="sidebar-menu">
            <?php
            $navItems = [
                "Dashboard" => "Dashboard",
                "schedule" => "Timetable",
                "assignment" => "Assignment",
                "logout" => "Logout"
            ];
            
            foreach ($navItems as $icon => $label) {
                echo '
                <button type="button" class="sidebar-button">
                    <span class="material-symbols-outlined">'.$icon.'</span>
                    <p class="sidebar-text">'.ucfirst($label).'</p>
                </button>';
            }
            ?>
        </nav>
    </div>
</aside>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
</head>
<body>
<aside id="sidebar" class="sidebar">
    <div class="handle" onmousedown="initResize(event)"></div>
    <div class="sidebar-inner">
        <header class="sidebar-header">
            <img src="../assets/images/Logo.svg" class="sidebar-logo" alt="Logo">

        </header>
        <nav class="sidebar-menu">
            <?php
            $navItems = [
                "dashboard" => ["label" => "Dashboard", "link" => "../student/index.php"],
                "schedule" => ["label" => "Timetable", "link" => "../student/timetable.php"],
                "assignment" => ["label" => "Assignments", "link" => "../student/assignment.php"],
                "logout" => ["label" => "Logout", "link" => "../auth/logout.php"]
            ];
            
            foreach ($navItems as $icon => $item) {
                echo '
                <a href="' . $item['link'] . '" class="sidebar-button">
                    <span class="material-symbols-outlined">'.$icon.'</span>
                    <p class="sidebar-text">'.ucfirst($item['label']).'</p>
                </a>';
            }
            ?>
        </nav>
    </div>
</aside>



</body>
</html>
