<?php
session_start();
if (!isset($_SESSION['TeacherID'])) {
    header("Location: /brighttrackschool/auth/login.php");
    exit;
}

$teacherID = $_SESSION['TeacherID'];
$teacher = null;
include('../config.php');

$stmt = $conn->prepare("SELECT * FROM Teacher WHERE TeacherID = ?");
$stmt->bind_param("i", $teacherID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 1) {
    $teacher = $result->fetch_assoc();
} else {
    echo "<p>Teacher profile not found.</p>";
}
$stmt->close();

$successMessage = '';
$profileImagePath = $teacher['ProfileImage'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $_POST['bio'];

    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        include('profile_upload.php');
    }

    $sql = "UPDATE Teacher SET Bio = ?, ProfileImage = ? WHERE TeacherID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $bio, $profileImagePath, $teacherID);
    if ($stmt->execute()) {
        $successMessage = 'Profile updated successfully!';
    } else {
        echo "<p>Error updating profile: " . $stmt->error . "</p>";
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>

        <form action="profile_update.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio" class="form-control"><?= htmlspecialchars($teacher['Bio']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="profileImage">Profile Image:</label>
                <input type="file" id="profileImage" name="profileImage" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>
