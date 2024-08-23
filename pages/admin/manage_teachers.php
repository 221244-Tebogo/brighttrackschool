<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require '../../config.php';  // Correct path from the admin directory

// Handle form submission for adding a teacher
if (isset($_POST['submit'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $marks = $_POST['marks'];
    $projects = $_POST['projects'];

    $sql = "INSERT INTO Teacher (FirstName, LastName, Email, Gender, DOB, Marks, Projects) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $firstName, $lastName, $email, $gender, $dob, $marks, $projects);

    if ($stmt->execute()) {
        $message = "Teacher added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Teachers</title>
</head>
<body>
    <h1>Manage Teachers</h1>
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>
    <form method="POST" action="manage_teachers.php">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br>
        <label for="marks">Marks:</label>
        <input type="text" id="marks" name="marks"><br>
        <label for="projects">Projects:</label>
        <input type="number" id="projects" name="projects"><br>
        <button type="submit" name="submit">Add Teacher</button>
    </form>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
