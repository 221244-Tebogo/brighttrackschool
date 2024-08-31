<?php
require_once '../config.php';
$teacherID = $_GET['id'] ?? null;
$teacher = null;

if ($teacherID) {
    $stmt = $conn->prepare("SELECT * FROM Teacher WHERE TeacherID = ?");
    $stmt->bind_param("i", $teacherID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $teacher = $result->fetch_assoc();
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];

    $sql = "UPDATE Teacher SET FirstName=?, LastName=?, Email=? WHERE TeacherID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $firstName, $lastName, $email, $teacherID);
    if ($stmt->execute()) {
        echo "<p>Teacher profile updated successfully!</p>";
        // Refresh data
        $teacher['FirstName'] = $firstName;
        $teacher['LastName'] = $lastName;
        $teacher['Email'] = $email;
    } else {
        echo "<p>Error updating teacher profile: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Teacher Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="content">
        <h1>Edit Teacher Profile</h1>
        <?php if ($teacher): ?>
        <form action="edit_teacher.php?id=<?= htmlspecialchars($teacherID) ?>" method="post">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" value="<?= htmlspecialchars($teacher['FirstName']) ?>" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" value="<?= htmlspecialchars($teacher['LastName']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($teacher['Email']) ?>" required>

            <button type="submit">Update Profile</button>
        </form>
        <?php else: ?>
        <p>Teacher profile not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
