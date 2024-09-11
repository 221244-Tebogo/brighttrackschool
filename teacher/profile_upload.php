<?php

$uploadFileDir = 'uploads/';
if (!file_exists($uploadFileDir)) {
    mkdir($uploadFileDir, 0777, true);
}

$fileTmpPath = $_FILES['profileImage']['tmp_name'];
$fileName = $_FILES['profileImage']['name'];
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));
$newFileName = $teacherID . '_' . time() . '.' . $fileExtension;
$dest_path = $uploadFileDir . $newFileName;


$allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];

if (in_array($fileExtension, $allowedfileExtensions)) {
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $profileImagePath = $newFileName; 
    } else {
        echo "<p>Error uploading profile image. Make sure the 'uploads/' directory has the correct permissions.</p>";
    }
} else {
    echo "<p>Invalid file type for profile image. Only JPG, PNG, and GIF are allowed.</p>";
}
?>
