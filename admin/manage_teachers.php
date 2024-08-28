<?php
require_once '../config.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => 'Unknown error'];
    
    switch ($_POST['action']) {
        case 'add':
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $gender = $_POST['gender'];

            $sql = "INSERT INTO Teacher (FirstName, LastName, Email, Gender) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssss", $firstName, $lastName, $email, $gender);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Teacher added successfully'];
                } else {
                    $response = ['success' => false, 'message' => 'Error adding teacher: ' . $stmt->error];
                }
                $stmt->close();
            }
            break;
        
        case 'edit':
            $id = $_POST['id'];
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $gender = $_POST['gender'];

            $sql = "UPDATE Teacher SET FirstName=?, LastName=?, Email=?, Gender=? WHERE TeacherID=?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssssi", $firstName, $lastName, $email, $gender, $id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Teacher updated successfully'];
                } else {
                    $response = ['success' => false, 'message' => 'Error updating teacher: ' . $stmt->error];
                }
                $stmt->close();
            }
            break;
        
        case 'delete':
            $id = $_POST['id'];
            $sql = "DELETE FROM Teacher WHERE TeacherID=?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Teacher deleted successfully'];
                } else {
                    $response = ['success' => false, 'message' => 'Error deleting teacher: ' . $stmt->error];
                }
                $stmt->close();
            }
            break;
    }
    echo json_encode($response);
    exit;
}

// Fetch all teachers for display
$sql = "SELECT TeacherID, FirstName, LastName, Email, Gender FROM Teacher";
$result = $conn->query($sql);
$teachers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Teachers</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css"></head>
<body>
<div class="sidebar">
    <?php include '../components/admin_sidebar.php'; ?>
</div>
    <div class="content">
    <h1>Manage Teachers</h1>
    <button onclick="showAddForm()">Add New Teacher</button>
    <div id="addEditForm" style="display:none;">
        <input type="hidden" id="teacherId">
        <input type="text" id="firstName" placeholder="First Name">
        <input type="text" id="lastName" placeholder="Last Name">
        <input type="email" id="email" placeholder="Email">
        <select id="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <button onclick="saveTeacher()">Save</button>
        <button onclick="hideForm()">Cancel</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teachers as $teacher): ?>
            <tr id="teacher-<?= $teacher['TeacherID'] ?>">
                <td><?= htmlspecialchars($teacher['FirstName']) ?></td>
                <td><?= htmlspecialchars($teacher['LastName']) ?></td>
                <td><?= htmlspecialchars($teacher['Email']) ?></td>
                <td><?= htmlspecialchars($teacher['Gender']) ?></td>
                <td>
                    <button onclick="editTeacher(<?= $teacher['TeacherID'] ?>)">Edit</button>
                    <button onclick="deleteTeacher(<?= $teacher['TeacherID'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
<script>
function showAddForm() {
    $('#teacherId').val('');
    $('#firstName').val('');
    $('#lastName').val('');
    $('#email').val('');
    $('#gender').val('');
    $('#addEditForm').show();
}

function hideForm() {
    $('#addEditForm').hide();
}

function editTeacher(id) {
    var row = $('#teacher-' + id);
    $('#teacherId').val(id);
    $('#firstName').val(row.find('td:eq(0)').text());
    $('#lastName').val(row.find('td:eq(1)').text());
    $('#email').val(row.find('td:eq(2)').text());
    $('#gender').val(row.find('td:eq(3)').text());
    $('#addEditForm').show();
}

function saveTeacher() {
    var id = $('#teacherId').val();
    var action = id ? 'edit' : 'add';
    $.post('manage_teachers.php', {
        action: action,
        id: id,
        first_name: $('#firstName').val(),
        last_name: $('#lastName').val(),
        email: $('#email').val(),
        gender: $('#gender').val()
    }, function(response) {
        alert(response.message);
        if (response.success) {
            location.reload(); // Reload the page to see changes
        }
    }, 'json');
}

function deleteTeacher(id) {
    if (confirm('Are you sure you want to delete this teacher?')) {
        $.post('manage_teachers.php', { action: 'delete', id: id }, function(response) {
            alert(response.message);
            if (response.success) {
                $('#teacher-' + id).remove(); // Remove the row from the table
            }
        }, 'json');
    }
}
</script>
</html>
