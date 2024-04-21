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

if (isset($_GET['file_id']) && isset($_SESSION['usergroup'])) {
    $fileId = intval($_GET['file_id']);
    $groupId = $_SESSION['usergroup'];

    // Check if the file exists and belongs to the group
    $stmt = $conn->prepare("SELECT file_id FROM group_files WHERE file_id = ? AND group_id = ?");
    $stmt->bind_param("ii", $fileId, $groupId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // File exists, proceed with deletion
        $deleteStmt = $conn->prepare("DELETE FROM group_files WHERE file_id = ? AND group_id = ?");
        $deleteStmt->bind_param("ii", $fileId, $groupId);
        $deleteStmt->execute();
        $deleteStmt->close();
        
        
        // Log the deletion
        writeToLog($conn, $_SESSION['user_id'], "DELETE", "Deleted file with ID: " . $fileId);
        
        // Redirect back to group files page
        header('Location: group_files.php');
        exit;
    } else {
        // File does not exist or user does not have permission
        writeToLog($conn, $_SESSION['user_id'], "DELETE_FAILED", "File not found or access denied: " . $fileId);
        echo "File not found or you do not have permission to access this file.";
    }
} else {
    writeToLog($conn, $_SESSION['user_id'], "DELETE_INVALID_REQUEST", "Invalid delete request.");
    echo "Invalid request.";
}
?>
