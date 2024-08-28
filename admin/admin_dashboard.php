<?php
session_start();
include('../components/admin_sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class List</title>
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/class_list.css"> <!-- Add specific CSS for class list -->
</head>
<>
<div class="sidebar">
    <?php include '../components/admin_sidebar.php'; ?>
</div>
<div class="content">
    <h1>Add a New Class</h1>

         <!-- Hero Blurb -->
            <div class="hero-blurb">
            <div class="main-content">
            <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

          
                <h2>Manage Classes</h2>
                <p>Here you can view, add, and manage all the classes. Use the form below to add new classes to the system.</p>
                <button id="addNewClass" class="button">Add New Class</button>
            </div>

            <!-- Class List Table -->
            <div class="class-list">
                <h2>Class List</h2>
                <!-- Add your code to list classes here -->
                <!-- Example: -->
                <table>
                    <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mathematics</td>
                            <td>Grade 5</td>
                            <td><a href="edit_class.php?id=1">Edit</a> | <a href="delete_class.php?id=1">Delete</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <!-- Modal -->
    <div id="addClassModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Class</h2>
            <form action="add_class.php" method="post">
                <label for="className">Class Name:</label>
                <input type="text" id="className" name="className" required>
                <label for="grade">Grade:</label>
                <input type="number" id="grade" name="grade" required>
                <button type="submit" class="submit-button">Submit</button>
            </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById("addClassModal");
        var btn = document.getElementById("addNewClass");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
