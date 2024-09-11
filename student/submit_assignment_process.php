<?php
session_start();
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['fileUpload'])) {
    $assignmentID = $_POST['assignmentID'];
    $studentID = $_POST['studentID'];


    if ($_FILES['fileUpload']['error'] !== UPLOAD_ERR_OK) {
        echo "File upload error: ";
        switch ($_FILES['fileUpload']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                echo "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                echo "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "The uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo "Missing a temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                echo "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                echo "File upload stopped by extension.";
                break;
            default:
                echo "Unknown error.";
                break;
        }
        exit;
    }

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
        echo "Upload directory does not exist or is not writable.";
        exit;
    }

    $filePath = $uploadDir . basename($_FILES['fileUpload']['name']);
    if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $filePath)) {
        $submittedDate = date("Y-m-d H:i:s");
        $query = "INSERT INTO Submission (AssignmentID, StudentID, SubmittedDate, FilePath) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if ($stmt === false) {
            echo "Error preparing statement: " . $conn->error;
            exit;
        }

        $stmt->bind_param("iiss", $assignmentID, $studentID, $submittedDate, $filePath);
        
        if ($stmt->execute()) {
            echo "Assignment submitted successfully.";
        } else {
            echo "Error submitting assignment: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Error moving uploaded file.";
    }

    $conn->close();
} else {
    echo "Invalid request. No file was uploaded.";
}
?>
