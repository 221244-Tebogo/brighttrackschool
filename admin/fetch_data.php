<?php
$host = 'your_host';
$dbname = 'bright_track';
$username = 'your_username';
$password = 'your_password';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchTeachers() {
    global $conn;
    $sql = "SELECT TeacherID, Name FROM Teachers";
    $result = $conn->query($sql);
    $teachers = [];
    while($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
    return $teachers;
}

function fetchClasses() {
    global $conn;
    $sql = "SELECT ClassID, ClassName FROM Classes";
    $result = $conn->query($sql);
    $classes = [];
    while($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
    return $classes;
}
?>

