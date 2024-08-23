<aside id="sidebar" class="sidebar" style="width: 60px;">
    <div class="handle" onmousedown="initResize(event)"></div>
    <div class="sidebar-inner">
        <header class="sidebar-header">
            <button type="button" class="sidebar-burger">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <img src="../../assets/images/Logo.svg" class="sidebar-logo" alt="Logo">
        </header>
        <nav class="sidebar-menu">
            <?php
            $navItems = [
                "school" => "Classes",
                "schedule" => "Timetable",
                "assessment" => "Reports",
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
    <link rel="stylesheet" href="../../assets/css/sidebar.css"> <!-- Link to sidebar CSS -->
</head>
<body>
    <aside id="sidebar" class="sidebar">
        <div class="handle" onmousedown="initResize(event)"></div>
        <div class="sidebar-inner">
            <header class="sidebar-header">
                <img src="../../assets/images/Logo.svg" class="sidebar-logo" alt="Logo">
            </header>
            <nav class="sidebar-menu">
                <?php
                $navItems = [
                    "school" => "Classes",
                    "schedule" => "Timetable",
                    "assessment" => "Reports",
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
</body>
</html>
