<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes and Subjects</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="sidebar">
    <?php include '../components/admin_sidebar.php'; ?>
</div>
<div class="content">

<h1>Manage Classes</h1>
    <a href="add_class.php" class="add-new">Add New Class</a>
    <table>
        <thead>
            <tr>
                <th>Class Name</th>
                <th>Grade</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
require_once '../config.php';
$sql = "SELECT * FROM Class";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['ClassName']}</td>
                <td>{$row['Grade']}</td>
                <td>
                    <a href='actions/edit_class.php?id={$row['ClassID']}' class='edit'>Edit</a>
                    <a href='actions/delete_class.php?id={$row['ClassID']}' class='delete'>Delete</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='3'>No classes found</td></tr>";
}
?>

        </tbody>
    </table>

    <h1>Manage Subjects</h1>
    <a href="add_subject.php" class="new_timetable_entries">Add New Subject</a>
    <table>
        <thead>
            <tr>
                <th>Subject Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM Subject";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['Name']}</td>
                            <td>
                                <a href='edit_subject.php?id={$row['SubjectID']}' class='edit'>Edit</a>
                                <a href='delete_subject.php?id={$row['SubjectID']}' class='delete'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No subjects found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>
    
</body>
</html>