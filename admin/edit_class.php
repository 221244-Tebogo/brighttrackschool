<?php
require_once '../config.php';

if (isset($_GET['id'])) {
    $classID = $_GET['id'];
    $sql = "SELECT * FROM Class WHERE ClassID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $classID);
    $stmt->execute();
    $result = $stmt->get_result();
    $class = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $classID = $_POST['ClassID'];
    $className = $_POST['ClassName'];
    $grade = $_POST['Grade'];
    $teacherID = $_POST['TeacherID'];

    $sql = "UPDATE Class SET ClassName = ?, Grade = ?, TeacherID = ? WHERE ClassID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $className, $grade, $teacherID, $classID);
    if ($stmt->execute()) {
        header("Location: ../class_list.php");
        exit;
    } else {
        echo "Error updating class: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Class</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/calendar.css">
</head>
<body>
</aside>
    <div class="container-grid">
    
    <aside class="navbar-left">
      <?php include('../components/admin_sidebar.php'); ?>
    </aside>
    
<div class="content">
    <h1>Edit Class</h1>
    <form method="post" action="edit_class.php?id=<?= $class['ClassID'] ?>">
        <input type="hidden" name="ClassID" value="<?= $class['ClassID'] ?>">
        <input type="text" name="ClassName" value="<?= $class['ClassName'] ?>" required>
        <input type="text" name="Grade" value="<?= $class['Grade'] ?>" required>
        <select name="TeacherID" required>
            <?php
            $sql = "SELECT TeacherID, FirstName, LastName FROM Teacher ORDER BY LastName, FirstName";
            $teachers = $conn->query($sql);
            while ($teacher = $teachers->fetch_assoc()) {
                $selected = $teacher['TeacherID'] == $class['TeacherID'] ? "selected" : "";
                echo "<option value='{$teacher['TeacherID']}' $selected>" . htmlspecialchars($teacher['FirstName'] . " " . $teacher['LastName']) . "</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary">Update Class</button>
    </form>
</div>
</body>
</html>
