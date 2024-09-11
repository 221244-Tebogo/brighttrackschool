<?php
session_start();
if (!isset($_SESSION['TeacherID'])) {
    header("Location: /brighttrackschool/auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Assignment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
</head>
<body>
    <div class="container-grid">
        <aside class="navbar-left">
            <?php include('../components/teacher_sidebar.php'); ?>
        </aside>

        <main class="main-content">
            <div class="welcome">
                <h1 class="display-4">Create New Assignment</h1>
            </div>
            <div class="assignment-form">
                <form action="create_assignment_process.php" method="POST">
                    <div class="form-group">
                        <label for="name">Assignment Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="dueDate">Due Date:</label>
                        <input type="date" class="form-control" id="dueDate" name="dueDate" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="subjectID">Subject:</label>
                        <select class="form-control" id="subjectID" name="subjectID" required>
                            <?php
                            require '../config.php';
                            $sql = "SELECT SubjectID, Name FROM Subject ORDER BY Name";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['SubjectID']}'>{$row['Name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="classID">Class:</label>
                        <select class="form-control" id="classID" name="classID" required>
                            <?php
                            $sql = "SELECT ClassID, ClassName FROM Class ORDER BY ClassName";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['ClassID']}'>{$row['ClassName']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Assignment</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
