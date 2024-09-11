<?php
session_start();
if (!isset($_SESSION['TeacherID'])) {
    header("Location: /brighttrackschool/auth/login.php");
    exit;
}

$teacherID = $_SESSION['TeacherID'];
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
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body>
    <div class="container-grid">
        <aside class="navbar-left">
            <?php include('../components/teacher_sidebar.php'); ?>
        </aside>

        <main class="main-content">
            <div class="welcome">
                <h1 class="display-4">
                    <span class="material-symbols-outlined">person</span>
                    Edit your profile here, <?= htmlspecialchars($teacher['FirstName']) ?>!
                </h1>
            </div>
            <div class="profile-header">
                <h1>Teacher Profile</h1>
            </div>
            <div class="profile-overview">
                <?php if ($teacher): ?>
                    <div class="profile-details">
                        <p><strong>Name:</strong> <?= htmlspecialchars($teacher['FirstName']) ?> <?= htmlspecialchars($teacher['LastName']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($teacher['Email']) ?></p>
                        <p><strong>Bio:</strong> <?= htmlspecialchars($teacher['Bio']) ?></p>
                        <a href="profile_update.php" class="btn btn-primary">Edit Profile</a>
                    </div>
                <?php else: ?>
                    <p>Profile not found.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php include('../components/footer.php'); ?>
</body>
</html>
