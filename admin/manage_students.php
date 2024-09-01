<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => 'Unknown error'];
    
    switch ($_POST['action']) {
        case 'add':
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $gender = $_POST['gender'];
            $dob = $_POST['dob'];

            $sql = "INSERT INTO Student (FirstName, LastName, Email, Gender, DOB) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssss", $firstName, $lastName, $email, $gender, $dob);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Student added successfully'];
                } else {
                    $response = ['success' => false, 'message' => 'Error adding student: ' . $stmt->error];
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
            $dob = $_POST['dob'];

            $sql = "UPDATE Student SET FirstName=?, LastName=?, Email=?, Gender=?, DOB=? WHERE StudentID=?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssssi", $firstName, $lastName, $email, $gender, $dob, $id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Student updated successfully'];
                } else {
                    $response = ['success' => false, 'message' => 'Error updating student: ' . $stmt->error];
                }
                $stmt->close();
            }
            break;
        
        case 'delete':
            $id = $_POST['id'];
            $sql = "DELETE FROM Student WHERE StudentID=?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Student deleted successfully'];
                } else {
                    $response = ['success' => false, 'message' => 'Error deleting student: ' . $stmt->error];
                }
                $stmt->close();
            }
            break;
    }
    echo json_encode($response);
    exit;
}

$sql = "SELECT StudentID, FirstName, LastName, Email, Gender, DOB FROM Student";
$result = $conn->query($sql);
$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="sidebar">
    <?php include '../components/admin_sidebar.php'; ?>
</div>
<div class="container mt-4">

<div class="content">
    <h1>Manage Students</h1>
    <button class="btn btn-primary" onclick="showAddForm()">Add New Student</button>
    <div id="addEditForm" style="display:none;" class="mt-3">
        <input type="hidden" id="studentId">
        <input type="text" id="firstName" class="form-control mb-2" placeholder="First Name">
        <input type="text" id="lastName" class="form-control mb-2" placeholder="Last Name">
        <input type="email" id="email" class="form-control mb-2" placeholder="Email">
        <select id="gender" class="form-control mb-2">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <input type="date" id="dob" class="form-control mb-2" placeholder="Date of Birth">
        <button class="btn btn-success" onclick="saveStudent()">Save</button>
        <button class="btn btn-secondary" onclick="hideForm()">Cancel</button>
    </div>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr id="student-<?= $student['StudentID'] ?>">
                <td><?= htmlspecialchars($student['FirstName']) ?></td>
                <td><?= htmlspecialchars($student['LastName']) ?></td>
                <td><?= htmlspecialchars($student['Email']) ?></td>
                <td><?= htmlspecialchars($student['Gender']) ?></td>
                <td><?= htmlspecialchars($student['DOB']) ?></td>
                <td>
                    <button class="btn btn-success btn-sm" onclick="editStudent(<?= $student['StudentID'] ?>)">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteStudent(<?= $student['StudentID'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
function showAddForm() {
    $('#studentId').val('');
    $('#firstName').val('');
    $('#lastName').val('');
    $('#email').val('');
    $('#gender').val('');
    $('#dob').val('');
    $('#addEditForm').show();
}

function hideForm() {
    $('#addEditForm').hide();
}

function editStudent(id) {
    var row = $('#student-' + id);
    $('#studentId').val(id);
    $('#firstName').val(row.find('td:eq(0)').text());
    $('#lastName').val(row.find('td:eq(1)').text());
    $('#email').val(row.find('td:eq(2)').text());
    $('#gender').val(row.find('td:eq(3)').text());
    $('#dob').val(row.find('td:eq(4)').text());
    $('#addEditForm').show();
}

function saveStudent() {
    var id = $('#studentId').val();
    var action = id ? 'edit' : 'add';
    $.post('manage_students.php', {
        action: action,
        id: id,
        first_name: $('#firstName').val(),
        last_name: $('#lastName').val(),
        email: $('#email').val(),
        gender: $('#gender').val(),
        dob: $('#dob').val()
    }, function(response) {
        alert(response.message);
        if (response.success) {
            location.reload(); // Reload the page to see changes
        }
    }, 'json');
}

function deleteStudent(id) {
    if (confirm('Are you sure you want to delete this student?')) {
        $.post('manage_students.php', { action: 'delete', id: id }, function(response) {
            alert(response.message);
            if (response.success) {
                $('#student-' + id).remove(); // Remove the row from the table
            }
        }, 'json');
    }
}
</script>
</body>
</html>
