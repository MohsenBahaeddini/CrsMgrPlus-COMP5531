<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../database.php'; 
require_once '../logs.php'; 

if (!isset($_SESSION['user_id'], $_SESSION['usergroup'])) {
    die("You must be logged in and part of a group to access this file.");
}

if (isset($_GET['file_id'])) {
    $fileId = intval($_GET['file_id']);
    $groupId = $_SESSION['usergroup'];

    $stmt = $conn->prepare("SELECT file_name,file_type,file_data FROM group_files WHERE file_id = ? AND group_id = ?");
    $stmt->bind_param("ii", $fileId, $groupId);
    $stmt->execute();
    $result = $stmt->get_result();
    

    if ($result->num_rows > 0) {
        writeToLog($conn, $_SESSION['user_id'], "DOWNLOAD", "Downloaded file with ID: " . $fileId);
        $row = $result->fetch_assoc();
        $file_name = $row['file_name'];
        $file_type = $row['file_type'];
        $file_data = $row['file_data'];

        header('Content-Type: ' . $file_type);
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Content-Length: ' . strlen($file_data));
        echo $file_data;
        exit;
    } else {
        writeToLog($conn, $_SESSION['user_id'], "DOWNLOAD_FAILED", "File not found with ID: " . $fileId);
        echo "File not found or you do not have permission to access this file.";
    }
} else {
    writeToLog($conn, $_SESSION['user_id'], "DOWNLOAD_INVALID_REQUEST", "Invalid download request.");
    echo "Invalid request.";
}
?>
