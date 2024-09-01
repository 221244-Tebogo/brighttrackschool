<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/sidebar.css"> <!-- Ensure this path is correct -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400&display=swap">
</head>
<body>
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-inner">
            <header class="sidebar-header">
                <button type="button" class="sidebar-burger">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <img src="../assets/images/Logo.svg" class="sidebar-logo" alt="Logo">
            </header>
            <nav class="sidebar-menu">
                <button type="button" class="sidebar-button" onclick="window.location.href='dashboard.php'">
                    <span class="material-symbols-outlined">dashboard</span>
                    <p class="sidebar-text">Dashboard</p>
                </button>
                <button type="button" class="sidebar-button" onclick="window.location.href='manage_teachers.php'">
                    <span class="material-symbols-outlined">person</span>
                    <p class="sidebar-text">Teachers</p>
                </button>
                <button type="button" class="sidebar-button" onclick="window.location.href='manage_students.php'">
                    <span class="material-symbols-outlined">school</span>
                    <p class="sidebar-text">Students</p>
                </button>
                <button type="button" class="sidebar-button" onclick="window.location.href='class_list.php'">
                    <span class="material-symbols-outlined">class</span>
                    <p class="sidebar-text">Classes</p>
                </button>
                <button type="button" class="sidebar-button" onclick="window.location.href='assign_timetable.php'">
                    <span class="material-symbols-outlined">schedule</span>
                    <p class="sidebar-text">Timetable</p>
                </button>
                <button type="button" class="sidebar-button" onclick="window.location.href='add_subject.php'">
                    <span class="material-symbols-outlined">book</span>
                    <p class="sidebar-text">Subjects</p>
                </button>
                <button type="button" class="sidebar-button" onclick="window.location.href='../auth/logout.php'">
                    <span class="material-symbols-outlined">logout</span>
                    <p class="sidebar-text">Logout</p>
                </button>
            </nav>
        </div>
    </aside>

    <script>
        // Optional: JavaScript for responsive toggle behavior
        document.querySelector('.sidebar-burger').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
    </script>
</body>
</html>
