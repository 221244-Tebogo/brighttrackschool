<?php
session_start(); // Start the session
include('../../config.php'); // Include your database connection

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Query to find the user in UserTypeMapping table
    $userCheckSql = "SELECT UserType FROM UserTypeMapping WHERE Email = '$email'";
    $result = $conn->query($userCheckSql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userType = $row['UserType'];

        // Check the password against the correct table
        if ($userType == 'admin') {
            $sql = "SELECT Password, UserName FROM Admin WHERE Email = '$email'";
        } elseif ($userType == 'student') {
            $sql = "SELECT Password, FirstName, LastName FROM Student WHERE Email = '$email'";
        } elseif ($userType == 'teacher') {
            $sql = "SELECT Password, FirstName, LastName FROM Teacher WHERE Email = '$email'";
        }

        $passwordResult = $conn->query($sql);

        if ($passwordResult->num_rows > 0) {
            $passwordRow = $passwordResult->fetch_assoc();
            $hashedPassword = $passwordRow['Password'];
            $userName = $passwordRow['UserName'] ?? $passwordRow['FirstName'] . ' ' . $passwordRow['LastName'];

            // Verify password
            if (password_verify($password, $hashedPassword)) {
                // Password is correct, set session variables and redirect
                $_SESSION['email'] = $email;
                $_SESSION['user_name'] = $userName;
                $_SESSION['user_type'] = $userType;

                // Redirect based on user type
                if ($userType == 'admin') {
                    header("Location: /brighttrackschool/pages/admin/dashboard.php");
                } elseif ($userType == 'student') {
                    header("Location: /brighttrackschool/pages/student/dashboard.php");
                } elseif ($userType == 'teacher') {
                    header("Location: /brighttrackschool/pages/teacher/dashboard.php");
                }
                exit(); // Ensure no further code is executed
            } else {
                $errorMessage = "Invalid password.";
            }
        } else {
            $errorMessage = "Error finding user in the database.";
        }
    } else {
        $errorMessage = "No user found with that email.";
    }

    $conn->close(); // Close the database connection
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BrightTrack School</title>
    <link rel="stylesheet" href="../../assets/css/login.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="login-container" id="container">
        <div class="login-form">
            <h1>Login</h1>
            <form action="login.php" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="login-button">Login</button>
            </form>
            <a href="register.php" class="link">Don't have an account? Register</a>
        </div>
        <div class="onboarding">
        <img src="../../assets/images/Logo.svg" alt="BrightTrack School Logo" class="onboarding-logo">
            <h2>Welcome Back to BrightTrack School!</h2>
            <p>We're glad to see you again. Log in to access your account and continue your learning journey.</p>
        </div>
    </div>

    <?php if ($errorMessage): ?>
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p><?php echo $errorMessage; ?></p>
        </div>
    </div>
    <script>
        // Show the modal
        document.getElementById('errorModal').style.display = 'block';

        // Close the modal
        function closeModal() {
            document.getElementById('errorModal').style.display = 'none';
        }
    </script>
    <?php endif; ?>
</body>
</html>
