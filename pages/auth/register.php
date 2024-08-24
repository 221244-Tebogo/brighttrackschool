<?php
include('../../config.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the posted data and sanitize it
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT); // Hash the password
    $user_type = $conn->real_escape_string($_POST['user_type']);

    // Initialize the SQL query
    $sql = '';

    // Handle registration based on the selected user type
    if ($user_type == 'admin') {
        $sql = "INSERT INTO Admin (UserName, Email, Password) VALUES ('$first_name $last_name', '$email', '$password')";
    } elseif ($user_type == 'student') {
        $gender = $conn->real_escape_string($_POST['gender']);
        $dob = $conn->real_escape_string($_POST['dob']);
        $sql = "INSERT INTO Student (FirstName, LastName, Email, Gender, DOB) VALUES ('$first_name', '$last_name', '$email', '$gender', '$dob')";
    } elseif ($user_type == 'teacher') {
        $gender = $conn->real_escape_string($_POST['gender']);
        $sql = "INSERT INTO Teacher (FirstName, LastName, Email, Gender) VALUES ('$first_name', '$last_name', '$email', '$gender')";
    }

    // Execute the query and insert into UserTypeMapping table
    if ($conn->query($sql) === TRUE) {
        // Insert into UserTypeMapping table
        $mapping_sql = "INSERT INTO UserTypeMapping (Email, UserType) VALUES ('$email', '$user_type')";
        if ($conn->query($mapping_sql) === TRUE) {
            header("Location: login.php"); // Redirect to login page after successful registration
            exit(); // Ensure no further code is executed
        } else {
            echo "Error inserting into UserTypeMapping: " . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close(); // Close the database connection
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BrightTrack School</title>
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>
<body>
    <div class="login-container" id="container">
        <div class="login-form">
            <h1>Create Account</h1>
            <span>or use your email for registration</span>
            <form action="register.php" method="POST" id="registrationForm">
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
                    <select name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <input type="date" name="dob" placeholder="Date of Birth">
                </div>

                <div id="teacherFields" style="display: none;">
                    <select name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div id="adminFields" style="display: none;">
                    <!-- Admin has no additional fields in this example -->
                </div>

                <button type="submit" class="login-button">Register</button>
            </form>
            <a href="#" class="link">Already have an account? Sign In</a>
        </div>
    </div>

    <script>
        function showFields() {
            const userType = document.getElementById('user_type').value;
            document.getElementById('studentFields').style.display = (userType === 'student') ? 'block' : 'none';
            document.getElementById('teacherFields').style.display = (userType === 'teacher') ? 'block' : 'none';
            document.getElementById('adminFields').style.display = (userType === 'admin') ? 'block' : 'none';
        }
    </script>
</body>
</html>

