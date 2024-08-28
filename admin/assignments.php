<?php
require_once '../config.php';


$sql = "SELECT a.AssignmentID, a.Name, a.DueDate, a.Description, s.Name AS SubjectName FROM Assignment a JOIN Subject s ON a.SubjectID = s.SubjectID";
$result = $conn->query($sql);

if ($result) {
    echo "<table><tr><th>Assignment ID</th><th>Name</th><th>Due Date</th><th>Description</th><th>Subject</th></tr>";
    while ($assignment = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($assignment['AssignmentID']) . "</td>";
        echo "<td>" . htmlspecialchars($assignment['Name']) . "</td>";
        echo "<td>" . htmlspecialchars($assignment['DueDate']) . "</td>";
        echo "<td>" . htmlspecialchars($assignment['Description']) . "</td>";
        echo "<td>" . htmlspecialchars($assignment['SubjectName']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment List</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php // Your PHP code here ?>
</body>
</html>

