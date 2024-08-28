<?php
require_once '../config.php';

$sql = "SELECT ResultsID, StudentID, SubjectID, Marks FROM Result";
$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $result) {
    echo "Result ID: " . $result['ResultsID'] . "<br>";
    echo "Student ID: " . $result['StudentID'] . "<br>";
    echo "Subject ID: " . $result['SubjectID'] . "<br>";
    echo "Marks: " . $result['Marks'] . "<br><br>";
}
?>
