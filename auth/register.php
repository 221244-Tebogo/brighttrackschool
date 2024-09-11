<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $conn->real_escape_string($_POST['user_type']);
    $gender = ($user_type == 'student') ? $conn->real_escape_string($_POST['gender']) : 'Other';
    $dob = ($user_type == 'student' && isset($_POST['dob'])) ? $conn->real_escape_string($_POST['dob']) : '2000-01-01';

    $sql = '';
    if ($user_type == 'admin') {
        $user_name = $first_name . ' ' . $last_name;
        $sql = "INSERT INTO Admin (UserName, Email, Password) VALUES (?, ?, ?)";
    } elseif ($user_type == 'student') {
        $sql = "INSERT INTO Student (FirstName, LastName, Email, Gender, DOB, Password) VALUES (?, ?, ?, ?, ?, ?)";
    } elseif ($user_type == 'teacher') {
        $sql = "INSERT INTO Teacher (FirstName, LastName, Email, Gender, Password) VALUES (?, ?, ?, ?, ?)";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "MySQL prepare error: " . $conn->error;
        exit;
    }

    if ($user_type == 'admin') {
        $stmt->bind_param("sss", $user_name, $email, $password);
    } elseif ($user_type == 'student') {
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $gender, $dob, $password);
    } elseif ($user_type == 'teacher') {
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $gender, $password);
    }

    if (!$stmt->execute()) {
        echo "Error registering user: " . $stmt->error;
    } else {
        $mapping_sql = "INSERT INTO UserTypeMapping (Email, UserType) VALUES (?, ?)";
        $mapping_stmt = $conn->prepare($mapping_sql);
        if ($mapping_stmt) {
            $mapping_stmt->bind_param("ss", $email, $user_type);
            if (!$mapping_stmt->execute()) {
                echo "Error inserting into UserTypeMapping: " . $conn->error;
            } else {
                header("Location: login.php");
                exit;
            }
            $mapping_stmt->close();
        } else {
            echo "Error preparing mapping statement: " . $conn->error;
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BrightTrack School</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/register.css">

</head>

<body>
<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form action="register.php" method="POST">
            <h1>Create Account</h1>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <select name="user_type" id="user_type" required onchange="showFields()">
                <option value="">Select User Type</option>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
            </select>

            <div id="studentFields" style="display: none;">
                <select name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <input type="date" name="dob" placeholder="Date of Birth">
            </div>

            <button type="submit" class="login-button">Register</button>
            <a href="login.php" class="link">Already have an account? Sign In</a>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-right">
                <img src="../assets/images/Logo.svg" alt="BrightTrack School Logo" class="onboarding-logo">
                <h1>Welcome to BrightTrack!</h1>
                <p>Create your account to start your learning journey with us today.</p>
            </div>
        </div>
    </div>
</div>


<script>
    function showFields() {
        var userType = document.getElementById('user_type').value;
        document.getElementById('studentFields').style.display = (userType === 'student') ? 'block' : 'none';
        document.getElementById('teacherFields').style.display = (userType === 'teacher') ? 'block' : 'none';
        document.getElementById('adminFields').style.display = (userType === 'admin') ? 'block' : 'none';
    }
</script>
</body>
</html>
