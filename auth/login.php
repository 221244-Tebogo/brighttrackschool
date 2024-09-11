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

    $stmt1 = $conn->prepare("SELECT UserType FROM UserTypeMapping WHERE Email = ?");
    if (!$stmt1) {
        $errorMessage = "Error preparing UserTypeMapping query: " . $conn->error;
    } else {
        $stmt1->bind_param("s", $email);
        $stmt1->execute();
        $result = $stmt1->get_result();

        if ($result->num_rows === 1) {
            $userType = $result->fetch_assoc()['UserType'];
            $stmt1->close(); 


            $userQuery = "";
            if ($userType === 'admin') {
                $userQuery = "SELECT AdminID, Password, UserName FROM Admin WHERE Email = ?";
            } elseif ($userType === 'student') {
                $userQuery = "SELECT StudentID, Password, FirstName, LastName FROM Student WHERE Email = ?";
            } elseif ($userType === 'teacher') {
                $userQuery = "SELECT TeacherID, Password, FirstName, LastName FROM Teacher WHERE Email = ?";
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

                            if ($userType === 'teacher') {
                                $_SESSION['TeacherID'] = $userData['TeacherID'];
                                header("Location: ../teacher/dashboard.php");
                            } elseif ($userType === 'student') {
                                $_SESSION['StudentID'] = $userData['StudentID'];
                                header("Location: ../student/index.php");
                            } elseif ($userType === 'admin') {
                                $_SESSION['AdminID'] = $userData['AdminID'];
                                header("Location: ../admin/dashboard.php");
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container" id="container">
    <div class="form-container sign-in-container">
        <form action="login.php" method="POST">
            <h1>Login</h1>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="login-button">Login</button>
            <a href="register.php" class="link">Don't have an account? Register</a>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-right">
                <img src="../assets/images/Logo.svg" alt="BrightTrack School Logo" class="onboarding-logo">
                <h1>Welcome Back!</h1>
                <p>We're glad to see you again. Log in to access your account and continue your learning journey.</p>
            </div>
        </div>
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
