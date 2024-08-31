<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('../config.php');

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Step 1: Get the user type from UserTypeMapping
    $stmt1 = $conn->prepare("SELECT UserType FROM UserTypeMapping WHERE Email = ?");
    if (!$stmt1) {
        $errorMessage = "Error preparing UserTypeMapping query: " . $conn->error;
    } else {
        $stmt1->bind_param("s", $email);
        $stmt1->execute();
        $result = $stmt1->get_result();

        if ($result->num_rows === 1) {
            $userType = $result->fetch_assoc()['UserType'];
            $stmt1->close();  // Close the first statement

            // Step 2: Prepare the query based on the user type
            $userQuery = "";
            if ($userType === 'admin') {
                $userQuery = "SELECT Password, UserName FROM Admin WHERE Email = ?";
            } elseif ($userType === 'student') {
                $userQuery = "SELECT Password, FirstName, LastName FROM Student WHERE Email = ?";
            } elseif ($userType === 'teacher') {
                $userQuery = "SELECT Password, FirstName, LastName FROM Teacher WHERE Email = ?";
            } else {
                $errorMessage = "Invalid user type.";
            }

            if (!empty($userQuery)) {
                $stmt2 = $conn->prepare($userQuery);
                if (!$stmt2) {
                    $errorMessage = "Error preparing user query: " . $conn->error;
                } else {
                    $stmt2->bind_param("s", $email);
                    $stmt2->execute();
                    $passwordResult = $stmt2->get_result();

                    if ($passwordResult->num_rows === 1) {
                        $userData = $passwordResult->fetch_assoc();
                        if (password_verify($password, $userData['Password'])) {
                            $_SESSION['email'] = $email;
                            $_SESSION['user_name'] = isset($userData['UserName']) ? $userData['UserName'] : $userData['FirstName'] . ' ' . $userData['LastName'];
                            $_SESSION['user_type'] = $userType;

                            
                            switch ($userType) {
                                case 'admin':
                                    header("Location: ../admin/admin_dashboard.php");
                                    break;
                                case 'student':
                                    header("Location: ../student/index.php");
                                    break;
                                case 'teacher':
                                    header("Location: ../teacher/dashboard.php");
                                    break;
                            }
                            exit;
                        } else {
                            $errorMessage = "Invalid password.";
                        }
                    } else {
                        $errorMessage = "No such user found.";
                    }
                    $stmt2->close();
                }
            }
        } else {
            $errorMessage = "No user found with that email.";
            $stmt1->close();
        }
    }
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BrightTrack School</title>
    <link rel="stylesheet" href="../assets/css/login.css">
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
            <img src="../assets/images/Logo.svg" alt="BrightTrack School Logo" class="onboarding-logo">
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
        document.getElementById('errorModal').style.display = 'block';
        function closeModal() {
            document.getElementById('errorModal').style.display = 'none';
        }
    </script>
    <?php endif; ?>
</body>
</html>
