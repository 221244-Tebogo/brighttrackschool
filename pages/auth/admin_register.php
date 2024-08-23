<?php
require_once '../../config.php'; // Adjust the path as necessary

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = ($email === 'admin@brighttrack.co.za') ? 
               "INSERT INTO Admin (UserName, Email, Password) VALUES (?, ?, ?)" :
               "INSERT INTO Teacher (FirstName, LastName, Email, Gender, DOB, Marks, Projects) VALUES (?, ?, ?, 'Unspecified', '2000-01-01', 0, 0)";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
    $conn->close();
}
?>

<form method="POST" action="admin_register.php">
    <label>Username:<input type="text" name="username" required></label>
    <label>Email:<input type="email" name="email" required></label>
    <label>Password:<input type="password" name="password" required></label>
    <label>Confirm Password:<input type="password" name="confirmPassword" required></label>
    <button type="submit">Register</button>
</form>
