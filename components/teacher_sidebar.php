<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Nav</title>
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400&display=swap">
</head>
<body>
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-inner">
            <header class="sidebar-header">
                <img src="../assets/images/Logo.svg" class="sidebar-logo" alt="Logo">
            </header>

            <nav class="sidebar-menu">
                <button type="button" class="sidebar-button" onclick="window.location.href='dashboard.php'">
                    <span class="material-symbols-outlined">dashboard</span>
                    <p class="sidebar-text">Dashboard</p>
                </button>

                <button type="button" class="sidebar-button" onclick="window.location.href='timetable.php'">
                    <span class="material-symbols-outlined">schedule</span>
                    <p class="sidebar-text">Timetable</p>
                </button>

                <button type="button" class="sidebar-button" onclick="window.location.href='create_assignment.php'">
                    <span class="material-symbols-outlined">assignment</span>
                    <p class="sidebar-text">Assignments</p>
                </button>

                <button type="button" class="sidebar-button" onclick="window.location.href='profile.php'">
                    <span class="material-symbols-outlined">person</span>
                    <p class="sidebar-text">Profile</p>
                </button>

                <button type="button" class="sidebar-button" onclick="window.location.href='../auth/logout.php'">
                    <span class="material-symbols-outlined">logout</span>
                    <p class="sidebar-text">Logout</p>
                </button>
            </nav>
        </div>
    </aside>
</body>
</html>
